<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Validator;
use App\Models\Payable;
use App\Models\PayableStatus;

class PayableController extends Controller
{
    public function show()
    {
        $payables = Payable::orderBy('created_at', 'desc')
                                    ->paginate(15);

        return view('admin.payables.show', compact(
            'payables'
        ));
    }

    public function search(Request $request)
    {
        $reference_number = $request->reference_number ?? '*';
        $name = $request->name ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('accounting.payables.filter', [$reference_number, $name, $status, $from_date, $to_date])->withInput();
    }

    public function filter($reference_number, $name, $status, $from_date, $to_date)
    {
        $query = Payable::leftJoin('purchase_orders', 'payables.purchase_order_id', '=', 'purchase_orders.id')
                    ->leftJoin('suppliers', 'purchase_orders.supplier_id', '=', 'suppliers.id')
                    ->select('payables.*')
                    ->orderBy('payables.created_at', 'desc');

        if ($reference_number != '*') {
            $query->where('purchase_orders.reference_number', $reference_number);
        }

        if ($name != '*') {
            $query->where('suppliers.name', 'LIKE', '%' . $name . '%');
        }

        if ($status != '*') {
            $query->where('payables.status', $status);
        }

        /* date filter */
        // if they provided both from date and to date
        if ($from_date != '*' && $to_date != '*') {
            $query->whereBetween('payables.date_issued', [$from_date . ' 00:00:00', $to_date . ' 23:59:59']);
        }
        /* date filter */

        $payables = $query->paginate(15);

        return view('admin.payables.show', compact(
            'payables'
        ));
    }

    public function create(Request $request)
    {
        $rules = [
            'mop' => 'required',
            'date_issued' => 'required',
            'date_released' => 'nullable',
            'due_date' => 'required',
            'check_number' => 'required|unique:payables',
            'price' => 'required',
            'image' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = request()->all(); // get all request

        if ($request->file('image')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('image')->getClientOriginalExtension(); // set unique name for that file
            $request->file('image')->move('uploads/images/payables', $image_name); // move the file to the laravel project
            $data['image'] = 'uploads/images/payables/' . $image_name; // save the destination of the file to the database
        }

        $data['status'] = PayableStatus::PENDING; // if you want to insert to a specific column
        Payable::create($data); // create data in a model

        $request->session()->flash('success', 'Data has been added');
        return redirect()->route('accounting.payables');
    }

    public function cancel(Request $request, $payable_id)
    {
        $payable = Payable::find($payable_id);
        $payable->status = PayableStatus::INACTIVE; // mark data as inactive
        $payable->save();

        $request->session()->flash('success', 'Data has been recovered.');
        return back();
    }

    public function database_update(Request $request)
    {
        /* payable overdue checker */
        $payables = Payable::all();
        foreach ($payables as $payable) {
            /* overdue checker */
            if (Carbon::now() < ($payable->due_date)) {
                $payable = Payable::find($payable->id);
                $payable->status = PayableStatus::PENDING;
                $payable->save();
            } else {
                /* tag as overdue */
                $payable = Payable::find($payable->id);
                $payable->status = PayableStatus::PAID;
                $payable->save();
            }
        }

        $request->session()->flash('success', 'Data has been updated');
        return back();
    }

    public function date_released(Request $request)
    {
        $payable = Payable::find($request->payable_id);
        $payable->date_released = $request->date_released;
        $payable->save();

        $request->session()->flash('success', 'Data has been updated.');
        return back();
    }

    public function recover(Request $request, $payable_id)
    {
        $payable = Payable::find($payable_id);
        $payable->status = PayableStatus::PENDING; // mark data as inactive
        $payable->save();

        $request->session()->flash('success', 'Data has been recovered.');
        return back();
    }

    public function delete(Request $request, $payable_id)
    {
        $payable = Payable::find($payable_id);
        $payable->status = PayableStatus::INACTIVE; // mark data as inactive
        $payable->save();

        $request->session()->flash('success', 'Data has been deleted');
        return back();
    }
}
