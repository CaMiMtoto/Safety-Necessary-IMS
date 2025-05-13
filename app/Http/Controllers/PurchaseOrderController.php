<?php

namespace App\Http\Controllers;

use App\Constants\TransactionType;
use App\Exports\PurchaseHistoryExport;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use DataTables;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     * @throws Exception
     */
    public function index()
    {
        if (\request()->ajax()) {

            $startDate = \request('start_date');
            $endDate = \request('end_date');
            $supplierId = \request('supplier_id');
            $data = PurchaseOrder::query()
                ->withSum('items', DB::raw('price * quantity'))
                ->with('supplier')
                ->withCount('items')
                ->when($startDate, fn($query, $startDate) => $query->whereDate('delivery_date', '>=', $startDate))
                ->when($endDate, fn($query, $endDate) => $query->whereDate('delivery_date', '<=', $endDate))
                ->when($supplierId, fn($query, $supplierId) => $query->where('supplier_id', $supplierId));


            return DataTables::of($data)
                ->addColumn('action', function ($purchaseOrder) {
                    return '<div class="dropdown">
                                <button class="btn btn-light btn-sm btn-icon dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="' . route('admin.purchase-orders.show', $purchaseOrder->id) . '" >Details</a>
                                    <a class="dropdown-item" href="' . route('admin.purchase-orders.print', $purchaseOrder->id) . '" >Print</a>
                                    <a class="dropdown-item js-edit" href="' . route('admin.purchase-orders.edit', $purchaseOrder->id) . '" >Edit</a>
                                    <a class="dropdown-item js-delete" href="' . route('admin.purchase-orders.destroy', $purchaseOrder->id) . '">Delete</a>
                                </div>
                            </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $suppliers = Supplier::all();
        return view('admin.purchase.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('admin.purchase.create', compact('suppliers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     * @throws Throwable
     */
    public function store(Request $request)
    {
//        return $request->all();
        $data = $request->validate([
            'supplier_id' => ['nullable', 'exists:suppliers,id'], // Ensure supplier exists
            'status' => ['nullable', 'in:pending,completed,canceled'], // Customize statuses as needed
            'delivery_date' => ['required', 'date'],
            'product_ids' => ['required', 'array'],
            'quantities' => ['required', 'array'],
            'prices' => ['required', 'array'],
        ]);

        DB::transaction(function () use ($data) {
            // Create the purchase order
            $purchaseOrder = PurchaseOrder::create([
                'supplier_id' => $data['supplier_id'],
                'status' => $data['status'] ?? 'pending',
                'delivery_date' => $data['delivery_date'],
                'done_by' => auth()->id()
            ]);

            $purchaseOrder->generateInvoiceNumber();

            // Loop through items and add them to the purchase order
            foreach ($data['product_ids'] as $index => $product_id) {
                $qty = $data['quantities'][$index];
                $orderItem = $purchaseOrder->items()->create([
                    'product_id' => $product_id,
                    'quantity' => $qty,
                    'price' => $data['prices'][$index],
                ]);

                // Find and update product stock quantity
                $product = Product::find($product_id); // Retrieve the product

                if ($product) {
                    // Validate quantity to ensure it’s a positive number
                    if ($qty <= 0) {
                        throw new Exception("Invalid quantity: Quantity must be greater than zero.");
                    }

                    $newQty = $qty;

                    // Check if the product is managed in square meters
                    if ($product->sold_in_square_meters) {
                        // Convert square meters to boxes, rounding up to ensure full boxes are purchased
                        $newQty = round($qty / $product->box_coverage, 2);
                    }

                    // Update stock quantity (boxes)
                    $product->stock_quantity += $newQty;
                    $product->save(); // Save the updated stock to the database

                    // Record the stock transaction
                    $orderItem->stockTransactions()->create([
                        'product_id' => $product_id,
                        'transaction_type' => TransactionType::IN, // Indicating stock inflow
                        'quantity' => $newQty, // Quantity added to stock (in boxes)
                        'reason' => 'Purchase Order #' . $purchaseOrder->invoice_number .
                            ($product->sold_in_square_meters ?
                                " (Purchased in Square Meters={$qty}, Converted to Boxes={$newQty})" :
                                '')
                    ]);
                } else {
                    throw new Exception("Product not found for ID: {$product_id}");
                }

            }
        });

        if ($request->ajax()) {
            return response()->json([
                'success' => 'Purchase order saved successfully.',
                'url' => route('admin.purchase-orders.index')
            ]);
        }

        return redirect()->route('admin.purchase-orders.index')->with('success', 'Purchase order saved successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load('supplier', 'items.product');
        return view('admin.purchase.show', compact('purchaseOrder'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PurchaseOrder $purchaseOrder)
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('admin.purchase.edit', compact('purchaseOrder', 'suppliers', 'products'));
    }

    /**
     * Update the specified resource in storage.
     * @throws Throwable
     */
    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        $data = $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'], // Ensure supplier exists
            'status' => ['nullable', 'in:pending,completed,canceled'], // Customize statuses as needed
            'delivery_date' => ['required', 'date'],
            'items' => ['required', 'array'],
            'items.*.product_id' => ['required', 'exists:products,id'], // Ensure products exist
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($data, $purchaseOrder) {
            // Step 1: Rollback stock for existing items
            foreach ($purchaseOrder->items()->get() as $existingItem) {
                $product = Product::find($existingItem->product_id);
                if ($product) {
                    // Subtract the existing item's quantity from the stock
                    $product->stock_quantity -= $existingItem->quantity;
                    $product->save(); // Save the updated stock
                }
            }

            // Step 2: Update the purchase order
            $purchaseOrder->update([
                'supplier_id' => $data['supplier_id'],
                'status' => $data['status'],
                'delivery_date' => $data['delivery_date'],
            ]);

            // Step 3: Delete existing items and re-add with updated quantities
            $purchaseOrder->items()->delete(); // Remove existing items

            // Step 4: Add new items and update stock quantities
            foreach ($data['items'] as $itemData) {
                // Add new item to purchase order
                $orderItem = $purchaseOrder->items()->create($itemData);

                // Update stock with new quantities
                $product_id = $itemData['product_id'];
                $product = Product::find($product_id);
                if ($product) {
                    // Add the new quantity to the stock
                    $qty = $itemData['quantity'];
                    $product->stock_quantity += $qty;
                    $product->save(); // Save updated stock
                }

            }
        });

        return response()->json([
            'success' => 'Purchase order updated successfully.',
            'url' => route('admin.purchase-orders.index')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->items()->delete(); // Delete all items
        $purchaseOrder->delete(); // Delete the purchase order
        return response()->json(['success' => 'Purchase order deleted successfully.']);
    }


    public function print(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load('supplier', 'items.product');

        $downloadName = 'purchase-order-' . $purchaseOrder->invoice_number . now()->toDateTimeLocalString() . '.pdf';
        $pdf = Pdf::loadView('admin.purchase.print', compact('purchaseOrder'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream($downloadName);
    }

    public function history()
    {
        $products = Product::query()->whereHas('purchases')->latest()->get();
        return view('admin.purchase.history', compact('products'));
    }

    public function exportHistory(ReportService $reportService)
    {
        $startDate = \request('start_date');
        $endDate = \request('end_date');
        $productId = \request('product_id');

        $data = $reportService->getPurchaseQueryBuilder($startDate, $endDate, $productId)
            ->get();
        $pdf = PDF::loadView('admin.purchase.pdf-history', compact('data', 'startDate', 'endDate'));
        return $pdf->stream("purchase-history.pdf");
    }
}
