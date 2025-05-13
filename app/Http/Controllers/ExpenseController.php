<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\Exceptions\Exception;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     * @throws Exception
     * @throws \Exception
     */
    public function index()
    {
        if (request()->ajax()) {
            $source = Expense::query()
                ->with('category');
            return datatables()->of($source)
                ->addColumn('action', function ($data) {
                    $button = '<a href="' . route('admin.expenses.show', $data->id) . '" class="btn btn-primary btn-sm btn-icon rounded-pill js-edit"><i class="bi bi-pencil"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="' . route('admin.expenses.destroy', $data->id) . '" class="btn btn-danger btn-sm btn-icon rounded-pill js-delete"><i class="bi bi-trash"></i></a>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.expenses', [
            'categories' => ExpenseCategory::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'expense_category_id' => ['required', 'exists:expense_categories,id'],
            'qty' => ['required', 'numeric'],
            'amount' => ['required', 'numeric'],
            'description' => ['required', 'max:255'],
            'date' => ['required', 'date']
        ],[
            'expense_category_id.required'=> 'The category field is required.',
            'expense_category_id.exists' => 'The selected category is invalid.',
            'amount.required' => 'The amount field is required.',
            'amount.numeric' => 'The amount must be a number.',
            'description.required' => 'The description field is required.',
            'description.max' => 'The description may not be greater than 255 characters.',
            'date.required' => 'The date field is required.',
            'date.date' => 'The date is not a valid date.',
        ]);
        $data['user_id'] = auth()->id();
        $id = $request->input('id');
        if ($id > 0) {
            $expense = Expense::find($id);
            $expense->update($data);
        } else {
            $expense = Expense::create($data);
        }
        return $expense;
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        return $expense;
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();
        return response()->json(['message' => 'Expense deleted successfully.']);
    }
}
