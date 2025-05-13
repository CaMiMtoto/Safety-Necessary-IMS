<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductsController extends Controller
{
    public function index()
    {
        $q = request('q');
        $products = Product::query()
            ->with('category')
            ->when($q, fn($q, $product) => $product->where('name', 'like', '%' . $q . '%'))
            ->paginate(20);
        return ProductResource::collection($products);
    }

    public function show(Product $product)
    {
        return ProductResource::make($product);
    }

    public function store()
    {
        $data = request()->validate([
            'name' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'numeric'],
            'stock_quantity' => ['required', 'numeric'],
            'unit_measure' => ['required', 'string'],
            'reorder_level' => ['required', 'numeric'],
        ]);

        $product = Product::create($data);

        return response()
            ->json([
                'message' => 'Product successfully created',
                'data' => ProductResource::make($product)
            ]);
    }

    public function update(Product $product)
    {
        $data = request()->validate([
            'name' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'numeric'],
            'stock_quantity' => ['required', 'numeric'],
            'unit_measure' => ['required', 'string'],
            'reorder_level' => ['required', 'numeric'],
        ]);

        $product->update($data);

        return new ProductResource($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();
    }
}
