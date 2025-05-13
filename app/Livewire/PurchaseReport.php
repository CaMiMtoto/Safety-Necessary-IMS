<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\PurchaseOrderItem;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Application;
use Illuminate\Http\Response;
use Illuminate\Support\HigherOrderWhenProxy;
use Illuminate\View\View;
use LaravelIdea\Helper\App\Models\_IH_PurchaseOrderItem_QB;
use Livewire\Attributes\Url;
use Livewire\Component;
use TWhenReturnType;

class PurchaseReport extends Component
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

    public function render(): Application|Factory|\Illuminate\Contracts\View\View|View
    {
        $purchases = $this->reportService->getPurchaseQueryBuilder($this->startDate, $this->endDate, $this->productId)
            ->get();
        $total_purchase = $purchases->sum('total');
        return view('livewire.purchase-report', compact('purchases', 'total_purchase'));
    }


}
