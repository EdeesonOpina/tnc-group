<?php

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Validator;
use App\Models\CheckVoucher;
use App\Models\CheckVoucherStatus;
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
                                                        ->where('status', BudgetRequestFormDetailStatus::APPROVED)
                                                        ->sum('total');

        return view('admin.cv.print', compact(
            'cv',
            'budget_request_form',
            'budget_request_form_details_total',
            'budget_request_form_details'
        ));
    }
}
