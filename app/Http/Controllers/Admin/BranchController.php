<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Validator;
use App\Models\Branch;
use App\Models\BranchStatus;

class BranchController extends Controller
{
    public function show()
    {
        $branches = Branch::orderBy('created_at', 'desc')
                    ->paginate(15);

        return view('admin.branches.show', compact(
            'branches'
        ));
    }

    public function search(Request $request)
    {
        $name = $request->name ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('admin.branches.filter', [$name, $status, $from_date, $to_date])->withInput();
    }

    public function filter($name, $status, $from_date, $to_date)
    {
        $query = Branch::orderBy('created_at', 'desc');

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

        $branches = $query->paginate(15);

        return view('admin.branches.show', compact(
            'branches'
        ));
    }

    public function add()
    {
        return view('admin.branches.add');
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
            $request->file('image')->move('uploads/images/branches', $image_name); // move the file to the laravel project
            $data['image'] = 'uploads/images/branches/' . $image_name; // save the destination of the file to the database
        }

        $data['status'] = BranchStatus::ACTIVE; // if you want to insert to a specific column
        Branch::create($data); // create data in a model

        $request->session()->flash('success', 'Data has been added');

        return redirect()->route('admin.branches');
    }

    public function view($branch_id)
    {
        $branch = Branch::find($branch_id);

        return view('admin.branches.view', compact(
            'branch'
        ));
    }

    public function edit($branch_id)
    {
        $branch = Branch::find($branch_id);

        return view('admin.branches.edit', compact(
            'branch'
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
            $request->file('image')->move('uploads/images/branches', $image_name); // move the file to the laravel project
            $data['image'] = 'uploads/images/branches/' . $image_name; // save the destination of the file to the database
        }

        $branch = Branch::find($request->branch_id);
        $branch->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');

        return redirect()->route('admin.branches');
    }

    public function recover(Request $request, $branch_id)
    {
        $branch = Branch::find($branch_id);
        $branch->status = BranchStatus::ACTIVE; // mark data as active
        $branch->save();

        $request->session()->flash('success', 'Data has been recovered');

        return back();
    }

    public function delete(Request $request, $branch_id)
    {
        $branch = Branch::find($branch_id);
        $branch->status = BranchStatus::INACTIVE; // mark data as inactive
        $branch->save();

        $request->session()->flash('success', 'Data has been deleted');

        return back();
    }
}
