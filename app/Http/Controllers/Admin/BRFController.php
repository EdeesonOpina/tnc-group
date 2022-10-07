<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Validator;
use App\Models\Project;
use App\Models\ProjectStatus;
use App\Models\BudgetRequestForm;
use App\Models\BudgetRequestFormStatus;
use App\Models\Client;
use App\Models\ClientStatus;
use App\Models\Company;
use App\Models\CompanyStatus;

class BRFController extends Controller
{
    public function show()
    {
        $budget_request_forms = BudgetRequestForm::orderBy('created_at', 'desc')
                    ->paginate(15);

        return view('admin.brf.show', compact(
            'budget_request_forms'
        ));
    }

    public function search(Request $request)
    {
        $name = $request->name ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('internals.brf.filter', [$name, $status, $from_date, $to_date])->withInput();
    }

    public function filter($name, $status, $from_date, $to_date)
    {
        $query = BudgetRequestForm::orderBy('created_at', 'desc');

        if ($name != '*') {
            $query->where('name', 'LIKE', '%' . $name . '%');
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

        $budget_request_forms = $query->paginate(15);

        return view('admin.brf.show', compact(
            'budget_request_forms'
        ));
    }

    public function add()
    {
        $clients = Client::where('status', ClientStatus::ACTIVE)
                        ->get();
        $companies = Company::where('status', CompanyStatus::ACTIVE)
                        ->get();

        return view('admin.brf.add', compact(
            'clients',
            'companies'
        ));
    }

    public function create(Request $request)
    {
        $rules = [
            'name' => 'required',
            'qty' => 'required',
            'description' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = request()->all(); // get all request

        if ($request->file('image')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('image')->getClientOriginalExtension(); // set unique name for that file
            $request->file('image')->move('uploads/images/brf', $image_name); // move the file to the laravel project
            $data['image'] = 'uploads/images/brf/' . $image_name; // save the destination of the file to the database
        }

        $data['total'] = $request->qty * $request->price;
        $data['status'] = BudgetRequestFormStatus::FOR_APPROVAL; // if you want to insert to a specific column
        BudgetRequestForm::create($data); // create data in a model

        $request->session()->flash('success', 'Data has been added');
        return redirect()->route('internals.projects.manage', [$request->project_id]);
    }

    public function view($budget_request_form_id)
    {
        $budget_request_form = BudgetRequestForm::find($budget_request_form_id);

        return view('admin.brf.view', compact(
            'budget_request_form'
        ));
    }

    public function edit($budget_request_form_id)
    {
        $budget_request_form = BudgetRequestForm::find($budget_request_form_id);
        $clients = Client::where('status', ClientStatus::ACTIVE)
                        ->get();
        $companies = Company::where('status', CompanyStatus::ACTIVE)
                        ->get();

        return view('admin.brf.edit', compact(
            'clients',
            'companies',
            'budget_request_form'
        ));
    }

    public function update(Request $request)
    {
        $rules = [
            'company_id' => 'required',
            'client_id' => 'required',
            'name' => 'required',
            'end_date' => 'required',
            'description' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = $request->all();

        if ($request->file('image')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('image')->getClientOriginalExtension(); // set unique name for that file
            $request->file('image')->move('uploads/images/brf', $image_name); // move the file to the laravel project
            $data['image'] = 'uploads/images/brf/' . $image_name; // save the destination of the file to the database
        }

        $budget_request_form = BudgetRequestForm::find($request->budget_request_form_id);
        $budget_request_form->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');

        return redirect()->route('internals.brf');
    }

    public function approve(Request $request, $budget_request_form_id)
    {
        $budget_request_form = BudgetRequestForm::find($budget_request_form_id);
        $budget_request_form->status = BudgetRequestFormStatus::APPROVED; // mark data as cancelled
        $budget_request_form->save();

        $request->session()->flash('success', 'Data has been approved');

        return back();
    }

    public function disapprove(Request $request, $budget_request_form_id)
    {
        $budget_request_form = BudgetRequestForm::find($budget_request_form_id);
        $budget_request_form->status = BudgetRequestFormStatus::DISAPPROVED; // mark data as cancelled
        $budget_request_form->save();

        $request->session()->flash('success', 'Data has been disapproved');

        return back();
    }

    public function recover(Request $request, $budget_request_form_id)
    {
        $budget_request_form = BudgetRequestForm::find($budget_request_form_id);
        $budget_request_form->status = BudgetRequestFormStatus::FOR_APPROVAL; // mark data as active
        $budget_request_form->save();

        $request->session()->flash('success', 'Data has been recovered');

        return back();
    }

    public function cancel(Request $request, $budget_request_form_id)
    {
        $budget_request_form = BudgetRequestForm::find($budget_request_form_id);
        $budget_request_form->status = BudgetRequestFormStatus::CANCELLED; // mark data as cancelled
        $budget_request_form->save();

        $request->session()->flash('success', 'Data has been cancelled');

        return back();
    }

    public function delete(Request $request, $budget_request_form_id)
    {
        $budget_request_form = BudgetRequestForm::find($budget_request_form_id);
        $budget_request_form->status = BudgetRequestFormStatus::INACTIVE; // mark data as inactive
        $budget_request_form->save();

        $request->session()->flash('success', 'Data has been deleted');

        return back();
    }
}
