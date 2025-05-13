<?php

namespace App\Livewire;

use App\Constants\Status;
use App\Models\Product;
use App\Models\PurchaseOrderItem;
use App\Models\SaleDeliveryItem;
use App\Models\SaleOrderItem;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;

class ItemsReport extends Component
{
    #[Url]
    public string $category = "summary";
    #[Url]
    public string $startDate;
    #[Url]
    public string $endDate;
    #[Url]
    public string $productName = "";

    #[Url]
    public ?int $productId;

    public $products;

    public function __construct()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = date('Y-m-d');
        $this->products = Product::query()->get();
    }

    public function render(): \Illuminate\Contracts\View\View|Application|View
    {

        return view('livewire.items-report');
    }
}
