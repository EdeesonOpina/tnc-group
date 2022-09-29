<?php

namespace App\Http\Controllers\Admin;

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
use App\Models\Inventory;
use App\Models\UserStatus;
use App\Models\POSDiscount;
use App\Models\POSVatStatus;
use App\Models\BranchStatus;
use App\Models\PaymentCredit;
use App\Models\PaymentStatus;
use App\Models\InventoryStatus;
use App\Models\ItemSerialNumber;
use App\Models\POSDiscountStatus;
use App\Models\PaymentCreditRecord;
use App\Models\PaymentCreditStatus;
use App\Models\ItemSerialNumberStatus;
use App\Models\PaymentCreditRecordStatus;

class PaymentCreditRecordController extends Controller
{
    public function approve(Request $request, $payment_credit_record_id)
    {
        $payment_credit_record = PaymentCreditRecord::find($payment_credit_record_id);
        $payment_credit = PaymentCredit::find($payment_credit_record->payment_credit->id);

        $payments_total = Payment::where('so_number', $payment_credit_record->so_number)
                                ->where('status', '!=', PaymentStatus::INACTIVE)
                                ->sum('total');

        $payments_discount = POSDiscount::where('so_number', $payment_credit_record->so_number)
                                    ->where('status', POSDiscountStatus::ACTIVE)
                                    ->first()
                                    ->price ?? 0;

        $payments_vat = POSVat::where('so_number', $payment_credit_record->so_number)
                                    ->where('status', POSVatStatus::ACTIVE)
                                    ->first()
                                    ->price ?? 0;

        $grand_total = ($payments_total + $payments_vat) - $payments_discount;
        $remaining_balance = $grand_total - $payment_credit->price;

        if ($payment_credit_record->price > $remaining_balance) {
            $request->session()->flash('error', 'Entered price is greater than the remaining balance');
            return back();
        }

        /* payment credit data */
        $payment_credit->price += $payment_credit_record->price;

        /* if payment credit price is greater or equal to remaining balance */
        if ($payment_credit->price == $grand_total) {
            // $payment_credit->status = PaymentCreditStatus::FULLY_PAID;
            // $payment_credit->status = PaymentCreditStatus::PARTIALLY_PAID;

            /* if the payment credit is already in pending then mark it as fully paid */
            if ($payment_credit->status == PaymentCreditStatus::PENDING)
                $payment_credit->status = PaymentCreditStatus::FULLY_PAID;
            else
                $payment_credit->status = PaymentCreditStatus::FULLY_PAID;
                // $payment_credit->status = PaymentCreditStatus::PENDING;
        } else {
            $payment_credit->status = PaymentCreditStatus::PARTIALLY_PAID;
        }

        // if ($payment_credit->price == 0)
        //     $payment_credit->status = PaymentCreditStatus::CREDIT;

        $payment_credit->save();
        /* payment credit data */

        $payment_credit_record = PaymentCreditRecord::find($payment_credit_record_id);
        $payment_credit_record->status = PaymentCreditRecordStatus::APPROVED; // mark data as inactive
        $payment_credit_record->save();

        $request->session()->flash('success', 'Data has been approved');

        return back();
    }

    public function disapprove(Request $request, $payment_credit_record_id)
    {
        $payment_credit_record = PaymentCreditRecord::find($payment_credit_record_id);
        $payment_credit = PaymentCredit::find($payment_credit_record->payment_credit->id);

        $payments_total = Payment::where('so_number', $payment_credit_record->so_number)
                                ->where('status', '!=', PaymentStatus::INACTIVE)
                                ->sum('total');

        $payments_discount = POSDiscount::where('so_number', $payment_credit_record->so_number)
                                    ->where('status', POSDiscountStatus::ACTIVE)
                                    ->first()
                                    ->price ?? 0;

        $payments_vat = POSVat::where('so_number', $payment_credit_record->so_number)
                                    ->where('status', POSVatStatus::ACTIVE)
                                    ->first()
                                    ->price ?? 0;

        $grand_total = ($payments_total + $payments_vat) - $payments_discount;
        $remaining_balance = $grand_total - $payment_credit->price;

        /* if payment credit price is less than or equal to remaining balance */
        // if ($payment_credit->price < $grand_total) {
        //     $payment_credit->status = PaymentCreditStatus::PARTIALLY_PAID;
        // }
        $payment_credit->save();

        /* payment credit data */
        // $payment_credit->price -= $payment_credit_record->price;
        /* if payment reaches negative value return 0 */
        // if ($payment_credit->price < 0)
        //     $payment_credit->price = 0;

        /* if payment credit price is greater or equal to remaining balance */
        // if ($payment_credit->price < $grand_total)
        //     $payment_credit->status = PaymentCreditStatus::PARTIALLY_PAID;
        
        //$payment_credit->save();
        /* payment credit data */

        $payment_credit_record = PaymentCreditRecord::find($payment_credit_record_id);
        $payment_credit_record->status = PaymentCreditRecordStatus::DISAPPROVED; // mark data as inactive
        $payment_credit_record->save();

        $request->session()->flash('success', 'Data has been disapproved');

        return back();
    }

    public function delete(Request $request, $payment_credit_record_id)
    {
        $payment_credit_record = PaymentCreditRecord::find($payment_credit_record_id);
        $payment_credit_record->status = PaymentCreditRecordStatus::INACTIVE; // mark data as inactive
        $payment_credit_record->save();

        $request->session()->flash('success', 'Data has been deleted');

        return back();
    }
}
