<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoriesController extends Controller
{
    public function index()
    {
        $q = request('q');
        $categories = Category::query()
            ->when($q, fn($q, $category) => $category->where('name', 'like', '%' . $q . '%'))
            ->paginate(20);
        return CategoryResource::collection($categories);
    }

    public function show(Category $category)
    {
        return CategoryResource::make($category);
    }

    public function store()
    {
        $data = request()->validate([
            'name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
        ]);

        $category = Category::create($data);

        return response()
            ->json([
                'message' => 'Category successfully created',
                'data' => CategoryResource::make($category)
            ]);
    }

    public function update(Category $category)
    {
        $data = request()->validate([
            'name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
        ]);

        $category->update($data);

        return new CategoryResource($category);
    }


    public function destroy(Category $category)
    {
        $category->delete();
    }

}
