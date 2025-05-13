<?php

namespace App\Livewire;

use App\Constants\Status;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Services\ReportService;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Url;
use Livewire\Component;

class SalesPaymentReport extends Component
{
    #[Url]
    public string $startDate;
    #[Url]
    public string $endDate;
    #[Url]
    public $paymentMethodId;
    public Collection $paymentMethods;
    protected ReportService $reportService;

    public function __construct()
    {
        $this->reportService = new ReportService();
        $this->paymentMethods = PaymentMethod::query()->latest()->get();
        $this->startDate = now()->subDays(30)->format('Y-m-d');
        $this->endDate = date('Y-m-d');
    }

    public function render(): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        $payments = $this->reportService->getSalesPaymentQueryBuilder($this->startDate, $this->endDate, $this->paymentMethodId)->get();
//        $total_purchase = $payments->sum('amount');
        $totalSales = $this->reportService->getSalesQueryBuilder($this->startDate, $this->endDate, null)
            ->addSelect(\DB::raw('SUM(quantity * price) as total_sales'))->first()
            ->total_sales;
        $paymentMethodsTotals= $this->reportService->getTotalPaymentsByMethod($this->startDate, $this->endDate,$this->paymentMethodId);

        return view('livewire.sales-payment-report', compact('payments', 'totalSales','paymentMethodsTotals'));
    }
}
