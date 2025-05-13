<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Models\SaleOrder;
use App\Models\SalePayment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Exceptions\Exception;

class SalePaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @throws Exception
     * @throws \Exception
     */
    public function index()
    {
        if (request()->ajax()) {
            $source = SalePayment::query()
                ->with('saleOrder.customer');
            return datatables()->of($source)
                ->addIndexColumn()
                ->addColumn('action', fn(SalePayment $payment) => view('admin.sales_payments._action', compact('payment')))
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.sales_payments.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.sales_payments.create');
    }

    /**
     * Store a newly created resource in storage.
     * @throws \Throwable
     */
    public function store(Request $request, SaleOrder $saleOrder)
    {
//        info("sale order: " . $saleOrder);
        $amountToPay = $this->getTotalSalesAmount($saleOrder);
//        info("amount to pay: " . $amountToPay);
        $totalPaid = $this->getTotalPaidAmount($saleOrder);
//        info("total paid: " . $totalPaid);
        $remaining = $amountToPay - $totalPaid;
//        info("remaining: " . $remaining);
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:1', 'max:' . $remaining],
            'payment_date' => ['required', 'date', 'date_format:Y-m-d', 'before_or_equal:today'],
            'payment_method_id' => ['required', 'numeric', 'exists:payment_methods,id'],
            'reference' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
            'currency' => ['nullable', 'string'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ]);

        $data['customer_id'] = $saleOrder->customer_id;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store(SalePayment::ATTACHMENT_PATH);
            $data['attachment'] = basename($path);
        }
        DB::beginTransaction();
        $data['user_id'] = auth()->id();
        $saleOrder->payments()->create($data);
        // update sale order payment status
        $this->updateSaleOrderPaymentStatus($saleOrder);
        DB::commit();
        if ($request->ajax()) {
            session()->flash('success', 'Sale payment created successfully');
            return response()->json([
                'success' => true,
                'message' => 'Sale payment created',
                'data' => $saleOrder,
                'redirect_url' => route('admin.sales_payment.index')
            ]);
        }
        return redirect()->route('admin.sales_payment.index')
            ->with('success', 'Sale payment created');
    }

    /**
     * Display the specified resource.
     */
    public function show(SalePayment $salePayment)
    {
        $salePayment->load('saleOrder.customer');
        return view('admin.sales_payments.show', compact('salePayment'));
    }


    /**
     * Remove the specified resource from storage.
     * @throws \Throwable
     */
    public function destroy(SalePayment $salePayment)
    {
        DB::beginTransaction();
        $salePayment->load('saleOrder.customer');
        $saleOrder = $salePayment->saleOrder;
        // update sale order payment status
        $amountToPay = $this->getTotalSalesAmount($saleOrder);
        $totalPaid = $this->getTotalPaidAmount($saleOrder);
        $this->updateSaleOrderPaymentStatus($saleOrder);
        $salePayment->delete();

        DB::commit();
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Sale payment deleted',
                'redirect_url' => route('admin.sales_payment.index')
            ]);
        }
        return redirect()->route('admin.sales_payment.index')
            ->with('success', 'Sale payment deleted');
    }

    /**
     * @throws \Throwable
     */
    public function cancel(SalePayment $salePayment)
    {
        $salePayment->load('saleOrder.customer');
        DB::beginTransaction();
        $salePayment->status = Status::CANCELLED;
        $salePayment->save();
        // update sale order payment status
        $saleOrder = $salePayment->saleOrder;
        $this->updateSaleOrderPaymentStatus($saleOrder);
        DB::commit();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Sale payment cancelled',
                'data' => $salePayment,
                'redirect_url' => route('admin.sales_payment.index')
            ]);
        }
        return redirect()->route('admin.sales_payment.index')
            ->with('success', 'Sale payment cancelled');
    }

    public function print(SalePayment $salePayment)
    {
        $salePayment->load('saleOrder.customer');
        $pdf = PDF::loadView('admin.sales_payments.print', compact('salePayment'));
        return $pdf->stream('payment_' . $salePayment->id . '.pdf');
    }

    public function updateSaleOrderPaymentStatus(SaleOrder $saleOrder): void
    {
        // Ensure $remaining and $totalPaid are numeric
        $amountToPay = $this->getTotalSalesAmount($saleOrder);
        $totalPaid = $this->getTotalPaidAmount($saleOrder);
        $remaining = $amountToPay - $totalPaid;
        // Determine payment status based on $remaining and $totalPaid
        if ($totalPaid == 0) {
            $saleOrder->payment_status = Status::UNPAID;
        } elseif ($remaining <= 0) {
            $saleOrder->payment_status = Status::PAID;
        } elseif ($remaining > 0 && $totalPaid > 0) {
            $saleOrder->payment_status = Status::PARTIALLY_PAID;
        }

        $saleOrder->save();
    }

    /**
     * Get the total paid amount for the given SaleOrder.
     *
     * @param SaleOrder $saleOrder
     * @return float
     */
    public function getTotalPaidAmount(SaleOrder $saleOrder): float
    {
        return (float)$saleOrder->payments()
            ->whereRaw('LOWER(status) = ?', ['paid']) // Cleaner query using whereRaw
            ->sum('amount');
    }


    /**
     * @param mixed $saleOrder
     * @return mixed
     */
    public function getTotalSalesAmount(SaleOrder $saleOrder): mixed
    {
        return $saleOrder->items()->sum(DB::raw('price * quantity'));
    }


}
