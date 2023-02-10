<?php

namespace App\Http\Controllers\Admin;

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
use App\Models\Liquidation;
use App\Models\LiquidationStatus;
use App\Models\Client;
use App\Models\ClientStatus;
use App\Models\Company;
use App\Models\CompanyStatus;
use App\Models\User;
use App\Models\UserStatus;
use App\Models\Supplier;
use App\Models\SupplierStatus;

class LiquidationController extends Controller
{
    public function show()
    {
        $budget_request_forms = BudgetRequestForm::orderBy('created_at', 'desc')
                    ->where('status', BudgetRequestFormStatus::FOR_LIQUIDATION)
                    ->where('status', '!=', BudgetRequestFormStatus::INACTIVE)
                    ->paginate(15);

        return view('admin.liquidations.show', compact(
            'budget_request_forms'
        ));
    }

    public function search(Request $request)
    {
        $reference_number = $request->reference_number ?? '*';
        $name = $request->name ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('accounting.liquidations.filter', [$reference_number, $name, $status, $from_date, $to_date])->withInput();
    }

    public function filter($reference_number, $name, $status, $from_date, $to_date)
    {
        $query = BudgetRequestForm::where('status', '!=', BudgetRequestFormStatus::INACTIVE)
                    ->orderBy('created_at', 'desc');

        if ($reference_number != '*') {
            $query->where('reference_number', $reference_number);
        }

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

        $budget_request_forms = $query->paginate(15);

        return view('admin.liquidations.show', compact(
            'budget_request_forms'
        ));
    }
}
