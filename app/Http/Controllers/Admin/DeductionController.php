<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Validator;
use App\Models\Deduction;
use App\Models\DeductionStatus;

class DeductionController extends Controller
{
    public function show()
    {
        $deductions = Deduction::orderBy('created_at', 'desc')
                    ->paginate(15);

        return view('admin.deductions.show', compact(
            'deductions'
        ));
    }

    public function search(Request $request)
    {
        $type = $request->type ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('admin.deductions.filter', [$type, $status, $from_date, $to_date])->withInput();
    }

    public function filter($type, $status, $from_date, $to_date)
    {
        $query = Deduction::orderBy('created_at', 'desc');

        if ($type != '*') {
            $query->where('type', 'LIKE', '%' . $type . '%');
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

        $deductions = $query->paginate(15);

        return view('admin.deductions.show', compact(
            'deductions'
        ));
    }

    public function add()
    {
        return view('admin.deductions.add');
    }

    public function create(Request $request)
    {
        $rules = [
            'type' => 'required',
            'value' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = request()->all(); // get all request
        $data['status'] = DeductionStatus::ACTIVE; // if you want to insert to a specific column
        Deduction::create($data); // create data in a model

        $request->session()->flash('success', 'Data has been added');

        return redirect()->route('admin.deductions');
    }

    public function view($deduction_id)
    {
        $deduction = Deduction::find($deduction_id);

        return view('admin.deductions.view', compact(
            'deduction'
        ));
    }

    public function edit($deduction_id)
    {
        $deduction = Deduction::find($deduction_id);

        return view('admin.deductions.edit', compact(
            'deduction'
        ));
    }

    public function update(Request $request)
    {
        $rules = [
            'type' => 'required',
            'value' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = $request->all();

        $deduction = Deduction::find($request->deduction_id);
        $deduction->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');

        return redirect()->route('admin.deductions');
    }

    public function recover(Request $request, $deduction_id)
    {
        $deduction = Deduction::find($deduction_id);
        $deduction->status = DeductionStatus::ACTIVE; // mark data as active
        $deduction->save();

        $request->session()->flash('success', 'Data has been recovered');

        return back();
    }

    public function delete(Request $request, $deduction_id)
    {
        $deduction = Deduction::find($deduction_id);
        $deduction->status = DeductionStatus::INACTIVE; // mark data as inactive
        $deduction->save();

        $request->session()->flash('success', 'Data has been deleted');

        return back();
    }
}
