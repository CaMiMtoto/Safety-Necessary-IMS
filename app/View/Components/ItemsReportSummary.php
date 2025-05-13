<?php

namespace App\View\Components;

use App\Constants\Status;
use App\Models\Product;
use App\Models\PurchaseOrderItem;
use App\Models\SaleDeliveryItem;
use App\Models\SaleOrderItem;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class ItemsReportSummary extends Component
{

    public string $category = "summary";
    public string $startDate;
    public string $endDate;
    public string $productName = "";

    /**
     * Create a new component instance.
     */
    public function __construct(string $category,
                                string $startDate,
                                string $endDate,
                                string $productName,)
    {
        $this->category = $category;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->productName = $productName;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $purchaseOrderItem_QB = PurchaseOrderItem::query()
            ->whereHas('purchaseOrder', function ($query) {
                $query->whereDate('delivery_date', '>=', $this->startDate)
                    ->whereDate('delivery_date', '<=', $this->endDate)
                    ->where('status', '!=', Status::CANCELLED);
            })
            ->when($this->productName, function (Builder $query) {
                $query->whereHas('product', function ($query) {
                    $query->where('name', 'like', '%' . $this->productName . '%');
                });
            });
        $saleOrderItem_QB = SaleOrderItem::query()
            ->whereHas('saleOrder', function (Builder $query) {
                $query->whereDate('order_date', '>=', $this->startDate)
                    ->whereDate('order_date', '<=', $this->endDate)
                    ->where('status', '!=', Status::CANCELLED);
            })
            ->when($this->productName, function (Builder $query) {
                $query->whereHas('product', function ($query) {
                    $query->where('name', 'like', '%' . $this->productName . '%');
                });
            });
        $saleDeliveryItem_QB = SaleDeliveryItem::query()
            ->whereHas('saleDelivery', function (Builder $query) {
                $query->whereDate('delivery_date', '>=', $this->startDate)
                    ->whereDate('delivery_date', '<=', $this->endDate);
            })
            ->when($this->productName, function (Builder $query) {
                $query->whereHas('product', function ($query) {
                    $query->where('name', 'like', '%' . $this->productName . '%');
                });
            });
        $totalReceived = $purchaseOrderItem_QB->sum('quantity');
        $totalReceivedAmount = $purchaseOrderItem_QB->sum(DB::raw('quantity * price'));
        $totalSales = $saleOrderItem_QB->sum('quantity');
        $totalSalesAmount = $saleOrderItem_QB->sum(DB::raw('quantity * price'));
        $totalDelivery = $saleDeliveryItem_QB->sum('quantity');
        $totalDeliveryAmount = $saleOrderItem_QB->whereHas('saleDeliveryItems')->sum(DB::raw('quantity * price'));
        $products = Product::query()->when($this->productName, function (Builder $query) {
            $query->where('name', 'like', '%' . $this->productName . '%');
        })->get();
        return view('components.items-report-summary', [
            'totalReceived' => $totalReceived,
            'totalReceivedAmount' => $totalReceivedAmount,
            'totalSales' => $totalSales,
            'totalSalesAmount' => $totalSalesAmount,
            'totalDelivery' => $totalDelivery,
            'totalDeliveryAmount' => $totalDeliveryAmount,
            'products' => $products,
        ]);
    }
}
