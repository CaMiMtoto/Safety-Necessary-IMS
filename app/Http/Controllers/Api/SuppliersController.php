<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SupplierResource;
use App\Models\Supplier;

class SuppliersController extends Controller
{
    public function index()
    {
        $q = request('q');
        $suppliers = Supplier::query()
            ->when($q, fn($q, $supplier) => $supplier->where('name', 'like', '%' . $q . '%'))
            ->paginate(20);
        return SupplierResource::collection($suppliers);
    }

    public function show(Supplier $supplier)
    {
        return SupplierResource::make($supplier);
    }

    public function store()
    {
        $data = request()->validate([
            'name' => ['required', 'string'],
            'email' => ['nullable', 'email','unique:suppliers'],
            'phone' => ['required', 'string', 'unique:suppliers'],
            'address' => ['required', 'string'],
        ]);

        $supplier = Supplier::create($data);

        return response()
            ->json([
                'message' => 'Supplier successfully created',
                'data' => SupplierResource::make($supplier)
            ]);
    }

    public function update(Supplier $supplier)
    {
        $data = request()->validate([
            'name' => ['required', 'string'],
            'email' => ['nullable', 'email','unique:suppliers,email,'.$supplier->id],
            'phone' => ['nullable', 'string', 'unique:suppliers,phone,'.$supplier->id],
            'address' => ['required', 'string'],
        ]);

        $supplier->update($data);

        return new SupplierResource($supplier);
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
    }

}
