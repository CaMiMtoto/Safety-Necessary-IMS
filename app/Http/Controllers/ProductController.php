<?php

namespace App\Http\Controllers;

use App\Exports\ProductsExport;
use App\Models\Category;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     * @throws Exception
     */
    public function index()
    {
        if (request()->ajax()) {
            $products = Product::query()
                ->with(['category']);
            return DataTables::of($products)
                ->addColumn('action', function ($product) {
                    // dropdown
                    return '<div class="dropdown">
                                <button class="btn btn-light btn-icon btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item js-edit" href="' . route('admin.products.show', $product->id) . '" >Edit</a>
                                    <a class="dropdown-item js-delete" href="' . route('admin.products.destroy', $product->id) . '">Delete</a>
                                </div>
                            </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $categories = Category::all();
        return view('admin.products.index', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'id' => ['required'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'category_id' => ['required', 'integer'],
            'price' => ['required', 'numeric'],
            'sku' => ['nullable', 'string', 'max:255'],
            'unit_measure' => ['required', 'string', 'max:255'],
            'stock_unit_measure' => ['required', 'string', 'max:255'],
            'reorder_level' => ['required', 'integer'],
            'sold_in_square_meters' => ['nullable'],
            'box_coverage' => ['nullable', 'numeric', 'min:0', 'required_if:sold_in_square_meters,on'],
        ]);

        $data['sold_in_square_meters'] = isset($data['sold_in_square_meters']) && $data['sold_in_square_meters'] == 'on';

        if ($data['id'] == 0) {
            $data['stock_quantity'] = 0;
            Product::create($data);
        } else {
            $product = Product::find($data['id']);
            $product->update($data);
        }

        return response()->json(['success' => 'Product saved successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return response()->json($product);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['success' => 'Product deleted successfully.']);
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportExcel()
    {
      return Excel::download(new ProductsExport, 'products.xlsx');
    }
}
