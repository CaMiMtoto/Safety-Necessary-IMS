<?php

namespace App\Http\Controllers;

use App\Constants\TransactionType;
use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Yajra\DataTables\Exceptions\Exception;

class StockTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @throws Exception
     * @throws \Exception
     */
    public function index()
    {
        if (request()->ajax()) {
            $source = StockTransaction::query()
                ->with('product');
            return datatables()->of($source)
                ->addIndexColumn()
                ->addColumn('actions', function (StockTransaction $row) {
                    return "";
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('stock_transactions.index');
    }

    /**
     * Display a listing of the resource.
     * @throws Exception
     * @throws \Exception
     */
    public function adjustments()
    {
        if (request()->ajax()) {
            $source = StockTransaction::query()
                ->where('transaction_type', '=', TransactionType::ADJUSTMENT)
                ->with('product');
            return datatables()->of($source)
                ->addIndexColumn()
                ->addColumn('actions', function (StockTransaction $row) {
                    return "";
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        $products = Product::query()->get();

        return view('stock_transactions.adjustments', [
            'products' => $products
        ]);
    }

    public function adjustStock(Request $request)
    {
        // Validate the request
        $data = $request->validate([
            'product_id' => 'required',
            'quantity' => 'required|integer|min:0',  // The new stock quantity
            'reason' => 'required|string',
        ],[
            'quantity.min' => 'The new stock quantity must be greater than or equal to 0.',
            'product_id.required' => 'Product ID is required.',
            'reason.required' => 'Reason is required.',
        ]);

        $product = Product::findOrFail($data['product_id']);


        // Adjust the stock to the new quantity
        $product->adjustStock($data['quantity'], $data['reason']);

        return response()->json(['message' => 'Stock adjusted successfully to the new quantity.']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(StockTransaction $stockTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockTransaction $stockTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StockTransaction $stockTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockTransaction $stockTransaction)
    {
        //
    }
}
