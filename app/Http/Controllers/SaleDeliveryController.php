<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Models\SaleDelivery;
use App\Models\SaleOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class SaleDeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SaleOrder $saleOrder)
    {
        $saleOrder->load(['items.product', 'customer', 'deliveries.items.product']);
        //        return $saleOrder;
        return view('admin.sales.deliveries', compact('saleOrder'));
    }


    /**
     * Store a newly created resource in storage.
     * @throws Throwable
     */
    public function store(Request $request, SaleOrder $saleOrder)
    {
        $saleOrder->load('items');
        $data = $request->validate([
            'quantities' => ['required', 'array'],
            'items' => ['required', 'array'],
            'delivery_address' => ['required', 'string'],
            'delivered_by' => ['required', 'string'],
        ]);

        DB::beginTransaction();

        // check is all quantities are zeroes
        if (array_sum($data['quantities']) == 0) {
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json(['error' => 'No quantity supplied for any item , please supply quantity for at least one item'], 422);
            }
            return back()->with('error', 'No quantity supplied for any item , please supply quantity for at least one item');
        }

        $saleDelivery = $saleOrder->deliveries()->create([
            'delivery_address' => $data['delivery_address'],
            'delivered_by' => $data['delivered_by'],
            'delivery_date' => now(),
            'delivery_status' => Status::PARTIALLY_DELIVERED
        ]);
        for ($i = 0; $i < count($data['quantities']); $i++) {
            $item = $saleOrder->items()->find($data['items'][$i]);
            $quantity = $data['quantities'][$i];

            if ($quantity <= 0) continue;

            if ($quantity > $item->remaining) {
                DB::rollBack();
                if ($request->ajax()) {
                    return response()->json(['error' => 'Quantity supplied for item ' . $item->product->name . ' is more than remaining (' . $item->remaining . ')'], 422);
                }
                return back()->with('error', 'Quantity supplied for item ' . $item->product->name . ' is more than remaining (' . $item->remaining . ')');
            }
            // calculate remaining
            $remaining = $item->remaining - $quantity;
            $saleDelivery->items()->create([
                'sale_order_item_id' => $item->id,
                'product_id' => $item->product_id,
                'quantity' => $quantity,
                'remaining' => $remaining,
            ]);
        }

        // check if all items have been delivered
        $sum = $saleOrder->items->sum('remaining');
        if ($sum <= 0.0) {
            $saleOrder->update(['status' => Status::DELIVERED]);
            $saleDelivery->update(['delivery_status' => Status::DELIVERED]);
        } else {
            $saleOrder->update(['status' => Status::PARTIALLY_DELIVERED]);
        }

        DB::commit();

        if ($request->ajax()) {
            return response()->json(['message' => 'Delivery created successfully']);
        }

        return back()->with('success', 'Delivery created successfully');
    }

    /**
     * @throws Throwable
     */
    public function destroy(SaleDelivery $saleDelivery)
    {
        DB::beginTransaction();
        $saleDelivery->load('items');
        $saleDelivery->delete();
        DB::commit();
        if (\request()->ajax()) {
            return response()->json(['success' => 'Delivery deleted successfully.']);
        }
        return back()->with('success', 'Delivery deleted successfully');
    }

    public function print(SaleDelivery $saleDelivery)
    {
        $saleDelivery->load(['items.product', 'saleOrder.customer']);
        $saleOrder = $saleDelivery->saleOrder;
//        return view('admin.sales.delivery_note', compact('saleDelivery', 'saleOrder'));

        $pdf = PDF::loadView('admin.sales.delivery_note', compact('saleDelivery', 'saleOrder'));
        $fileName = 'Delivery Note - ' . $saleDelivery->saleOrder->customer->name . ' - ' . $saleDelivery->delivery_date . '.pdf';
        $fileName = \Str::of($fileName)->slug('_');
        return $pdf->stream($fileName);
    }

}
