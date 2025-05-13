<?php

namespace App\Livewire;

use App\Models\Product;
use App\Services\ReportService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\Component;

class SalesReport extends Component
{
    #[Url]
    public string $startDate;
    #[Url]
    public string $endDate;
    #[Url]
    public $productId;
    public Collection $products;
    protected ReportService $reportService;

    public function __construct()
    {
        $this->reportService = new ReportService();
        $this->products = Product::query()->latest()->get();
    }

    public function mount(): void
    {

        $this->startDate = now()->subMonth()->format('Y-m-d');
        $this->endDate = date("Y-m-d");
    }
    public function render(): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        $sales = $this->reportService->getSalesQueryBuilder($this->startDate, $this->endDate, $this->productId)
            ->get();
        $totalSales = $sales->sum('total');
        $totalExpenses= $this->reportService->getExpensesQueryBuilder($this->startDate, $this->endDate)->sum(DB::raw('amount*qty'));
        $netProfit= $totalSales - $totalExpenses;
        return view('livewire.sales-report',compact('sales', 'totalSales','totalExpenses','netProfit'));
    }
}
