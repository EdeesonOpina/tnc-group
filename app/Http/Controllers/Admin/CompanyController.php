<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Validator;
use App\Models\Company;
use App\Models\CompanyStatus;

class CompanyController extends Controller
{
    public function show()
    {
        $companies = Company::orderBy('created_at', 'desc')
                    ->paginate(15);

        return view('admin.companies.show', compact(
            'companies'
        ));
    }

    public function search(Request $request)
    {
        $name = $request->name ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('admin.companies.filter', [$name, $status, $from_date, $to_date])->withInput();
    }

    public function filter($name, $status, $from_date, $to_date)
    {
        $query = Company::orderBy('created_at', 'desc');

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

        $companies = $query->paginate(15);

        return view('admin.companies.show', compact(
            'companies'
        ));
    }

    public function add()
    {
        return view('admin.companies.add');
    }

    public function create(Request $request)
    {
        $rules = [
            'name' => 'required',
            'person' => 'required',
            'mobile' => 'required',
            'email' => 'required',
            'line_address_1' => 'required',
            'line_address_2' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = request()->all(); // get all request

        if ($request->file('image')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('image')->getClientOriginalExtension(); // set unique name for that file
            $request->file('image')->move('uploads/images/companies', $image_name); // move the file to the laravel project
            $data['image'] = 'uploads/images/companies/' . $image_name; // save the destination of the file to the database
        }

        $data['status'] = CompanyStatus::ACTIVE; // if you want to insert to a specific column
        Company::create($data); // create data in a model

        $request->session()->flash('success', 'Data has been added');

        return redirect()->route('admin.companies');
    }

    public function view($company_id)
    {
        $company = Company::find($company_id);

        return view('admin.companies.view', compact(
            'company'
        ));
    }

    public function edit($company_id)
    {
        $company = Company::find($company_id);

        return view('admin.companies.edit', compact(
            'company'
        ));
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => 'required',
            'mobile' => 'required',
            'email' => 'required',
            'line_address_1' => 'required',
            'line_address_2' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = $request->all();

        if ($request->file('image')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('image')->getClientOriginalExtension(); // set unique name for that file
            $request->file('image')->move('uploads/images/companies', $image_name); // move the file to the laravel project
            $data['image'] = 'uploads/images/companies/' . $image_name; // save the destination of the file to the database
        }

        $company = Company::find($request->company_id);
        $company->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');

        return redirect()->route('admin.companies');
    }

    public function recover(Request $request, $company_id)
    {
        $company = Company::find($company_id);
        $company->status = CompanyStatus::ACTIVE; // mark data as active
        $company->save();

        $request->session()->flash('success', 'Data has been recovered');

        return back();
    }

    public function delete(Request $request, $company_id)
    {
        $company = Company::find($company_id);
        $company->status = CompanyStatus::INACTIVE; // mark data as inactive
        $company->save();

        $request->session()->flash('success', 'Data has been deleted');

        return back();
    }
}
