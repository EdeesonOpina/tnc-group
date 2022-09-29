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
use App\Models\Branch;
use App\Models\POSVat;
use App\Models\Payment;
use App\Models\UserStatus;
use App\Models\POSDiscount;
use App\Models\POSVatStatus;
use App\Models\BranchStatus;
use App\Models\PaymentStatus;
use App\Models\PaymentReceipt;
use App\Models\ItemSerialNumber;
use App\Models\POSDiscountStatus;
use App\Models\PaymentReceiptStatus;
use App\Models\ItemSerialNumberNumber;

class CustomerController extends Controller
{
    public function show()
    {
        $users = User::where('status', '!=', UserStatus::INACTIVE)
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);

        return view('admin.reports.customers.show', compact(
            'users'
        ));
    }

    public function search(Request $request)
    {
        $name = $request->name ?? '*';
        $mop = $request->mop ?? '*';
        $role = $request->role ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('admin.reports.customers.filter', [$name, $mop, $from_date, $to_date])->withInput();
    }

    public function filter($name, $mop, $from_date, $to_date)
    {
        $query = User::orderBy('created_at', 'desc');

        if ($name != '*') {
            $query->whereRaw("concat(users.firstname, ' ', users.lastname) like '%" . $name . "%' ");
            
            /* original query */
            // $query->where('users.firstname', $name);
            // $query->orWhere('users.lastname', $name);
        }

        /* date filter */
        // if they provided both from date and to date
        if ($from_date != '*' && $to_date != '*') {
            $query->whereBetween('created_at', [$from_date . ' 00:00:00', $to_date . ' 23:59:59']);
        }
        /* date filter */

        $users = $query->paginate(15);

        return view('admin.reports.customers.show', compact(
            'users'
        ));
    }

    public function view_filter(Request $request)
    {
        $customer_id = $request->user_id ?? '*';
        $mop = $request->mop ?? '*';
        $from_date = $request->customer_from_date ?? '*';
        $to_date = $request->customer_to_date ?? '*';

        return redirect()->route('admin.reports.customers.view', [$customer_id, $mop, $from_date, $to_date])->withInput();
    }

    public function view($customer_id, $mop, $from_date, $to_date)
    {
        if ($from_date != '*') {
            $from_date = $from_date . ' 00:00:00';
        }
        
        if ($to_date != '*') {
            $to_date = $to_date . ' 23:59:59';
        }

        /* payments */
        $payments_query = Payment::where('user_id', $customer_id)
                    ->orderBy('created_at', 'desc');

        /* payment receipts */
        $pr_query = PaymentReceipt::where('user_id', $customer_id)
                        ->orderBy('created_at', 'desc');

        /* payment receipts w/o cancelled */
        $no_cancelled_pr_query = PaymentReceipt::where('user_id', $customer_id)
                        ->where('status', '!=', PaymentReceiptStatus::CANCELLED)
                        ->where('status', '!=', PaymentReceiptStatus::INACTIVE)
                        ->orderBy('created_at', 'desc');

        /* payments with cancelled */
        $cancelled_payments_query = Payment::where('user_id', $customer_id)
                    ->where('status', PaymentStatus::CANCELLED)
                    // ->where('status', '!=', PaymentStatus::INACTIVE)
                    ->orderBy('created_at', 'desc');

        /* payments without cancelled */
        $no_cancelled_payments_query = Payment::where('user_id', $customer_id)
                    ->where('status', '!=', PaymentStatus::CANCELLED)
                    ->where('status', '!=', PaymentStatus::INACTIVE)
                    ->orderBy('created_at', 'desc');

        /* mop filter */
        if ($mop != '*') {
            $payments_query->where('mop', $mop);
            $pr_query->where('mop', $mop);
            $cancelled_payments_query->where('mop', $mop);
        }
        /* date filter */

        /* date filter */
        // if they provided both from date and to date
        if ($from_date != '*' && $to_date != '*') {
            $payments_query->whereBetween('created_at', [$from_date, $to_date]);
            $pr_query->whereBetween('created_at', [$from_date, $to_date]);
            $cancelled_payments_query->whereBetween('created_at', [$from_date, $to_date]);
        }
        /* date filter */

        // $payments = $payments_query->get();
        $payment_receipts = $pr_query->get();

        
        $grand_vat_total = $pr_query->sum('vat');
        $grand_discount_total = $pr_query->sum('discount');
        $grand_total_amount = $payments_query->sum('total');
        $grand_cancelled_total_amount = $cancelled_payments_query->sum('total');
        $grand_total = ((($grand_total_amount - $grand_cancelled_total_amount)) + $pr_query->sum('vat')) - $pr_query->sum('discount');

        $user = User::find($customer_id); // customer

        return view('admin.reports.customers.view', compact(
            'grand_cancelled_total_amount',
            'grand_total_amount',
            'grand_discount_total',
            'grand_vat_total',
            'grand_total',
            'user',
            'from_date',
            'to_date',
            'payment_receipts'
        ));
    }
}
