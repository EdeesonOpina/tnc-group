<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Auth;
use Mail;
use Validator;
use App\Models\User;
use App\Models\UserStatus;
use App\Models\CashAdvance;
use App\Models\CashAdvanceStatus;
use App\Models\CashAdvancePayment;
use App\Models\CashAdvancePaymentStatus;

class CashAdvanceController extends Controller
{
    public function show()
    {
        $users = User::where('role', '!=', 'Customer')
                    ->where('role', '!=', 'Agent')
                    ->where('role', '!=', 'Corporate')
                    ->where('status', '!=', UserStatus::INACTIVE)
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);

        return view('admin.reports.cash-advances.show', compact(
            'users'
        ));
    }

    public function search(Request $request)
    {
        $name = $request->name ?? '*';
        $role = $request->role ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('admin.reports.cash-advances.filter', [$name, $from_date, $to_date])->withInput();
    }

    public function filter($name, $from_date, $to_date)
    {
        $query = User::where('role', '!=', 'Customer')
                    ->where('role', '!=', 'Agent')
                    ->where('role', '!=', 'Corporate')
                    ->orderBy('created_at', 'desc');

        if ($name != '*') {
            $query->whereRaw("concat(users.firstname, ' ', users.lastname) like '%" . $name . "%' ");
            
            /* original query */
            // $query->where('users.firstname', $name);
            // $query->orWhere('users.lastname', $name);
        }

        /* date filter */
        // if they provided both from date and to date
        if ($from_date != '*' && $to_date != '*') {
            $query->whereBetween('date_borrowed', [$from_date . ' 00:00:00', $to_date . ' 23:59:59']);
        }
        /* date filter */

        $users = $query->paginate(15);

        return view('admin.reports.cash-advances.show', compact(
            'users'
        ));
    }

    public function view_filter(Request $request)
    {
        $user_id = $request->user_id ?? '*';
        $from_date = $request->customer_from_date ?? '*';
        $to_date = $request->customer_to_date ?? '*';

        return redirect()->route('admin.reports.cash-advances.view', [$user_id, $from_date, $to_date])->withInput();
    }

    public function view($user_id, $from_date, $to_date)
    {
        if ($from_date != '*') {
            $from_date = $from_date . ' 00:00:00';
        }
        
        if ($to_date != '*') {
            $to_date = $to_date . ' 23:59:59';
        }

        /* cash advances */
        $cash_advances_query = CashAdvance::where('user_id', $user_id)
                                    ->orderBy('date_borrowed', 'desc');

        /* date filter */
        // if they provided both from date and to date
        if ($from_date != '*' && $to_date != '*') {
            $cash_advances_query->whereBetween('date_borrowed', [$from_date, $to_date]);
        }
        /* date filter */

        $cash_advances = $cash_advances_query->get();

        $user = User::find($user_id); // customer

        return view('admin.reports.cash-advances.view', compact(
            'from_date',
            'to_date',
            'cash_advances',
            'user',
        ));
    }
}
