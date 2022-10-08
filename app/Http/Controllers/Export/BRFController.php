<?php

namespace App\Http\Controllers\Export;

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
use App\Models\BudgetRequestFormDetail;
use App\Models\BudgetRequestFormDetailStatus;
use App\Models\Client;
use App\Models\ClientStatus;
use App\Models\Company;
use App\Models\CompanyStatus;

class BRFController extends Controller
{
    public function print($budget_request_form_id)
    {
        $budget_request_form = BudgetRequestForm::find($budget_request_form_id);
        $budget_request_form_details = BudgetRequestFormDetail::where('status', '!=', BudgetRequestFormDetailStatus::INACTIVE)
                                                        ->get();
        $budget_request_form_details_total = BudgetRequestFormDetail::where('status', BudgetRequestFormDetailStatus::APPROVED)
                                                        ->sum('total');

        return view('admin.brf.print', compact(
            'budget_request_form',
            'budget_request_form_details_total',
            'budget_request_form_details'
        ));
    }
}
