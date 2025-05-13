<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Yajra\DataTables\Exceptions\Exception;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     * @throws Exception
     * @throws \Exception
     */
    public function index()
    {

        if (request()->ajax()) {
            $data = Supplier::query();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // dropdown button
                    return '<div class="btn-group">
                            <button type="button" class="btn btn-sm btn-icon dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu">
                            <a class="dropdown-item js-edit" href="' . route('admin.settings.suppliers.show', $row->id) . '" >Edit</a>
                            <a class="dropdown-item js-delete" href="' . route('admin.settings.suppliers.destroy', $row->id) . '">Delete</a>
                            </div>
                            </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.settings.suppliers');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'id' => ['required', 'integer', 'min:0'],
            'name' => ['required', 'string'],
            'email' => ['nullable', 'email', 'string'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
        ]);


        if ($data['id'] == 0) {
            Supplier::insert($data);
        } else {
            Supplier::where('id', $data['id'])->update($data);
        }
        return response()->json(['success' => 'Supplier Added successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        return response()->json($supplier);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return response()->json(['success' => 'Supplier deleted successfully.']);
    }
}
