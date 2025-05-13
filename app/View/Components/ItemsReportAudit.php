<?php

namespace App\View\Components;

use App\Constants\Status;
use App\Models\Product;
use App\Models\PurchaseOrderItem;
use App\Models\SaleOrderItem;
use App\Models\SalePayment;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\Component;

class ItemsReportAudit extends Component
{
    public string $category = "summary";
    public string $startDate;
    public string $endDate;
    public string $productId;

    /**
     * Create a new component instance.
     */
    public function __construct(string $category,
                                string $startDate,
                                string $endDate,
                                string $productId)
    {
        $this->category = $category;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->productId = $productId;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $product = Product::query()->find($this->productId);
        $sales = SaleOrderItem::query()
            ->with(['saleOrder.customer'])
            ->when($this->startDate, function (Builder $query, $startDate) {
                $query->whereHas('saleOrder', function (Builder $query) use ($startDate) {
                    $query->whereDate('order_date', '>=', $startDate);
                });
            })
            ->when($this->endDate, function (Builder $query, $endDate) {
                $query->whereHas('saleOrder', function (Builder $query) use ($endDate) {
                    $query->whereDate('order_date', '<=', $endDate);
                });
            })
            ->where('product_id', '=', $this->productId)
            ->whereRelation('saleOrder', \DB::raw('lower(status)'), '!=', strtolower(Status::CANCELLED))
            ->get();

        $purchases = PurchaseOrderItem::query()
            ->with(['purchaseOrder.supplier'])
            ->when($this->startDate, function (Builder $query, $startDate) {
                $query->whereHas('purchaseOrder', function (Builder $query) use ($startDate) {
                    $query->whereDate('delivery_date', '>=', $startDate);
                });
            })
            ->when($this->endDate, function (Builder $query, $endDate) {
                $query->whereHas('purchaseOrder', function (Builder $query) use ($endDate) {
                    $query->whereDate('delivery_date', '<=', $endDate);
                });
            })
            ->where('product_id', '=', $this->productId)
            ->get();
        $payments = SalePayment::query()
            ->with(['saleOrder.customer', 'paymentMethod'])
            ->when($this->startDate, function (Builder $query, $startDate) {
                $query->whereDate('payment_date', '>=', $startDate);
            })
            ->when($this->endDate, function (Builder $query, $endDate) {
                $query->whereDate('payment_date', '<=', $endDate);
            })
            ->whereHas('saleOrder', function (Builder $query) {
                $query->whereHas('items', function (Builder $query) {
                    $query->where('product_id', '=', $this->productId);
                });
            })
            ->get();
        return view('components.items-report-audit', [
            'category' => $this->category,
            'product' => $product,
            'sales' => $sales,
            'purchases' => $purchases,
            'payments' => $payments,
        ]);
    }
}
