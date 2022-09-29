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
use App\Models\Order;
use App\Models\POSVat;
use App\Models\Payment;
use App\Models\UserStatus;
use App\Models\OrderStatus;
use App\Models\POSDiscount;
use App\Models\POSVatStatus;
use App\Models\PaymentCredit;
use App\Models\PaymentStatus;
use App\Models\PaymentReceipt;
use App\Models\ItemSerialNumber;
use App\Models\POSDiscountStatus;
use App\Models\PaymentCreditStatus;
use App\Models\PaymentReceiptStatus;
use App\Models\ItemSerialNumberStatus;

class QueryController extends Controller
{
    public function payment_receipts(Request $request)
    {
        $payments = Payment::all();
        $receipt_data = [];

        foreach ($payments->unique('so_number') as $payment) {
            $vat = POSVat::where('so_number', $payment->so_number)->first()->price ?? '0.00';
            $discount = POSDiscount::where('so_number', $payment->so_number)->first()->price ?? '0.00';

            if (!PaymentReceipt::where('so_number', $payment->so_number)->exists()) {
                /* save to payment receipt */
                $receipt_data['so_number'] = $payment->so_number;
                $receipt_data['salesperson_id'] = $payment->salesperson_id;
                $receipt_data['user_id'] = $payment->user_id;
                $receipt_data['is_credit'] = $payment->is_credit;
                $receipt_data['authorized_user_id'] = $payment->authorized_user_id;
                $receipt_data['branch_id'] = $payment->branch_id;
                $receipt_data['mop'] = $payment->mop ?? 'cash';
                $receipt_data['discount'] = $discount;
                $receipt_data['vat'] = $vat;
                $receipt_data['total'] = $payment->total;
                $receipt_data['real_total'] = $payment->real_total;
                $receipt_data['status'] = $payment->status;
                $receipt_data['created_at'] = $payment->created_at;
                $receipt_data['updated_at'] = $payment->updated_at;
                PaymentReceipt::create($receipt_data); // create data in a model
            }
        }
    }

    public function payments(Request $request)
    {
        $payments = Payment::all();

        foreach ($payments->unique('so_number') as $payment) {
            $payment_receipt = PaymentReceipt::where('so_number', $payment->so_number)
                                        ->first();

            DB::table('payments')
            ->where('so_number', $payment_receipt->so_number)
            ->update([
                'payment_receipt_id' => $payment_receipt->id,
            ]);
        }
    }

    public function salespersons(Request $request)
    {
        $payments = Payment::where('salesperson_id', '>', 0)->get();

        foreach ($payments as $payment) {
            $payment_receipt = PaymentReceipt::where('so_number', $payment->so_number)
                                        ->first();

            $payment_receipt->salesperson_id = $payment->salesperson_id;
            $payment_receipt->save();
        }
    }

    public function payment_credits(Request $request)
    {
        
    }

    public function pos_vat(Request $request)
    {
        $pos_vats = POSVat::all();
        
        foreach ($pos_vats as $pos_vat) {
            if (PaymentReceipt::where('so_number', $pos_vat->so_number)
                                        ->exists()) {
                $payment_receipt = PaymentReceipt::where('so_number', $pos_vat->so_number)
                                            ->first();

                $db_pos_vat = POSVat::find($pos_vat->id);
                $db_pos_vat->payment_receipt_id = $payment_receipt->id;
                $db_pos_vat->save();
            }
        }
    }

    public function pos_discount(Request $request)
    {
        $pos_discounts = POSDiscount::all();
        
        foreach ($pos_discounts as $pos_discount) {
            if (PaymentReceipt::where('so_number', $pos_discount->so_number)
                                        ->exists()) {
                $payment_receipt = PaymentReceipt::where('so_number', $pos_discount->so_number)
                                        ->first();

                $db_pos_discount = POSDiscount::find($pos_discount->id);
                $db_pos_discount->payment_receipt_id = $payment_receipt->id;
                $db_pos_discount->save();
            }
        }
    }

    public function costing(Request $request)
    {
        $payments = Payment::leftJoin('inventories', 'payments.inventory_id', '=', 'inventories.id')
                        ->select('payments.*')
                        ->where('inventories.goods_receipt_id', '>', 0)->get();

        foreach ($payments as $payment) {
                $landing_price = Order::where('item_id', $payment->item->id)
                                      ->where('goods_receipt_id', $payment->inventory->goods_receipt->id)
                                      ->where('status', OrderStatus::ACTIVE)
                                      ->latest()
                                      ->first()
                                      ->price ?? 0;

            $db_payment = Payment::find($payment->id);
            $db_payment->cost = $landing_price;
            $db_payment->save();
        }
    }

    public function overdue(Request $request)
    {
        $payment_credits = PaymentCredit::all();

        foreach ($payment_credits as $payment_credit) {
            /* overdue checker */
            if (Carbon::now() <= ($payment_credit->created_at->addDays($payment_credit->days_due))) {
                if ($payment_credit->status == PaymentCreditStatus::FULLY_PAID || $payment_credit->status == PaymentCreditStatus::PARTIALLY_PAID) {
                    /* the status will not change */
                } else if ($payment_credit->status == PaymentCreditStatus::CREDIT) {
                    /* tag as unpaid */
                    $payment_credit = PaymentCredit::find($payment_credit->id);
                    $payment_credit->status = PaymentCreditStatus::UNPAID;
                    $payment_credit->save();
                }
            } else {
                if ($payment_credit->status == PaymentCreditStatus::FULLY_PAID) {
                    /* the status will not change */
                } else {
                    /* tag as overdue */
                    $payment_credit = PaymentCredit::find($payment_credit->id);
                    $payment_credit->status = PaymentCreditStatus::OVERDUE;
                    $payment_credit->save();
                }
            }
        }
    }

    public function discount(Request $request)
    {
        $pos_discounts = POSDiscount::all();

        foreach ($pos_discounts as $pos_discount) {
            if (PaymentReceipt::where('so_number', $pos_discount->so_number)->exists()) {
                $payment_receipt = PaymentReceipt::where('so_number', $pos_discount->so_number)->first();
                $payment_receipt->discount = $pos_discount->price;
                $payment_receipt->save();
            }
        }
    }

    public function vat(Request $request)
    {
        $pos_vats = POSVat::all();

        foreach ($pos_vats as $pos_vat) {
            if (PaymentReceipt::where('so_number', $pos_vat->so_number)->exists()) {
                $payment_receipt = PaymentReceipt::where('so_number', $pos_vat->so_number)->first();
                $payment_receipt->vat = $pos_vat->price;
                $payment_receipt->save();
            }
        }
    }

    public function item_serial_numbers(Request $request)
    {
        $item_serial_numbers = ItemSerialNumber::all();

        foreach ($item_serial_numbers as $item_serial_number) {
            $db_item_serial_number = ItemSerialNumber::find($item_serial_number->id);

            if ($db_item_serial_number) {
                if (Payment::where('id', $item_serial_number->payment_id)->exists()) {
                    $payment = Payment::where('id', $item_serial_number->payment_id)->first();
                    $payment_receipt = PaymentReceipt::where('id', $payment->payment_receipt_id)->first();

                    if ($payment_receipt)
                        $db_item_serial_number->payment_receipt_id = $payment_receipt->id;
                    $db_item_serial_number->save();
                }
            }
        }
    }

    public function verify_internal_users(Request $request)
    {
        DB::table('users')
            ->where('role', '!=', 'Customer')
            ->where('role', '!=', 'Agent')
            ->where('role', '!=', 'Corporate')
            ->update([
                'email_verified_at' => Carbon::now(),
                'status' => UserStatus::ACTIVE,
            ]);
    }
}
