<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Constants\TransactionType;
use App\Models\Customer;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\SaleOrder;
use App\Models\Stock;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SaleOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     * @throws \Exception
     */
    public function index()
    {
        if (\request()->ajax()) {

            $startDate = \request('start_date');
            $endDate = \request('end_date');
            $status = \request('status');

            $data = SaleOrder::query()
                ->with('customer')
                ->withCount('items')
                ->withSum('items', DB::raw("quantity * price"))
                ->when($startDate, fn($query, $startDate) => $query->whereDate('order_date', '>=', $startDate))
                ->when($endDate, fn($query, $endDate) => $query->whereDate('order_date', '<=', $endDate))
                ->when($status, fn($query, $status) => $query->where('status', $status));

            return \DataTables::of($data)
                ->addColumn('action', fn(SaleOrder $saleOrder) => view('admin.sales.partials.actions', compact('saleOrder')))
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.sales.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        $products = Product::query()
            ->where('stock_quantity', '>', 0)
            ->get();
        $paymentMethods = PaymentMethod::query()->get();
        return view('admin.sales.create', compact('customers', 'products', 'paymentMethods'));
    }

    /**
     * Store a newly created resource in storage.
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'], // Ensure supplier exists
            'status' => ['nullable', 'in:pending,completed,canceled'], // Customize statuses as needed
            'order_date' => ['required', 'date'],
            'product_ids' => ['required', 'array'],
            'quantities' => ['required', 'array'],
            'prices' => ['required', 'array'],
            'payment_method_id' => ['nullable', 'exists:payment_methods,id'],
            'amount' => ['nullable', 'numeric', 'min:0'],
        ]);
        try {
            DB::beginTransaction();
            $total_amount = 0;
            // Create the purchase order
            $order = SaleOrder::create([
                'customer_id' => $data['customer_id'],
                'status' => $data['status'] ?? 'Order',
                'order_date' => $data['order_date'],
                'total_amount' => $total_amount,
                'done_by' => auth()->id()
            ]);

            $order->generateInvoiceNumber();

            // Loop through items and add them to the purchase order
            foreach ($data['product_ids'] as $index => $product_id) {
                $qty = $data['quantities'][$index];
                $price = $data['prices'][$index];
                $orderItem = $order->items()->create([
                    'product_id' => $product_id,
                    'quantity' => $qty,
                    'price' => $price,
                ]);

                $total_amount += $qty * $price;
                // Find and update product stock quantity
                $product = Product::find($product_id);
                $newQty = $qty;

                if ($product) {
                    // Check if the product is sold in square meters
                    if ($product->sold_in_square_meters) {
                        // Calculate the number of boxes required (rounding up)
                        $newQty = round($qty / $product->box_coverage, 2); // Now $newQty is in boxes
                    }

                    // Ensure the stock is sufficient (checking in boxes or units)
                    if ($product->stock_quantity < $newQty) {
                        DB::rollBack(); // Roll back the transaction
                        return redirect()->back()->withErrors(['error' => 'Insufficient stock for product: ' . $product->name])
                            ->withInput($data); // Return error and retain the form inputs
                    }

                    // Update the product's stock quantity
                    $product->stock_quantity -= $newQty; // Decrease by newQty (boxes or units)
                    $product->save(); // Save changes to product


                    // Record the stock transaction
                    $orderItem->stockTransactions()->create([
                        'product_id' => $product_id,
                        'transaction_type' => TransactionType::OUT, // Stock outflow
                        'quantity' => $newQty, // The adjusted quantity (boxes/units)
                        'reason' => 'Sale Order #' . $order->invoice_number
                    ]);
                }


            }

            $order->update(['total_amount' => $total_amount]);
            $amountPaid = $request->integer('amount');
            if ($amountPaid > 0) {
                app(SalePaymentController::class)->store($request, $order);
                // if amount paid is greater than total amount, update customer balance
                $this->updateCustomerBalance($amountPaid, $total_amount, $order);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback in case of error
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput($data);
        }
        if ($request->ajax()) {
            return response()->json([
                'success' => 'Sales order saved successfully.',
                'url' => route('admin.sale-orders.index')
            ]);
        }

        return redirect()->route('admin.sale-orders.index')->with('success', 'Sales order saved successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SaleOrder $saleOrder)
    {
        $saleOrder->load('customer', 'items.product');
        return view('admin.sales.show', compact('saleOrder'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaleOrder $saleOrder)
    {
        $customers = Customer::all();
        $products = Product::all();
        return view('admin.purchase.edit', compact('saleOrder', 'customers', 'products'));
    }

    /**
     * Update the specified resource in storage.
     * @throws \Throwable
     */
    public function update(Request $request, SaleOrder $saleOrder)
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

        DB::transaction(function () use ($data, $saleOrder) {
            // Step 1: Rollback stock for existing items
            foreach ($saleOrder->items()->get() as $existingItem) {
                $product = Product::find($existingItem->product_id);
                if ($product) {
                    // Subtract the existing item's quantity from the stock
                    $product->stock_quantity -= $existingItem->quantity;
                    $product->save(); // Save the updated stock
                }
            }

            // Step 2: Update the purchase order
            $saleOrder->update([
                'supplier_id' => $data['supplier_id'],
                'status' => $data['status'],
                'delivery_date' => $data['delivery_date'],
            ]);

            // Step 3: Delete existing items and re-add with updated quantities
            $saleOrder->items()->delete(); // Remove existing items

            // Step 4: Add new items and update stock quantities
            foreach ($data['items'] as $itemData) {
                // Add new item to purchase order
                $saleOrder->items()->create($itemData);

                // Update stock with new quantities
                $product = Product::find($itemData['product_id']);
                if ($product) {
                    // Add the new quantity to the stock
                    $product->stock_quantity += $itemData['quantity'];
                    $product->save(); // Save updated stock
                }
            }
        });

        return response()->json([
            'success' => 'Sales order updated successfully.',
            'url' => route('admin.sale-orders.index')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @throws \Throwable
     */
    public function destroy(SaleOrder $saleOrder)
    {
        if ($saleOrder->status !== Status::ORDER) {
            return response()->json(['error' => 'Only pending orders can be deleted.']);
        }
        DB::beginTransaction();
        // rollback stock
        $this->rollbackStock($saleOrder);
        $saleOrder->items()->delete(); // Delete all items
        $saleOrder->delete(); // Delete the purchase order
        DB::commit();
        return response()->json(['success' => 'Purchase order deleted successfully.']);
    }

    /**
     * @throws \Throwable
     */
    public function cancel(SaleOrder $saleOrder)
    {
        if ($saleOrder->status !== Status::ORDER) {
            return response()->json(['error' => 'Only pending orders can be canceled.']);
        }
        DB::beginTransaction();
        $saleOrder->update(['status' => Status::CANCELLED]);
        // rollback stock
        $this->rollbackStock($saleOrder);
        DB::commit();
        return response()->json(['success' => 'Sales order canceled successfully.']);
    }


    public function print(SaleOrder $saleOrder)
    {
        $saleOrder->load('customer', 'items.product');
        $data = QrCode::size(512)
//            ->format('png')
//            ->merge(public_path('assets/media/logos/logo.png'), 0.3, true)
            ->errorCorrection('M')
            ->generate(
                route('home.order-verify', encodeId($saleOrder->id))
            );
        $downloadName = 'sales-order-' . $saleOrder->invoice_number . now()->toDateTimeLocalString() . '.pdf';
        $pdf = Pdf::loadView('admin.sales.print', compact('saleOrder', 'data'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream($downloadName);

    }

    /**
     * @param SaleOrder $saleOrder
     * @return void
     */
    public function rollbackStock(SaleOrder $saleOrder): void
    {
        $saleOrder->load('items.product');
        foreach ($saleOrder->items as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                $product->stock_quantity += $item->quantity;
                $product->save();
            }
        }
    }

    public function search()
    {
        $orderNumber = \request('order_number');
        $saleOrder = SaleOrder::query()
            ->where(DB::raw('LOWER(invoice_number)'), '=', strtolower($orderNumber))
            ->first();

        if (is_null($saleOrder)) {
            return response()->json([
                'success' => false,
                'message' => 'Sale order not found.'
            ], 404);
        }
        $amountToPay = $saleOrder->items()->sum(DB::raw('price * quantity'));
        $amountPaid = $saleOrder->payments()->whereRaw('lower(status)="paid"')->sum('amount');
        $remaining = $amountToPay - $amountPaid;

        return view('admin.sales._search', compact('saleOrder', 'amountPaid', 'remaining', 'amountToPay'));
    }

    /**
     * @param int $amountPaid
     * @param float|int $total_amount
     * @param SaleOrder $order
     * @return void
     */
    public function updateCustomerBalance(int $amountPaid, float|int $total_amount, SaleOrder $order): void
    {
        if ($amountPaid > $total_amount) {
            $customer = Customer::find($order->customer_id);
            $customer->balance += $amountPaid - $total_amount;
        }
    }
}
