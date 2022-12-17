<?php

namespace App\Http\Controllers\Admin\CV;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Validator;
use App\Models\Project;
use App\Models\ProjectStatus;
use App\Models\Account;
use App\Models\AccountStatus;
use App\Models\CheckVoucher;
use App\Models\CheckVoucherStatus;
use App\Models\CheckVoucherRemark;
use App\Models\CheckVoucherRemarkStatus;
use App\Models\CheckVoucherDetail;
use App\Models\CheckVoucherDetailStatus;

class DetailController extends Controller
{
    public function create(Request $request)
    {
        $rules = [
            'reference_number' => 'required|exists:projects',
            'name' => 'required',
            'qty' => 'required',
            'price' => 'required',
            'description' => 'nullable',
            'file' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $project = Project::where('reference_number', $request->reference_number)
                        ->where('status', '!=', ProjectStatus::INACTIVE)
                        ->first();

        if ($project->status != ProjectStatus::APPROVED) {
            $request->session()->flash('error', 'Selected CE is still not approved');
            return back();
        }

        $data = request()->all(); // get all request

        if ($request->file('file')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('file')->getClientOriginalExtension(); // set unique name for that file
            $request->file('file')->move('uploads/files/cv', $image_name); // move the file to the laravel project
            $data['file'] = 'uploads/files/cv/' . $image_name; // save the destination of the file to the database
        }

        $data['project_id'] = $project->id;
        $data['price'] = str_replace(',', '', $request->price);
        $data['total'] = $request->qty * str_replace(',', '', $request->price);
        $data['status'] = CheckVoucherDetailStatus::FOR_APPROVAL; // if you want to insert to a specific column
        $cv_detail = CheckVoucherDetail::create($data); // create data in a model

        $cv = CheckVoucher::find($cv_detail->check_voucher_id);

        /* there is a bug on total so just subtract it to request total to solve the issue */
        $total = CheckVoucherDetail::where('check_voucher_id', $cv->id)
                                ->where('status', '!=', CheckVoucherDetailStatus::INACTIVE)
                                ->sum('total') - $data['total'];
        $cv->total = $total;
        $cv->save();

        $request->session()->flash('success', 'Data has been added');
        return redirect()->route('internals.cv.details.approve', [$cv_detail->id]);
    }

    public function view($cv_detail_id)
    {
        $cv_detail = CheckVoucherDetail::find($cv_detail_id);

        return view('admin.cv.view', compact(
            'check_voucher'
        ));
    }

    public function update(Request $request)
    {
        $rules = [
            'reference_number' => 'required|exists:projects',
            'name' => 'required',
            'qty' => 'required',
            'price' => 'required',
            'description' => 'nullable',
            'file' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $project = Project::where('reference_number', $request->reference_number)
                        ->where('status', '!=', ProjectStatus::INACTIVE)
                        ->first();

        if ($project->status != ProjectStatus::APPROVED) {
            $request->session()->flash('error', 'Selected CE is still not approved');
            return back();
        }

        $data = $request->all();

        if ($request->file('file')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('file')->getClientOriginalExtension(); // set unique name for that file
            $request->file('file')->move('uploads/files/cv', $image_name); // move the file to the laravel project
            $data['file'] = 'uploads/files/cv/' . $image_name; // save the destination of the file to the database
        }

        $cv_detail = CheckVoucherDetail::find($request->check_voucher_detail_id);
        $data['price'] = str_replace(',', '', $request->price);
        $data['total'] = $request->qty * str_replace(',', '', $request->price);
        $cv_detail->fill($data)->save();

        $cv = CheckVoucher::find($cv_detail->check_voucher_id);
        $cv_details_total = CheckVoucherDetail::where('check_voucher_id', $cv->id)
                                        ->where('status', '!=', CheckVoucherDetailStatus::INACTIVE)
                                        ->sum('total');
        $cv->total = $cv_details_total;
        $cv->save();

        $request->session()->flash('success', 'Data has been updated');
        return back();
    }

    public function approve(Request $request, $cv_detail_id)
    {
        $cv_detail = CheckVoucherDetail::find($cv_detail_id);
        $cv_detail->status = CheckVoucherDetailStatus::APPROVED; // mark data as cancelled
        $cv_detail->save();

        $cv = CheckVoucher::find($cv_detail->check_voucher_id);
        $cv->total += $cv_detail->total;
        $cv->save();

        $request->session()->flash('success', 'Data has been approved');
        return redirect()->route('internals.cv.custom.manage', [$cv->id]);
    }

    public function disapprove(Request $request, $cv_detail_id)
    {
        $cv_detail = CheckVoucherDetail::find($cv_detail_id);
        $cv_detail->status = CheckVoucherDetailStatus::DISAPPROVED; // mark data as cancelled
        $cv_detail->save();

        $request->session()->flash('success', 'Data has been disapproved');

        return back();
    }

    public function recover(Request $request, $cv_detail_id)
    {
        $cv_detail = CheckVoucherDetail::find($cv_detail_id);
        $cv_detail->status = CheckVoucherDetailStatus::FOR_APPROVAL; // mark data as active
        $cv_detail->save();

        $cv = CheckVoucher::find($cv_detail->check_voucher_id);
        $cv->total += $cv_detail->total;
        $cv->save();

        $request->session()->flash('success', 'Data has been recovered');

        return back();
    }

    public function cancel(Request $request, $cv_detail_id)
    {
        $cv_detail = CheckVoucherDetail::find($cv_detail_id);
        $cv_detail->status = CheckVoucherDetailStatus::CANCELLED; // mark data as cancelled
        $cv_detail->save();

        $request->session()->flash('success', 'Data has been cancelled');
        return back();
    }

    public function delete(Request $request, $cv_detail_id)
    {
        $cv_detail = CheckVoucherDetail::find($cv_detail_id);
        $cv_detail->status = CheckVoucherDetailStatus::INACTIVE; // mark data as inactive
        $cv_detail->save();

        $cv = CheckVoucher::find($cv_detail->check_voucher_id);
        $cv->total -= $cv_detail->total;
        $cv->save();

        $request->session()->flash('success', 'Data has been deleted');
        return back();
    }
}
