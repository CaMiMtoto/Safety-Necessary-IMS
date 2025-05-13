<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Yajra\DataTables\Exceptions\Exception;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     * @throws Exception
     * @throws \Exception
     */
    public function index()
    {
        if (\request()->ajax()) {
            return datatables()->of(PaymentMethod::all())
                ->addColumn('action', function ($data) {
                    $button = '<a href="' . route('admin.settings.payment-methods.show', $data->id) . '" class="btn btn-icon btn-sm btn-primary js-edit"><i class="bi bi-pencil"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a   href="' . route('admin.settings.payment-methods.destroy',$data->id) . '" class="btn btn-icon btn-sm btn-danger js-delete"><i class="bi bi-trash"></i></button>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.payment_methods');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ]);

        $id = $request->input('id');
        if ($id > 0) {
            $paymentMethod = PaymentMethod::find($id);
        } else {
            $paymentMethod = new PaymentMethod();
        }

        $paymentMethod->fill($data);
        $paymentMethod->save();

        return response()->json(['message' => 'Payment method saved successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentMethod $paymentMethod)
    {
        return response()->json($paymentMethod);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();
        return response()->json(['message' => 'Payment method deleted successfully.']);
    }
}
