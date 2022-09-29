<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CashAdvance;
use App\Models\CashAdvanceStatus;
use App\Models\CashAdvancePayment;
use App\Models\CashAdvancePaymentStatus;

class CashAdvancePaymentController extends Controller
{
    public function approve(Request $request, $cash_advance_payment_id)
    {
        $cash_advance_payment = CashAdvancePayment::find($cash_advance_payment_id);
        $cash_advance_payment->status = CashAdvancePaymentStatus::APPROVED; // mark data as inactive
        $cash_advance_payment->save();

        $cash_advance = CashAdvance::find($cash_advance_payment->cash_advance_id);
        $paid_balance = CashAdvancePayment::where('cash_advance_id', $cash_advance->id)
                                        ->where('status', CashAdvancePaymentStatus::APPROVED)
                                        ->sum('price');
        $remaining_balance = $cash_advance->price - $paid_balance;

        /* status checker */
        $cash_advance->status = CashAdvanceStatus::PARTIALLY_PAID;
        $cash_advance->save();

        /* remaining balance checker */
        if ($remaining_balance == 0) {
            $cash_advance->status = CashAdvanceStatus::FULLY_PAID;
            $cash_advance->save();
        }

        $request->session()->flash('success', 'Data has been approved');
        return back();
    }

    public function disapprove(Request $request, $cash_advance_payment_id)
    {
        $cash_advance_payment = CashAdvancePayment::find($cash_advance_payment_id);
        $cash_advance_payment->status = CashAdvancePaymentStatus::DISAPPROVED; // mark data as inactive
        $cash_advance_payment->save();

        $cash_advance = CashAdvance::find($cash_advance_payment->cash_advance_id);
        $paid_balance = CashAdvancePayment::where('cash_advance_id', $cash_advance->id)
                                        ->where('status', CashAdvancePaymentStatus::APPROVED)
                                        ->sum('price');
        $remaining_balance = $cash_advance->price - $paid_balance;

        /* status checker */
        $cash_advance->status = CashAdvanceStatus::PARTIALLY_PAID;
        $cash_advance->save();

        /* remaining balance checker */
        if ($remaining_balance == 0) {
            $cash_advance->status = CashAdvanceStatus::FULLY_PAID;
            $cash_advance->save();
        }

        $request->session()->flash('success', 'Data has been disapproved');
        return back();
    }
}
