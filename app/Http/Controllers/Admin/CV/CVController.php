<?php

namespace App\Http\Controllers\Admin\CV;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Validator;
use App\Models\Account;
use App\Models\AccountStatus;
use App\Models\CheckVoucher;
use App\Models\CheckVoucherStatus;
use App\Models\CheckVoucherRemark;
use App\Models\CheckVoucherRemarkStatus;
use App\Models\CheckVoucherDetail;
use App\Models\CheckVoucherDetailStatus;

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

    public function add_custom()
    {
        $accounts = Account::where('status', '!=', AccountStatus::INACTIVE)
                  ->get();

        return view('admin.cv.custom.add', compact(
            'accounts'
        ));
    }

    public function search(Request $request)
    {
        $reference_number = $request->reference_number ?? '*';
        $name = $request->name ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('internals.cv.filter', [$reference_number, $name, $status, $from_date, $to_date])->withInput();
    }

    public function filter($reference_number, $name, $status, $from_date, $to_date)
    {
        $query = CheckVoucher::leftJoin('budget_request_forms', 'check_vouchers.budget_request_form_id', '=', 'budget_request_forms.id')
                    ->select('check_vouchers.*')
                    ->orderBy('check_vouchers.created_at', 'desc');

        if ($name != '*') {
            $query->where('budget_request_forms.name', $name);
        }

        if ($reference_number != '*') {
            $query->where('check_vouchers.reference_number', $reference_number);
        }

        if ($status != '*') {
            $query->where('check_vouchers.status', $status);
        }

        /* date filter */
        // if they provided both from date and to date
        if ($from_date != '*' && $to_date != '*') {
            $query->whereBetween('check_vouchers.created_at', [$from_date . ' 00:00:00', $to_date . ' 23:59:59']);
        }
        /* date filter */

        $cvs = $query->paginate(15);

        return view('admin.cv.show', compact(
            'cvs'
        ));
    }

    public function create_custom(Request $request)
    {
        $cv_count = str_replace('CV-', '', CheckVoucher::orderBy('created_at', 'desc')->first()->reference_number ?? 0) + 1; // get the latest cv sequence then add 1

        $data = request()->all(); // get all request
        $data['reference_number'] = 'CV-' . str_pad($cv_count, 8, '0', STR_PAD_LEFT);
        $data['budget_request_form_id'] = 0;
        $data['is_custom'] = 1;
        $data['total'] = str_replace(',', '', $request->amount);
        $data['prepared_by_user_id'] = auth()->user()->id;
        $data['status'] = CheckVoucherStatus::ON_PROCESS; // if you want to insert to a specific column
        $cv = CheckVoucher::create($data); // create data in a model

        $data['check_voucher_id'] = $cv->id;
        $data['amount'] = str_replace(',', '', $request->amount);
        $data['status'] = CheckVoucherRemarkStatus::ACTIVE;
        $remarks = CheckVoucherRemark::create($data);

        $request->session()->flash('success', 'Data has been added');
        return redirect()->route('internals.cv.custom.manage', $cv->id);
    }

    public function create(Request $request)
    {
        $cv_count = str_replace('CV-', '', CheckVoucher::orderBy('created_at', 'desc')->first()->reference_number ?? 0) + 1; // get the latest cv sequence then add 1

        $data = request()->all(); // get all request
        $data['reference_number'] = 'CV-' . str_pad($cv_count, 8, '0', STR_PAD_LEFT);
        $data['budget_request_form_id'] = $request->budget_request_form_id;
        $data['prepared_by_user_id'] = auth()->user()->id;
        $data['total'] = str_replace(',', '', $request->amount);
        $data['status'] = CheckVoucherStatus::DONE; // if you want to insert to a specific column
        $cv = CheckVoucher::create($data); // create data in a model

        $data['check_voucher_id'] = $cv->id;
        $data['amount'] = str_replace(',', '', $request->amount);
        $data['status'] = CheckVoucherRemarkStatus::ACTIVE;
        $remarks = CheckVoucherRemark::create($data);

        $request->session()->flash('success', 'Data has been added');
        return redirect()->route('internals.exports.cv.print', $cv->reference_number);
    }

    public function view($cv_id)
    {
        $cv = CheckVoucher::find($cv_id);

        return view('admin.cv.view', compact(
            'cv'
        ));
    }

    public function manage($cv_id)
    {
        $cv = CheckVoucher::find($cv_id);
        $details = CheckVoucherDetail::where('check_voucher_id', $cv_id)
                                                        ->where('status', '!=', CheckVoucherDetailStatus::INACTIVE)
                                                        ->get();
        $details_total = CheckVoucherDetail::where('check_voucher_id', $cv_id)
                                                        ->where('status', '!=', CheckVoucherDetailStatus::INACTIVE)
                                                        ->sum('total');

        return view('admin.cv.custom.manage', compact(
            'cv',
            'details_total',
            'details'
        ));
    }

    public function done(Request $request, $cv_id)
    {
        $cv = CheckVoucher::find($cv_id);
        $cv->status = CheckVoucherStatus::DONE; // mark data as done
        $cv->save();

        $request->session()->flash('success', 'Data has been marked as done');
        return redirect()->route('internals.exports.cv.print.custom', $cv->reference_number);
    }

    public function open_for_editing(Request $request, $cv_id)
    {
        $cv = CheckVoucher::find($cv_id);
        $cv->status = CheckVoucherStatus::OPEN_FOR_EDITING; // mark data as open for editing
        $cv->save();

        $request->session()->flash('success', 'Data has been marked as open for editing');
        return back();
    }
}
