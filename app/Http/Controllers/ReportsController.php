<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Exports\ExportPurchases;
use App\Exports\ExportSales;
use App\Models\PaymentMethod;
use App\Models\SaleOrder;
use App\Models\SaleOrderItem;
use App\Models\SalePayment;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Exception;
use Yajra\DataTables\Facades\DataTables;

class ReportsController extends Controller
{
    /**
     * @throws \Exception
     */
    public function salesReport()
    {
        $startDate = \request('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = \request('end_date', now()->format('Y-m-d'));
        $status = \request('status');

        return view('admin.reports.sales', [
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }

    public function printSales(ReportService $reportService)
    {
        $startDate = \request('start_date');
        $endDate = \request('end_date');
        $productId = \request('product_id');
        $status = \request('status');
        $data = $reportService->getSalesQueryBuilder($startDate, $endDate, $productId, $status)->get();
        $totalSales =$data->sum('total');
        $totalExpenses =$reportService->getExpensesQueryBuilder($startDate, $endDate)->sum('amount');
        $netProfit= $totalSales - $totalExpenses;
        $pdf = Pdf::loadView('admin.reports.print_sales', compact('data', 'startDate', 'endDate', 'status', 'totalSales', 'totalExpenses', 'netProfit'));
        $pdf->setPaper('A4', \request('orientation','landscape'));
        return $pdf->stream('sales.pdf');
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportSales()
    {
        $startDate = \request('start_date');
        $endDate = \request('end_date');
        $status = \request('status');
        $now = now()->toDateTimeLocalString();
        return Excel::download(new ExportSales($startDate, $endDate, $status), "sales_report_$now.xlsx");
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportPurchases()
    {
        $startDate = \request('start_date');
        $endDate = \request('end_date');
        $supplierId = \request('supplier_id');
        $now = now()->toDateTimeLocalString();
        return Excel::download(new ExportPurchases($startDate, $endDate, $supplierId), "purchase_report_$now.xlsx");
    }


    public function paymentsReport()
    {
        $startDate = \request('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = \request('end_date', now()->format('Y-m-d'));
        $status = \request('status');
        $paymentMethods = PaymentMethod::query()->get();
        return view('admin.reports.payments.index', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'paymentMethods' => $paymentMethods,
        ]);
    }

    public function printPayments(ReportService $reportService)
    {
        $startDate = \request('start_date');
        $endDate = \request('end_date');
        $status = \request('status');
        $paymentMethodId = \request('payment_method_id');
        $data = $reportService->getSalesPaymentQueryBuilder($startDate, $endDate, $paymentMethodId)
            ->get();
        $paymentMethod = PaymentMethod::query()->find($paymentMethodId);
        $pdf = Pdf::loadView('admin.reports.payments.print', compact('data', 'startDate', 'endDate', 'status', 'paymentMethod'));
        return $pdf->stream('payments.pdf');
    }

    public function itemsReport()
    {
      return view('admin.reports.items');
    }

    public function expensesReport()
    {
        return view('admin.reports.expenses');
    }

}
