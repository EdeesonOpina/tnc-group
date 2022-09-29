<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\CarbonPeriod;
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
use App\Models\PaymentCredit;
use App\Models\PaymentStatus;
use App\Models\ItemSerialNumber;
use App\Models\POSDiscountStatus;
use App\Models\PaymentCreditStatus;
use App\Models\PaymentCreditRecord;
use App\Models\ItemSerialNumberNumber;
use App\Models\PaymentCreditRecordStatus;

class PaymentCreditController extends Controller
{
    public function show()
    {
        $payment_credits = PaymentCredit::orderBy('created_at', 'desc')
                                    ->where('status', PaymentCreditStatus::OVERDUE)
                                    ->where('status', '!=', PaymentCreditStatus::INACTIVE)
                                    ->paginate(15);

        $salespersons = User::where('role', '!=', 'Customer')
                            ->where('role', '!=', 'Agent')
                            ->where('role', '!=', 'Corporate')
                            ->where('status', UserStatus::ACTIVE)
                            ->get();

        return view('admin.reports.payment-credits.show', compact(
            'payment_credits',
            'salespersons'
        ));
    }

    public function search(Request $request)
    {
        $from_date = $request->input('from_date') ?? '*';
        $to_date = $request->input('to_date') ?? '*';
        $status = $request->input('status') ?? '*';
        $salesperson_id = $request->input('salesperson_id') ?? '*';

        return redirect()->route('admin.reports.payment-credits.filter', [$salesperson_id, $status, $from_date, $to_date])->withInput();
    }

    public function filter($salesperson_id, $status, $from_date, $to_date)
    {
        $payment_credits_query = PaymentCredit::leftJoin('payment_receipts', 'payment_credits.payment_receipt_id', '=', 'payment_receipts.id')
                                        ->select('payment_credits.*')
                                        ->where('payment_credits.status', '!=', PaymentCreditStatus::INACTIVE)
                                        ->orderBy('payment_credits.created_at', 'desc');

        if ($status != '*') {
            $payment_credits_query->where('payment_credits.status', $status);
        }

        if ($salesperson_id != '*') {
            $payment_credits_query->where('payment_receipts.salesperson_id', $salesperson_id);
        }

        /* date filter */
        // if they provided both from date and to date
        if ($from_date != '*' && $to_date != '*') {
            $from_date = $from_date . ' 00:00:00';
            $to_date = $to_date . ' 23:59:59';

            $payment_credits_query->whereBetween('payment_credits.created_at', [$from_date . ' 00:00:00', $to_date . ' 23:59:59']);
        }
        /* date filter */

        $status = $status;

        $payment_credits = $payment_credits_query->get();

        return view('admin.reports.payment-credits.view', compact(
            'payment_credits',
            'status',
            'from_date',
            'to_date'
        ));
    }

    public function not_in_filter($salesperson_id, $status, $from_date, $to_date)
    {
        $payment_credits_query = PaymentCredit::leftJoin('payment_receipts', 'payment_credits.payment_receipt_id', '=', 'payment_receipts.id')
                                        ->select('payment_credits.*')
                                        ->where('payment_credits.status', '!=', PaymentCreditStatus::INACTIVE)
                                        ->orderBy('payment_credits.created_at', 'desc');

        if ($status != '*') {
            $payment_credits_query->where('payment_credits.status', '!=', $status);
        }

        if ($salesperson_id != '*') {
            $payment_credits_query->where('payment_receipts.salesperson_id', $salesperson_id);
        }

        /* date filter */
        // if they provided both from date and to date
        if ($from_date != '*' && $to_date != '*') {
            $from_date = $from_date . ' 00:00:00';
            $to_date = $to_date . ' 23:59:59';

            $payment_credits_query->whereBetween('payment_credits.created_at', [$from_date . ' 00:00:00', $to_date . ' 23:59:59']);
        }
        /* date filter */

        $status = $status;

        $payment_credits = $payment_credits_query->get();

        return view('admin.reports.payment-credits.view', compact(
            'status',
            'payment_credits',
            'from_date',
            'to_date'
        ));
    }
}
