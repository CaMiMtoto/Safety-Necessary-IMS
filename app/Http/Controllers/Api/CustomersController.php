<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\SupplierResource;
use App\Models\Customer;
use App\Models\Supplier;

class CustomersController extends Controller
{
    public function index()
    {
        $q = request('q');
        $suppliers = Customer::query()
            ->when($q, fn($q, $supplier) => $supplier->where('name', 'like', '%' . $q . '%'))
            ->paginate(20);
        return SupplierResource::collection($suppliers);
    }

    public function show(Customer $customer)
    {
        return SupplierResource::make($customer);
    }

    public function store()
    {
        $data = request()->validate([
            'name' => ['required', 'string'],
            'email' => ['nullable', 'email','unique:suppliers'],
            'phone' => ['required', 'string', 'unique:suppliers'],
            'address' => ['required', 'string'],
        ]);
        $model = Customer::create($data);

        return response()
            ->json([
                'message' => 'Customer successfully created',
                'data' => CustomerResource::make($model)
            ]);
    }

    public function update(Customer $customer)
    {
        $data = request()->validate([
            'name' => ['required', 'string'],
            'email' => ['nullable', 'email','unique:suppliers,email,'.$customer->id],
            'phone' => ['nullable', 'string', 'unique:suppliers,phone,'.$customer->id],
            'address' => ['required', 'string'],
        ]);

        $customer->update($data);

        return new SupplierResource($customer);
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
    }

}
