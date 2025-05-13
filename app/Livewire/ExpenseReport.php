<?php

namespace App\Livewire;

use App\Models\ExpenseCategory;
use App\Services\ReportService;
use Livewire\Attributes\Url;
use Livewire\Component;

class ExpenseReport extends Component
{
    #[Url]
    public string $startDate;
    #[Url]
    public string $endDate;

    #[Url]
    public $categoryId;
    protected ReportService $reportService;

    public $categories;

    public function __construct()
    {
        $this->reportService = new ReportService();
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = date('Y-m-d');
        $this->categories = ExpenseCategory::query()->latest()->get();
    }

    public function render()
    {
        $expenses = $this->reportService->getExpensesQueryBuilder($this->startDate, $this->endDate, $this->categoryId)
            ->get();
        return view('livewire.expense-report', [
            'expenses' => $expenses,
        ]);
    }
}
