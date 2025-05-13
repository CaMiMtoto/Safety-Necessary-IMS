<?php

namespace App\Services;

use App\Constants\Status;
use App\Models\Expense;
use App\Models\PaymentMethod;
use App\Models\PurchaseOrderItem;
use App\Models\SaleOrderItem;
use App\Models\SalePayment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HigherOrderWhenProxy;
use LaravelIdea\Helper\App\Models\_IH_PurchaseOrderItem_QB;

class ReportService
{

    /**
     * @param string $endDate
     * @param string $startDate
     * @param $productId
     * @return PurchaseOrderItem|Builder|HigherOrderWhenProxy|_IH_PurchaseOrderItem_QB
     */
    public function getPurchaseQueryBuilder(string $startDate, string $endDate, $productId): HigherOrderWhenProxy|PurchaseOrderItem|_IH_PurchaseOrderItem_QB|Builder
    {
        return PurchaseOrderItem::query()
            ->with(['purchaseOrder.supplier', 'product'])
            ->whereHas('purchaseOrder', function (Builder $query) use ($endDate, $startDate) {
                $query->when($startDate, function (Builder $query) use ($startDate) {
                    $query->whereDate('delivery_date', '>=', $startDate);
                })->when($endDate, function (Builder $query) use ($endDate) {
                    $query->whereDate('delivery_date', '<=', $endDate);
                });
            })
            ->when($productId, function (Builder $query) use ($productId) {
                $query->where('product_id', '=', $productId);
            });
    }

    public function getSalesQueryBuilder($startDate, $endDate, $productId, $status = null)
    {
        return SaleOrderItem::query()
            ->with('saleOrder.customer') // Eager load related customer data
            ->whereHas('saleOrder', function (Builder $query) use ($productId, $status, $startDate, $endDate) {
                // Filter by date range
                $query->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('order_date', [$startDate, $endDate]);
                })
                    // Filter by status if provided
                    ->when($status, function ($query) use ($status) {
                        $query->where('status', '=', $status);
                    })
                    // Exclude cancelled orders when no status is provided
                    ->when(is_null($status), function ($query) {
                        $query->where(\DB::raw("LOWER(status)"), '!=', strtolower(Status::CANCELLED));
                    })
                    // Filter by product ID if provided
                    ->when($productId, function ($query) use ($productId) {
                        $query->where('product_id', '=', $productId);
                    });
            });
    }


    public function getSalesPaymentQueryBuilder(
        string $startDate,
        string $endDate,
               $paymentMethodId
    )
    {
        return SalePayment::query()
            ->with(['saleOrder.customer', 'paymentMethod', 'user'])
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereDate('payment_date', '>=', $startDate)
                    ->whereDate('payment_date', '<=', $endDate);
            })
            ->when($paymentMethodId, function ($query) use ($paymentMethodId) {
                $query->where('payment_method_id', '=', $paymentMethodId);
            })
            ->where('status', Status::PAID);

    }
    public function getTotalPaymentsByMethod($startDate, $endDate, $paymentMethodId = null): \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|\LaravelIdea\Helper\App\Models\_IH_PaymentMethod_C|array
    {
        return PaymentMethod::query()
            ->leftJoin('sale_payments', function ($join) use ($startDate, $endDate) {
                $join->on('sale_payments.payment_method_id', '=', 'payment_methods.id')
                    ->whereBetween(\DB::raw('DATE(sale_payments.payment_date)'), [$startDate, $endDate])
                    ->where('sale_payments.status', '=', Status::PAID);
            })
            ->when($paymentMethodId, function ($query) use ($paymentMethodId) {
                $query->where('payment_methods.id', '=', $paymentMethodId);
            })
            ->select('payment_methods.name as payment_method',
                \DB::raw('IFNULL(SUM(sale_payments.amount), 0) as total_amount'))
            ->groupBy('payment_methods.name')
            ->get();
    }


    public function getExpensesQueryBuilder($startDate, $endDate, $categoryId = null)
    {
        return Expense::query()
            ->with('category') // Eager load related customer data
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            })
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('expense_category_id', '=', $categoryId);
            });
    }


}
