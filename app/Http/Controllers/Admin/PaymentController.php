<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Auth;
use Mail;
use Validator;
use App\Models\Cart;
use App\Models\User;
use App\Models\Branch;
use App\Models\POSVat;
use App\Models\Payment;
use App\Models\Inventory;
use App\Models\CartStatus;
use App\Models\UserStatus;
use App\Models\POSDiscount;
use App\Models\PaymentProof;
use App\Models\POSVatStatus;
use App\Models\BranchStatus;
use App\Models\PaymentCredit;
use App\Models\PaymentStatus;
use App\Models\PaymentReceipt;
use App\Models\InventoryStatus;
use App\Models\ItemSerialNumber;
use App\Models\POSDiscountStatus;
use App\Models\PaymentProofStatus;
use App\Models\PaymentCreditStatus;
use App\Models\PaymentReceiptStatus;
use App\Models\ItemSerialNumberStatus;

class PaymentController extends Controller
{
    public function show()
    {
        $payment_receipts = PaymentReceipt::orderBy('created_at', 'desc')
                        ->paginate(15);

        // $payments = Payment::orderBy('created_at', 'desc')
        //                 ->paginate(15);

        return view('admin.payments.show', compact(
            // 'payments'
            'payment_receipts'
        ));
    }

    public function search(Request $request)
    {
        $so_number = $request->so_number ?? '*';
        $name = $request->name ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('accounting.payments.filter', [$so_number, $name, $status, $from_date, $to_date])->withInput();
    }

    public function filter($so_number, $name, $status, $from_date, $to_date)
    {
        // $query = Payment::leftJoin('users', 'payments.user_id', '=', 'users.id')
        //             ->leftJoin('payment_receipts', 'payments.payment_receipt_id', '=', 'payment_receipts.id')
        //             ->select('payments.*')
        //             ->orderBy('payments.created_at', 'desc');

        $query = PaymentReceipt::leftJoin('users', 'payment_receipts.user_id', '=', 'users.id')
                    ->select('payment_receipts.*')
                    ->orderBy('payment_receipts.created_at', 'desc');

        if ($so_number != '*') {
            $query->where('payment_receipts.so_number', $so_number);
        }

        // if ($so_number != '*') {
        //     $query->where('payments.so_number', $so_number);
        // }

        if ($name != '*') {
            $query->whereRaw("concat(users.firstname, ' ', users.lastname) like '%" . $name . "%' ");
            
            /* original query */
            // $query->where('users.firstname', $name);
            // $query->orWhere('users.lastname', $name);
        }

        if ($status != '*') {
            $query->where('payment_receipts.status', $status);
        }

        /* date filter */
        // if they provided both from date and to date
        if ($from_date != '*' && $to_date != '*') {
            $query->whereBetween('payment_receipts.created_at', [$from_date . ' 00:00:00', $to_date . ' 23:59:59']);
        }
        /* date filter */

        // $payments = $query->paginate(15);
        $payment_receipts = $query->paginate(15);

        return view('admin.payments.show', compact(
            'payment_receipts'
        ));
    }

    public function view($so_number)
    {
        $payment_receipt = PaymentReceipt::where('so_number', $so_number)
                                    ->first();

        $payment_receipts = PaymentReceipt::where('so_number', $so_number)
                                    ->where('status', '!=', PaymentReceiptStatus::INACTIVE)
                                    ->get();

        $main_payment = Payment::where('so_number', $so_number)
                            ->first();
        $branch = Branch::find($payment_receipt->branch_id); // branch of that pos
        $user = User::find($payment_receipt->user_id); // the customer
        $cashier = User::find($payment_receipt->authorized_user_id); // the cashier in charge

        $payments = Payment::where('so_number', $payment_receipt->so_number)
                        ->where('status', '!=', PaymentStatus::INACTIVE)
                        ->get();

        $payments_total = Payment::where('so_number', $payment_receipt->so_number)
                                ->where('status', '!=', PaymentStatus::INACTIVE)
                                ->sum('total');

        $payments_discount = POSDiscount::where('so_number', $payment_receipt->so_number)
                                    ->where('status', POSDiscountStatus::ACTIVE)
                                    ->first()
                                    ->price ?? 0;

        $payments_vat = POSVat::where('so_number', $payment_receipt->so_number)
                                    ->where('status', POSVatStatus::ACTIVE)
                                    ->first()
                                    ->price ?? 0;

        $grand_total = ($payments_vat + $payments_total) - $payments_discount;

        if (PaymentCredit::where('so_number', $payment_receipt->so_number)
                    ->exists()) {
            $payment_credits = PaymentCredit::where('so_number', $payment_receipt->so_number)
                                        ->get();
            $payment_credit_total = PaymentCredit::where('so_number', $payment_receipt->so_number)
                                            ->sum('price');
        } else {
            $payment_credits = [];
            $payment_credit_total = 0;
        }

        $item_serial_numbers = ItemSerialNumber::where('payment_id', $main_payment->id)
                                            ->get() ?? null;

        return view('admin.payments.view', compact(
            'payment_receipts',
            'payment_receipt',
            'grand_total',
            'payment_credits',
            'payment_credit_total',
            'main_payment',
            'branch',
            'user',
            'cashier',
            'payments',
            'payments_total',
            'payments_discount',
            'payments_vat',
            'item_serial_numbers',
        ));
    }

    public function mop(Request $request)
    {
        $rules = [
            'mop' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) 
            return back()->withInput()->withErrors($validator);

        $payment_receipt = PaymentReceipt::where('so_number', $request->so_number)
                                    ->first();

        DB::table('payment_receipts')
            ->where('so_number', $request->so_number)
            ->update([
                'mop' => $request->mop
            ]);

        DB::table('payments')
            ->where('so_number', $request->so_number)
            ->update([
                'mop' => $request->mop
            ]);

        if ($request->mop == 'cash') {
            if (PaymentCredit::where('payment_receipt_id', $payment_receipt->id)->exists()) {
                $payment_credit = PaymentCredit::where('payment_receipt_id', $payment_receipt->id)->first();
                $payment_credit->status = PaymentCreditStatus::INACTIVE;
                $payment_credit->save();
            }
        }

        if ($request->mop == 'credit') {
            if (PaymentCredit::where('payment_receipt_id', $payment_receipt->id)->exists()) {
                $payment_credit = PaymentCredit::where('payment_receipt_id', $payment_receipt->id)->first();
                $payment_credit->status = PaymentCreditStatus::UNPAID;
                $payment_credit->save();
            } else {
                $var_payment = Payment::where('so_number', $request->so_number)
                            ->first();

                $payment_credit = new PaymentCredit;
                $payment_credit->so_number = $payment_receipt->so_number;
                $payment_credit->payment_receipt_id = $payment_receipt->id;
                $payment_credit->payment_id = $var_payment->id;
                $payment_credit->price = 0;
                $payment_credit->interest = 0;
                $payment_credit->days_due = 30;
                $payment_credit->status = 0;
                $payment_credit->status = PaymentCreditStatus::UNPAID;
                $payment_credit->save();
            }
        }

        $request->session()->flash('success', 'Data has been updated');
        return back();
    }

    public function discount(Request $request)
    {
        $rules = [
            'price' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) 
            return back()->withInput()->withErrors($validator);

        $data = $request->all();

        $pos_discount = POSDiscount::where('so_number', $request->so_number)
                        ->first();

        /* if the pos exists */
        if ($pos_discount)
            $pos_discount->fill($data)->save();

        /* if the pos does not exist */
        if (! $pos_discount) {
            $payment = Payment::where('so_number', $request->so_number)->first();

            /* payments receipt */
            $payment_receipt = PaymentReceipt::where('so_number', $request->so_number)->first();
            $payment_receipt->discount = $request->price;
            $payment_receipt->save();

            /* save the discount */
            $pos_data['so_number'] = $request->so_number;
            $pos_data['price'] = $request->price;
            $pos_data['cart_id'] = $payment->cart->id;
            $pos_data['payment_id'] = $payment->id;
            $pos_data['status'] = POSDiscountStatus::ACTIVE;
            POSDiscount::create($pos_data); // create data in a model
        }

        $request->session()->flash('success', 'Data has been updated');

        return back();
    }

    public function vat(Request $request)
    {
        $rules = [
            'price' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) 
            return back()->withInput()->withErrors($validator);

        $data = $request->all();

        /* payments receipt */
        $payment_receipt = PaymentReceipt::where('so_number', $request->so_number)->first();
        $payment_receipt->vat = $request->price;
        $payment_receipt->save();

        $pos_vat = POSVat::where('so_number', $request->so_number)
                        ->first();
        $pos_vat->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');

        return back();
    }

    public function proof(Request $request)
    {
        $rules = [
            'image' => 'required|image',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) 
            return back()->withInput()->withErrors($validator);

        if ($request->file('image')) { // if the file is present
            $image_name = $request->so_number . '-' . time() . '.' . $request->file('image')->getClientOriginalExtension(); // set unique name for that file
            $request->file('image')->move('uploads/images/payment-proofs', $image_name); // move the file to the laravel project
            $data['image'] = 'uploads/images/payment-proofs/' . $image_name; // save the destination of the file to the database
        }

        $data['so_number'] = $request->so_number;
        $data['status'] = PaymentProofStatus::ACTIVE;
        PaymentProof::create($data); // create data in a model

        $request->session()->flash('success', 'Data has been uploaded');

        return back();
    }

    public function credit(Request $request)
    {
        $rules = [
            'days_due' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) 
            return back()->withInput()->withErrors($validator);

        $payment = Payment::where('so_number', $request->so_number)
                        ->first();
        $payments_total = Payment::where('so_number', $request->so_number)
                                ->sum('total');

        $data['mop'] = $request->mop;
        $payment->fill($data)->save();

        /* save the credit */
        $data['so_number'] = $payment->so_number;
        $data['price'] = 0; // initial downpayment
        $data['days_due'] = $request->days_due;
        $data['interest'] = 0;
        $data['payment_id'] = $payment->id;
        $data['payment_receipt_id'] = $payment->payment_receipt_id;
        $data['status'] = PaymentCreditStatus::UNPAID;
        PaymentCredit::create($data); // create data in a model

        $request->session()->flash('success', 'Data has been created');

        return back();
    }

    public function birNumber(Request $request)
    {
        $rules = [
            'bir_number' => 'required|unique:payments',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) 
            return back()->withInput()->withErrors($validator);

        /* payments */
        DB::table('payments')
            ->where('so_number', $request->so_number)
            ->update([
                'bir_number' => $request->bir_number
            ]);

        $request->session()->flash('success', 'Data has been updated');

        return back();
    }

    public function invoiceNumber(Request $request)
    {
        $rules = [
            'invoice_number' => 'required|unique:payments',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) 
            return back()->withInput()->withErrors($validator);

        /* payments */
        DB::table('payments')
            ->where('so_number', $request->so_number)
            ->update([
                'invoice_number' => $request->invoice_number
            ]);

        $request->session()->flash('success', 'Data has been updated');

        return back();
    }

    public function confirm(Request $request, $so_number)
    {
        /* payment receipts */
        DB::table('payment_receipts')
            ->where('so_number', $so_number)
            ->update([
                'status' => PaymentReceiptStatus::CONFIRMED
            ]);

        DB::table('payments')
            ->where('so_number', $so_number)
            ->update([
                'status' => PaymentStatus::CONFIRMED
            ]);

        $request->session()->flash('success', 'Data has been confirmed');

        return back();
    }

    public function delivery(Request $request, $so_number)
    {
        /* payment receipts */
        DB::table('payment_receipts')
            ->where('so_number', $so_number)
            ->update([
                'status' => PaymentReceiptStatus::FOR_DELIVERY
            ]);

        DB::table('payments')
            ->where('so_number', $so_number)
            ->update([
                'status' => PaymentStatus::FOR_DELIVERY
            ]);

        $request->session()->flash('success', 'Data has been marked as for delivery');

        return back();
    }

    public function delivered(Request $request, $so_number)
    {
        /* payment receipts */
        DB::table('payment_receipts')
            ->where('so_number', $so_number)
            ->update([
                'status' => PaymentReceiptStatus::DELIVERED
            ]);

        DB::table('payments')
            ->where('so_number', $so_number)
            ->update([
                'status' => PaymentStatus::DELIVERED
            ]);

        $request->session()->flash('success', 'Data has been delivered');

        return back();
    }

    public function completion(Request $request, $so_number)
    {
        DB::table('payments')
            ->where('so_number', $so_number)
            ->update([
                'is_completion' => 1
            ]);

        $request->session()->flash('success', 'Data has been marked as for completion');

        return back();
    }

    public function standalone(Request $request, $so_number)
    {
        DB::table('payments')
            ->where('so_number', $so_number)
            ->update([
                'is_completion' => 0
            ]);

        $request->session()->flash('success', 'Data has been marked as standalone');

        return back();
    }

    public function cancel(Request $request, $so_number)
    {
        /* payment receipts */
        DB::table('payment_receipts')
            ->where('so_number', $so_number)
            ->update([
                'status' => PaymentReceiptStatus::CANCELLED
            ]);

        DB::table('payments')
            ->where('so_number', $so_number)
            ->update([
                'status' => PaymentStatus::CANCELLED
            ]);

        $payments = Payment::where('so_number', $so_number)
                        ->get();
        $payment_receipt = PaymentReceipt::where('so_number', $so_number)
                                ->first();

        foreach ($payments as $payment) {
            $current_inventory = Inventory::where('id', $payment->inventory->id)
                                        ->first();
            $current_inventory->qty += $payment->qty;
            $current_inventory->save();

            $var_item_serial_numbers = ItemSerialNumber::leftJoin('payments', 'item_serial_numbers.payment_id', 'payments.id')
                                                    ->select('item_serial_numbers.*')
                                                    // ->where('payments.item_id', $payment->item->id)
                                                    ->where('item_serial_numbers.item_id', $payment->item->id)
                                                    ->where('payments.user_id', $payment->user->id)
                                                    ->where('item_serial_numbers.status', ItemSerialNumberStatus::SOLD)
                                                    ->get();

            foreach ($var_item_serial_numbers as $var_item_serial_number) {
                /* make sure that the the serial number that we are removing is on the proper SO */
                if ($var_item_serial_number->payment_receipt_id == $payment_receipt->id) {
                    $current_item_serial_number = ItemSerialNumber::find($var_item_serial_number->id);
                    $current_item_serial_number->payment_id = 0; // remove the sold to data
                    $current_item_serial_number->payment_receipt_id = 0; // remove the sold to data
                    $current_item_serial_number->status = ItemSerialNumberStatus::AVAILABLE; // mark it as available
                    $current_item_serial_number->save();
                }
            }
        }

        $request->session()->flash('success', 'Data has been cancelled. Qty has been reverted');

        return back();
    }

    public function serial_number(Request $request)
    {
        $rules = [
            'payment_id' => 'required',
            'serial_number' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $payment_receipt = PaymentReceipt::where('so_number', $request->so_number)->first();
        $payment = Payment::find($request->payment_id);

        /* check if there is an available serial number for this item */
        if (! ItemSerialNumber::where('item_id', $payment->item->id)
                    ->where('code', $request->serial_number)
                    ->where('status', ItemSerialNumberStatus::AVAILABLE)
                    ->first()) {
            $request->session()->flash('error', 'There is no available serial number for this item');
            return back();
        }

        /* tag the serial number as for checkout */
        $item_serial_number = ItemSerialNumber::where('item_id', $payment->item->id)
                                        ->where('code', $request->serial_number)
                                        ->where('status', ItemSerialNumberStatus::AVAILABLE)
                                        ->first();
        $cart = Cart::where('user_id', $request->user_id)
                    ->where('item_id', $payment->item->id)
                    ->where('status', CartStatus::CHECKED_OUT)
                    ->latest()
                    ->first();
        $item_serial_number->payment_id = $payment->id;
        $item_serial_number->payment_receipt_id = $payment_receipt->id;
        $item_serial_number->cart_id = $cart->id;
        $item_serial_number->status = ItemSerialNumberStatus::SOLD;
        $item_serial_number->save();

        $request->session()->flash('success', 'Data has been added');

        return back();
    }

    public function recover(Request $request, $so_number)
    {
        /* payment receipts */
        DB::table('payment_receipts')
            ->where('so_number', $so_number)
            ->update([
                'status' => PaymentReceiptStatus::PENDING
            ]);

        DB::table('payments')
            ->where('so_number', $so_number)
            ->update([
                'status' => PaymentStatus::PENDING
            ]);

        $payments = Payment::where('so_number', $so_number)
                        ->get();

        foreach ($payments as $payment) {
            $current_inventory = Inventory::where('id', $payment->inventory->id)
                                        ->first();
            $current_inventory->qty -= $payment->qty;
            $current_inventory->save();
        }

        $request->session()->flash('success', 'Data has been recovered. Qty has been reverted');

        return back();
    }

    /* original WORKING return to inventory */
    // public function return(Request $request, $payment_id)
    // {
    //     $db_payment = Payment::find($payment_id);
    //     $db_payment->status = PaymentStatus::INACTIVE; // mark data as inactive
    //     $db_payment->save();

    //     $current_inventory = Inventory::where('id', $db_payment->inventory->id)
    //                                     ->first();
    //     $current_inventory->qty += $db_payment->qty;
    //     $current_inventory->save();

    //     $var_item_serial_numbers = ItemSerialNumber::leftJoin('payments', 'item_serial_numbers.payment_id', 'payments.id')
    //                                             ->select('item_serial_numbers.*')
    //                                             // ->where('payments.item_id', $payment->item->id)
    //                                             ->where('item_serial_numbers.item_id', $db_payment->item->id)
    //                                             ->where('payments.user_id', $db_payment->user->id)
    //                                             ->where('item_serial_numbers.status', ItemSerialNumberStatus::SOLD)
    //                                             ->get();

    //     foreach ($var_item_serial_numbers as $var_item_serial_number) {
    //         $current_item_serial_number = ItemSerialNumber::find($var_item_serial_number->id);
    //         $current_item_serial_number->payment_id = 0; // remove the sold to data 
    //         $current_item_serial_number->payment_receipt_id = 0; // remove the sold to data 
    //         $current_item_serial_number->status = ItemSerialNumberStatus::AVAILABLE; // mark it as available
    //         $current_item_serial_number->save();
    //     }

    //     /*
    //     $payment = Payment::where('so_number', $db_payment->so_number)
    //                     ->get();

    //     foreach ($payments as $payment) {
    //         $current_inventory = Inventory::where('id', $payment->inventory->id)
    //                                     ->first();
    //         $current_inventory->qty += $payment->qty;
    //         $current_inventory->save();

    //         $var_item_serial_numbers = ItemSerialNumber::leftJoin('payments', 'item_serial_numbers.payment_id', 'payments.id')
    //                                                 ->select('item_serial_numbers.*')
    //                                                 ->where('payments.item_id', $payment->item->id)
    //                                                 ->where('payments.user_id', $payment->user->id)
    //                                                 ->where('item_serial_numbers.status', ItemSerialNumberStatus::SOLD)
    //                                                 ->get();

    //         foreach ($var_item_serial_numbers as $var_item_serial_number) {
    //             $current_item_serial_number = ItemSerialNumber::find($var_item_serial_number->id);
    //             $current_item_serial_number->payment_id = 0; // remove the sold to data 
    //             $current_item_serial_number->status = ItemSerialNumberStatus::AVAILABLE; // mark it as available
    //             $current_item_serial_number->save();
    //         }
    //     }
    //     */

    //     $request->session()->flash('success', 'Data has been returned');
    //     return back();
    // }

    public function return(Request $request, $payment_id)
    {
        $db_payment = Payment::find($payment_id);
        $payment_receipt = PaymentReceipt::find($db_payment->payment_receipt_id);

        $db_payment->status = PaymentStatus::INACTIVE; // mark data as inactive
        $db_payment->save();

        $current_inventory = Inventory::where('id', $db_payment->inventory->id)
                                        ->first();
        $current_inventory->qty += $db_payment->qty;
        $current_inventory->save();

        $var_item_serial_numbers = ItemSerialNumber::leftJoin('payments', 'item_serial_numbers.payment_id', 'payments.id')
                                                ->select('item_serial_numbers.*')
                                                // ->where('payments.item_id', $payment->item->id)
                                                ->where('item_serial_numbers.item_id', $db_payment->item->id)
                                                ->where('payments.user_id', $db_payment->user->id)
                                                ->where('item_serial_numbers.status', ItemSerialNumberStatus::SOLD)
                                                ->get();

        foreach ($var_item_serial_numbers as $var_item_serial_number) {
            /* make sure that the the serial number that we are removing is on the proper SO */
            if ($var_item_serial_number->payment_receipt_id == $payment_receipt->id) {
                $current_item_serial_number = ItemSerialNumber::find($var_item_serial_number->id);
                $current_item_serial_number->payment_id = 0; // remove the sold to data 
                $current_item_serial_number->payment_receipt_id = 0; // remove the sold to data 
                $current_item_serial_number->status = ItemSerialNumberStatus::AVAILABLE; // mark it as available
                $current_item_serial_number->save();
            }
        }

        $request->session()->flash('success', 'Data has been returned');
        return back();
    }

    public function delete(Request $request, $payment_id)
    {
        /* payment receipts */
        DB::table('payment_receipts')
            ->where('so_number', $so_number)
            ->update([
                'status' => PaymentReceiptStatus::INACTIVE
            ]);
        
        $db_payment = Payment::find($payment_id);
        $db_payment->status = PaymentStatus::INACTIVE; // mark data as inactive
        $db_payment->save();

        $payments = Payment::where('so_number', $db_payment->so_number)
                        ->get();

        foreach ($payments as $payment) {
            $current_inventory = Inventory::where('id', $payment->inventory->id)
                                        ->first();
            $current_inventory->qty += $payment->qty;
            $current_inventory->save();

            $var_item_serial_numbers = ItemSerialNumber::leftJoin('payments', 'item_serial_numbers.payment_id', 'payments.id')
                                                    ->select('item_serial_numbers.*')
                                                    ->where('payments.item_id', $payment->item->id)
                                                    ->where('payments.user_id', $payment->user->id)
                                                    ->where('item_serial_numbers.status', ItemSerialNumberStatus::SOLD)
                                                    ->get();

            foreach ($var_item_serial_numbers as $var_item_serial_number) {
                $current_item_serial_number = ItemSerialNumber::find($var_item_serial_number->id);
                $current_item_serial_number->payment_id = 0; // remove the sold to data 
                $current_item_serial_number->status = ItemSerialNumberStatus::AVAILABLE; // mark it as available
                $current_item_serial_number->save();
            }
        }

        $request->session()->flash('success', 'Data has been deleted');

        return back();
    }
}
