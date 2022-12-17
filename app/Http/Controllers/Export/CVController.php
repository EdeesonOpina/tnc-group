<?php

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Validator;
use App\Models\CheckVoucherDetail;
use App\Models\CheckVoucherDetailStatus;
use App\Models\CheckVoucher;
use App\Models\CheckVoucherStatus;
use App\Models\CheckVoucherRemark;
use App\Models\CheckVoucherRemarkStatus;
use App\Models\BudgetRequestForm;
use App\Models\BudgetRequestFormStatus;
use App\Models\BudgetRequestFormDetail;
use App\Models\BudgetRequestFormDetailStatus;

class CVController extends Controller
{
    public function print($reference_number)
    {
        $cv = CheckVoucher::where('reference_number', $reference_number)->first();
        $budget_request_form = BudgetRequestForm::find($cv->budget_request_form->id);
        $budget_request_form_details = BudgetRequestFormDetail::where('budget_request_form_id', $budget_request_form->id)
                                                        ->where('status', '!=', BudgetRequestFormDetailStatus::INACTIVE)
                                                        ->get();
        $budget_request_form_details_total = BudgetRequestFormDetail::where('budget_request_form_id', $budget_request_form->id)
                                                        ->where('status', '!=', BudgetRequestFormDetailStatus::INACTIVE)
                                                        ->sum('total');
        $remarks = CheckVoucherRemark::where('check_voucher_id', $cv->id)
                                ->where('status', '!=', CheckVoucherStatus::INACTIVE)
                                ->get();

        return view('admin.cv.print', compact(
            'cv',
            'budget_request_form',
            'budget_request_form_details_total',
            'budget_request_form_details',
            'remarks',
        ));
    }

    public function print_custom($reference_number)
    {
        $cv = CheckVoucher::where('reference_number', $reference_number)->first();
        $details = CheckVoucherDetail::where('check_voucher_id', $cv->id)
                                                        ->where('status', '!=', CheckVoucherDetailStatus::INACTIVE)
                                                        ->get();
        $details_total = CheckVoucherDetail::where('check_voucher_id', $cv->id)
                                                        ->where('status', '!=', CheckVoucherDetailStatus::INACTIVE)
                                                        ->sum('total');
        $remarks = CheckVoucherRemark::where('check_voucher_id', $cv->id)
                                ->where('status', '!=', CheckVoucherStatus::INACTIVE)
                                ->get();

        return view('admin.cv.custom.print', compact(
            'cv',
            'details_total',
            'details',
            'remarks',
        ));
    }
}
