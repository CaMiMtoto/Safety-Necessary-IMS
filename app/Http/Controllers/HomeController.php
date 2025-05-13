<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Models\Product;
use App\Models\SaleOrder;
use App\Models\SaleOrderItem;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        return redirect('admin/dashboard');
    }

    public function dashboard()
    {
        // Data for stock overview
        $totalStockValue = Product::sum('price');
        $lowStockProducts = Product::query()->whereColumn('stock_quantity', '<=', 'reorder_level')->get();
        $outOfStockProducts = $lowStockProducts->where('stock_quantity', 0);

        // Sales data
        $totalSalesValue = SaleOrderItem::query()
            ->whereHas('saleOrder', function ($query) {
                $query->where('status','!=', Status::CANCELLED);
            })->sum(DB::raw('price * quantity'));
        $topSellingProducts = Product::withCount('orders')
            ->with('category')
            ->orderBy('orders_count', 'desc')->take(5)->get();

        $pendingDeliveries = SaleOrder::whereNotIn('status', [Status::DELIVERED, Status::CANCELLED])->count();
        $salesTrends = $this->salesTrends();
        return view('admin.dashboard', [
            'totalStockValue' => $totalStockValue,
            'lowStockProducts' => $lowStockProducts,
            'outOfStockProducts' => $outOfStockProducts,
            'totalSalesValue' => $totalSalesValue,
            'topSellingProducts' => $topSellingProducts,
            'pendingDeliveries' => $pendingDeliveries,
            'months' => $salesTrends['months'],
            'sales' => $salesTrends['sales']
        ]);
    }

    public function salesTrends()
    {
        $salesData = DB::table('sale_order_items as soi')
            ->join('sale_orders as so', 'soi.sale_order_id', '=', 'so.id')
            ->select(DB::raw("DATE_FORMAT(so.order_date, '%b') as month, SUM(total_amount) as count,MONTH(so.order_date) as month_num"))
            ->whereYear('so.order_date', Carbon::now()->year)
            ->groupBy("month_num", "month")
            ->orderBy("month_num")
            ->get();
        $months = [];
        $sales = [];

        foreach ($salesData as $data) {
            $months[] = $data->month;
            $sales[] = $data->count;
        }
        return [
            'months' => $months,
            'sales' => $sales
        ];

    }

    public function verifyOrder($id)
    {
        $saleOrder = SaleOrder::find(decodeId($id));

        if (is_null($saleOrder))
            return response()->json(['error' => 'Order not found'], 400);

        return view('admin.sales.show', compact('saleOrder'));

    }
}
