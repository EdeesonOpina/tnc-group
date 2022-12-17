<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Validator;
use App\Models\CheckVoucher;
use App\Models\CheckVoucherStatus;

class CVController extends Controller
{
    public function show()
    {
        $cvs = CheckVoucher::orderBy('created_at', 'desc')
                    ->paginate(15);

        return view('admin.cv.show', compact(
            'cvs'
        ));
    }

    public function search(Request $request)
    {
        $reference_number = $request->reference_number ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('internal.cv.filter', [$reference_number, $status, $from_date, $to_date])->withInput();
    }

    public function filter($reference_number, $status, $from_date, $to_date)
    {
        $query = CheckVoucher::orderBy('created_at', 'desc');

        if ($reference_number != '*') {
            $query->where('reference_number', $reference_number);
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

        $cvs = $query->paginate(15);

        return view('admin.cv.show', compact(
            'cvs'
        ));
    }

    public function create(Request $request)
    {
        $cv_count = str_replace('CV-', '', CheckVoucher::orderBy('created_at', 'desc')->first()->reference_number ?? 0) + 1; // get the latest cv sequence then add 1

        $data = request()->all(); // get all request
        $data['reference_number'] = 'CV-' . str_pad($cv_count, 8, '0', STR_PAD_LEFT);
        $data['budget_request_form_id'] = $request->budget_request_form_id;
        $data['status'] = CheckVoucherStatus::DONE; // if you want to insert to a specific column
        $cv = CheckVoucher::create($data); // create data in a model

        $data['check_voucher_id'] = $cv->id;
        $data['status'] = CheckVoucherRemarkStatus::ACTIVE;
        $remarks = CheckVoucherRemark::create($data);

        $request->session()->flash('success', 'Data has been added');
        return redirect()->route('internals.cv.view', $cv->reference_number);
    }

    public function view($cv_id)
    {
        $cv = CheckVoucher::find($cv_id);

        return view('admin.cv.view', compact(
            'cv'
        ));
    }
}
