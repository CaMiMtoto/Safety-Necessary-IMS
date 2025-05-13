<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
{
    /**
     * @throws \Exception
     */
    public function index()
    {
        if (request()->ajax()) {
            $categories = Category::query()
                ->withCount('products');
            return DataTables::of($categories)
                ->addColumn('action', function ($category) {
                    // dropdown
                    return '<div class="dropdown">
                                <button class="btn btn-secondary btn-sm btn-icon dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item js-edit" href="' . route('admin.products.categories.show', $category->id) . '" >Edit</a>
                                    <a class="dropdown-item js-delete" href="' . route('admin.products.categories.destroy', $category->id) . '">Delete</a>
                                </div>
                            </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.products.categories');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id' => ['required'],
            'name' => ['required', 'string', 'max:255','unique:categories,name,'.$request->id],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $data['slug'] = Str::slug($data['name']);

        if ($data['id'] == 0) {
            Category::create($data);
        } else {
            $category = Category::find($data['id']);
            $category->update($data);
        }

        return response()->json(['success' => 'Category saved successfully.']);

    }

    public function show(Category $category)
    {
        return response()->json($category);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(['success' => 'Category deleted successfully.']);
    }

}
