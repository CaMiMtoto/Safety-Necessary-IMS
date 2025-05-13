<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\Exceptions\Exception;

class ExpenseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @throws Exception
     * @throws \Exception
     */
    public function index()
    {
        if (request()->ajax()) {
            $expenseCategories = ExpenseCategory::query()
                ->withCount('expenses');
            return datatables()->of($expenseCategories)
                ->addColumn('action', function ($expenseCategory) {
                    $btn = '<a href="' . route('admin.settings.expense-categories.show', $expenseCategory->id) . '" class="edit btn btn-primary btn-sm btn-icon mx-1 js-edit rounded-pill"><i class="bi bi-pencil"></i></a>';
                    $btn .= '<a href="' . route('admin.settings.expense-categories.destroy', $expenseCategory->id) . '" class="delete btn btn-danger btn-sm btn-icon mx-1 rounded-pill js-delete"><i class="bi bi-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make();
        }
        return view('admin.expense_categories');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required'],
            'description' => ['nullable', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $id = $request->input('id');
        if ($id > 0) {
            $expenseCategory = ExpenseCategory::query()->find($id);
            $expenseCategory->update($data);
        } else {
            $expenseCategory = ExpenseCategory::query()->create($data);
        }
        return response()->json(['message' => 'Expense Category saved successfully.', 'data' => $expenseCategory]);
    }

    /**
     * Display the specified resource.
     */
    public function show(ExpenseCategory $expenseCategory)
    {
        return $expenseCategory;
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExpenseCategory $expenseCategory)
    {
        $expenseCategory->delete();
        return response()->json(['message' => 'Expense Category deleted successfully.']);
    }
}
