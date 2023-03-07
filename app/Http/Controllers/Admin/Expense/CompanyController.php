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

class CompanyController extends Controller
{
    public function show()
    {
        $expense_companies = ExpenseCompany::orderBy('created_at', 'desc')
                        ->paginate(15);

        $expense_categories = ExpenseCategory::where('status', ExpenseCategoryStatus::ACTIVE)
                                        ->orderBy('name', 'asc')
                                        ->get();

        return view('admin.expense_companies.show', compact(
            'expense_companies'
        ));
    }

    public function search(Request $request)
    {
        $name = $request->name ?? '*';
        $category_id = $request->category_id ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('accounting.expense-companies.filter', [$name, $category_id, $status, $from_date, $to_date])->withInput();
    }

    public function filter($name, $category_id, $status, $from_date, $to_date)
    {
        $query = ExpenseCompany::orderBy('created_at', 'desc');

        if ($name != '*') {
            $query->where('name', 'LIKE', '%' . $name . '%');
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
            $query->whereBetween('created_at', [$from_date . ' 00:00:00', $to_date . ' 23:59:59']);
        }
        /* date filter */

        $expense_companies = $query->paginate(15);
        $expense_categories = ExpenseCategory::where('status', ExpenseCategoryStatus::ACTIVE)
                                        ->orderBy('name', 'asc')
                                        ->get();

        return view('admin.expense_companies.show', compact(
            'expense_categories',
            'expense_companies'
        ));
    }

    public function add()
    {
        $expense_categories = ExpenseCategory::where('status', ExpenseCategoryStatus::ACTIVE)
                                        ->get();

        return view('admin.expense_companies.add', compact(
            'expense_categories'
        ));
    }

    public function create(Request $request)
    {
        $rules = [
            'category_id' => 'required',
            'name' => 'required',
            'image' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) 
            return back()->withInput()->withErrors($validator);

        $data = request()->all(); // get all request

        if ($request->file('image')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('image')->getClientOriginalExtension(); // set unique name for that file
            $request->file('image')->move('uploads/images/expense-companies', $image_name); // move the file to the laravel project
            $data['image'] = 'uploads/images/expense-companies/' . $image_name; // save the destination of the file to the database
        }

        $data['status'] = ExpenseCompanyStatus::ACTIVE; // if you want to insert to a specific column
        ExpenseCompany::create($data); // create data in a model

        $request->session()->flash('success', 'Data has been added');

        return redirect()->route('accounting.expense-companies');
    }

    public function view($expense_company_id)
    {
        $expense_company = ExpenseCompany::find($expense_company_id);

        return view('admin.expense_companies.view', compact(
            'expense_company'
        ));
    }

    public function edit($expense_company_id)
    {
        $expense_company = ExpenseCompany::find($expense_company_id);

        $expense_categories = ExpenseCategory::where('status', ExpenseCategoryStatus::ACTIVE)
                                        ->get();

        return view('admin.expense_companies.edit', compact(
            'expense_categories',
            'expense_company'
        ));
    }

    public function update(Request $request)
    {
        $rules = [
            'category_id' => 'required',
            'name' => 'required',
            'image' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = $request->all();

        if ($request->file('image')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('image')->getClientOriginalExtension(); // set unique name for that file
            $request->file('image')->move('uploads/images/expense-companies', $image_name); // move the file to the laravel project
            $data['image'] = 'uploads/images/expense-companies/' . $image_name; // save the destination of the file to the database
        }

        $expense_company = ExpenseCompany::find($request->expense_company_id);
        $expense_company->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');

        return redirect()->route('accounting.expense-companies');
    }

    public function recover(Request $request, $expense_company_id)
    {
        $expense = ExpenseCompany::find($expense_company_id);
        $expense->status = ExpenseCompanyStatus::ACTIVE; // mark data as active
        $expense->save();

        $request->session()->flash('success', 'Data has been recovered');

        return back();
    }

    public function delete(Request $request, $expense_company_id)
    {
        $expense = ExpenseCompany::find($expense_company_id);
        $expense->status = ExpenseCompanyStatus::INACTIVE; // mark data as inactive
        $expense->save();

        $request->session()->flash('success', 'Data has been deleted');

        return back();
    }
}
