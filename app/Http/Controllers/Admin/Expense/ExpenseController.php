<?php

namespace App\Http\Controllers\Admin\Expense;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Expense;
use App\Models\ExpenseStatus;
use App\Models\ExpenseCompany;
use App\Models\ExpenseCategory;
use App\Models\ExpenseCompanyStatus;
use App\Models\ExpenseCategoryStatus;

class ExpenseController extends Controller
{
    public function show()
    {
        $expenses = Expense::orderBy('created_at', 'desc')
                        ->paginate(15);

        $expense_categories = ExpenseCategory::where('status', ExpenseCategoryStatus::ACTIVE)
                                        ->orderBy('name', 'asc')
                                        ->get();

        return view('admin.expenses.show', compact(
            'expense_categories',
            'expenses'
        ));
    }

    public function search(Request $request)
    {
        $description = $request->description ?? '*';
        $category_id = $request->category_id ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('accounting.expenses.filter', [$description, $category_id, $status, $from_date, $to_date])->withInput();
    }

    public function filter($description, $category_id, $status, $from_date, $to_date)
    {
        $query = Expense::orderBy('date', 'desc');

        if ($description != '*') {
            $query->where('description', 'LIKE', '%' . $description . '%');
        }

        if ($category_id != '*') {
            $query->where('category_id', $category_id);
        }

        if ($status != '*') {
            $query->where('status', $status);
        }

        /* date filter */
        // if they provided both from date and to date
        if ($from_date != '*' && $to_date != '*') {
            $query->whereBetween('date', [$from_date, $to_date]);
        }
        /* date filter */

        $expenses = $query->paginate(15);
        $expense_categories = ExpenseCategory::where('status', ExpenseCategoryStatus::ACTIVE)
                                        ->orderBy('name', 'asc')
                                        ->get();

        return view('admin.expenses.show', compact(
            'expense_categories',
            'expenses'
        ));
    }

    public function add()
    {
        $expense_categories = ExpenseCategory::where('status', ExpenseCategoryStatus::ACTIVE)
                                        ->get();

        return view('admin.expenses.add', compact(
            'expense_categories'
        ));
    }

    public function create(Request $request)
    {
        $rules = [
            'category_id' => 'required',
            'date' => 'required',
            'company_id' => 'nullable',
            'price' => 'required|numeric',
            'description' => 'required',
            'note' => 'nullable',
            'image' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) 
            return back()->withInput()->withErrors($validator);

        $data = request()->all(); // get all request

        if ($request->file('image')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('image')->getClientOriginalExtension(); // set unique name for that file
            $request->file('image')->move('uploads/images/expenses', $image_name); // move the file to the laravel project
            $data['image'] = 'uploads/images/expenses/' . $image_name; // save the destination of the file to the database
        }

        $data['status'] = ExpenseStatus::ACTIVE; // if you want to insert to a specific column
        Expense::create($data); // create data in a model

        $request->session()->flash('success', 'Data has been added');

        return redirect()->route('accounting.expenses');
    }

    public function view($expense_id)
    {
        $expense = Expense::find($expense_id);

        return view('admin.expenses.view', compact(
            'expense'
        ));
    }

    public function edit($expense_id)
    {
        $expense = Expense::find($expense_id);

        $expense_categories = ExpenseCategory::where('status', ExpenseCategoryStatus::ACTIVE)
                                        ->get();

        return view('admin.expenses.edit', compact(
            'expense_categories',
            'expense'
        ));
    }

    public function update(Request $request)
    {
        $rules = [
            'category_id' => 'required',
            'date' => 'required',
            'company_id' => 'nullable',
            'price' => 'required|numeric',
            'description' => 'required',
            'note' => 'nullable',
            'image' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = $request->all();

        if ($request->file('image')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('image')->getClientOriginalExtension(); // set unique name for that file
            $request->file('image')->move('uploads/images/expenses', $image_name); // move the file to the laravel project
            $data['image'] = 'uploads/images/expenses/' . $image_name; // save the destination of the file to the database
        }

        $expense = Expense::find($request->expense_id);
        $expense->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');

        return redirect()->route('accounting.expenses');
    }

    public function recover(Request $request, $expense_id)
    {
        $expense = Expense::find($expense_id);
        $expense->status = ExpenseStatus::ACTIVE; // mark data as active
        $expense->save();

        $request->session()->flash('success', 'Data has been recovered');

        return back();
    }

    public function delete(Request $request, $expense_id)
    {
        $expense = Expense::find($expense_id);
        $expense->status = ExpenseStatus::INACTIVE; // mark data as inactive
        $expense->save();

        $request->session()->flash('success', 'Data has been deleted');

        return back();
    }
}
