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
use App\Models\PaymentStatus;
use App\Models\ItemSerialNumber;
use App\Models\POSDiscountStatus;
use App\Models\ItemSerialNumberNumber;

class PaymentController extends Controller
{
    public function show()
    {
        $start_date = Carbon::now();
        $first_day = Carbon::parse($start_date->firstOfMonth())->format('Y-m-d');
        $last_day = Carbon::parse($start_date->lastOfMonth())->format('Y-m-d');

        $period = CarbonPeriod::create($first_day, $last_day);

        return view('admin.reports.sales.show', compact(
            'period',
            'start_date',
            'first_day',
            'last_day'
        ));
    }

    public function month($month)
    {
        $month = $month;
        $start_date = Carbon::parse($month);
        $first_day = Carbon::parse($start_date->firstOfMonth())->format('Y-m-d');
        $last_day = Carbon::parse($start_date->lastOfMonth())->format('Y-m-d');

        $period = CarbonPeriod::create($first_day, $last_day);

        return view('admin.reports.sales.show', compact(
            'month',
            'period',
            'start_date',
            'first_day',
            'last_day'
        ));
    }

    public function search(Request $request)
    {
        $from_date = $request->input('from_date') ?? '*';
        $to_date = $request->input('to_date') ?? '*';
        $user_id = $request->input('user_id') ?? '*';

        return redirect()->route('admin.reports.sales.filter', [$from_date, $to_date])->withInput();
    }

    public function filter($from_date, $to_date)
    {
        $from_date = $from_date . ' 00:00:00';
        $to_date = $to_date . ' 23:59:59';

        $payments = Payment::whereBetween('created_at', [
            $from_date, $to_date
        ])
        ->where('status', '!=', PaymentStatus::INACTIVE)
        ->get();

        $vat_total = POSVat::whereBetween('created_at', [
            $from_date, $to_date
        ])->sum('price') ?? 0;

        $pos_discounts_total = POSDiscount::whereBetween('created_at', [
            $from_date, $to_date
        ])->sum('price');

        /* cancelled total */
        $pos_cancelled_total = Payment::where('is_pos_transaction', 1)
                    ->where('status', PaymentStatus::CANCELLED)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])->sum('total');

        $online_cancelled_total = Payment::where('is_pos_transaction', 0)
                    ->where('status', PaymentStatus::CANCELLED)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])->sum('total');

        /* cancelled count */
        $pos_cancelled_count = Payment::distinct('so_number')
                    ->where('is_pos_transaction', 1)
                    ->where('status', PaymentStatus::CANCELLED)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])->count();

        $online_cancelled_count = Payment::distinct('so_number')
                    ->where('is_pos_transaction', 0)
                    ->where('status', PaymentStatus::CANCELLED)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])->count();

        /* pos sales count */
        $pos_cash_count = Payment::distinct('so_number')
                    ->where('mop', 'cash')
                    ->where('is_pos_transaction', 1)
                    ->where('status', '!=', PaymentStatus::CANCELLED)
                    ->where('status', '!=', PaymentStatus::INACTIVE)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])->count();
        $pos_credit_count = Payment::distinct('so_number')
                    ->where('mop', '!=', 'cash')
                    ->where('is_pos_transaction', 1)
                    ->where('status', '!=', PaymentStatus::CANCELLED)
                    ->where('status', '!=', PaymentStatus::INACTIVE)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])->count();
        $pos_count = Payment::distinct('so_number')
                    ->where('is_pos_transaction', 1)
                    ->where('status', '!=', PaymentStatus::CANCELLED)
                    ->where('status', '!=', PaymentStatus::INACTIVE)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])->count();
        /* pos sales count */

        /* pos sales total */
        $pos_cash_total = Payment::where('mop', 'cash')
                    ->where('is_pos_transaction', 1)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])
                    ->where('status', '!=', PaymentStatus::CANCELLED)
                    ->where('status', '!=', PaymentStatus::INACTIVE)
                    ->sum('total');
        $pos_credit_total = Payment::where('mop', '!=', 'cash')
                    ->where('is_pos_transaction', 1)
                    ->where('status', '!=', PaymentStatus::CANCELLED)
                    ->where('status', '!=', PaymentStatus::INACTIVE)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])->sum('total');
        $pos_total = Payment::where('is_pos_transaction', 1)
                    ->where('status', '!=', PaymentStatus::CANCELLED)
                    ->where('status', '!=', PaymentStatus::INACTIVE)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])->sum('total');
        /* pos sales total */

        /* online sales count */
        $online_paypal_count = Payment::distinct('so_number')
                    ->where('mop', 'paypal')
                    ->where('is_pos_transaction', 0)
                    ->where('status', '!=', PaymentStatus::CANCELLED)
                    ->where('status', '!=', PaymentStatus::INACTIVE)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])->count();
        $online_bank_deposit_count = Payment::distinct('so_number')
                    ->where('mop', 'bank-deposit')
                    ->where('is_pos_transaction', 0)
                    ->where('status', '!=', PaymentStatus::CANCELLED)
                    ->where('status', '!=', PaymentStatus::INACTIVE)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])->count();
        $online_stripe_count = Payment::distinct('so_number')
                    ->where('mop', 'stripe')
                    ->where('is_pos_transaction', 0)
                    ->where('status', '!=', PaymentStatus::CANCELLED)
                    ->where('status', '!=', PaymentStatus::INACTIVE)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])->count();
        $online_cod_count = Payment::distinct('so_number')
                    ->where('mop', 'cod')
                    ->where('is_pos_transaction', 0)
                    ->where('status', '!=', PaymentStatus::CANCELLED)
                    ->where('status', '!=', PaymentStatus::INACTIVE)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])->count();
        $online_count = Payment::distinct('so_number')
                    ->where('is_pos_transaction', 0)
                    ->where('status', '!=', PaymentStatus::CANCELLED)
                    ->where('status', '!=', PaymentStatus::INACTIVE)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])->count();
        /* online sales count */

        /* online sales total */
        $online_paypal_total = Payment::where('mop', 'paypal')
                    ->where('is_pos_transaction', 0)
                    ->where('status', '!=', PaymentStatus::CANCELLED)
                    ->where('status', '!=', PaymentStatus::INACTIVE)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])->sum('total');
        $online_bank_deposit_total = Payment::where('mop', 'bank-deposit')
                    ->where('is_pos_transaction', 0)
                    ->where('status', '!=', PaymentStatus::CANCELLED)
                    ->where('status', '!=', PaymentStatus::INACTIVE)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])->sum('total');
        $online_stripe_total = Payment::where('mop', 'stripe')
                    ->where('is_pos_transaction', 0)
                    ->where('status', '!=', PaymentStatus::CANCELLED)
                    ->where('status', '!=', PaymentStatus::INACTIVE)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])->sum('total');
        $online_cod_total = Payment::where('mop', 'cod')
                    ->where('is_pos_transaction', 0)
                    ->where('status', '!=', PaymentStatus::CANCELLED)
                    ->where('status', '!=', PaymentStatus::INACTIVE)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])->sum('total');
        $online_total = Payment::where('is_pos_transaction', 0)
                    ->where('status', '!=', PaymentStatus::CANCELLED)
                    ->where('status', '!=', PaymentStatus::INACTIVE)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])->sum('total');
        /* online sales total */

        return view('admin.reports.sales.view', compact(
            'pos_cancelled_total',
            'online_cancelled_total',
            'pos_cancelled_count',
            'online_cancelled_count',
            'pos_cash_count',
            'pos_credit_count',
            'pos_credit_total',
            'pos_total',
            'pos_count',
            'online_paypal_count',
            'online_bank_deposit_count',
            'online_stripe_count',
            'online_cod_count',
            'online_count',
            'online_paypal_total',
            'online_bank_deposit_total',
            'online_stripe_total',
            'online_cod_total',
            'online_total',
            'vat_total',
            'pos_discounts_total',
            'payments',
            'from_date',
            'to_date'
        ));
    }
}
