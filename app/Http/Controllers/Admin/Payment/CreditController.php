<?php

namespace App\Http\Controllers\Admin\Payment;

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
use App\Models\PaymentReceipt;
use App\Models\InventoryStatus;
use App\Models\ItemSerialNumber;
use App\Models\POSDiscountStatus;
use App\Models\PaymentCreditRecord;
use App\Models\PaymentCreditStatus;
use App\Models\ItemSerialNumberStatus;
use App\Models\PaymentCreditRecordStatus;

class CreditController extends Controller
{
    public function show()
    {
        $payment_credits = PaymentCredit::orderBy('created_at', 'desc')
                                    ->where('status', '!=', PaymentCreditStatus::INACTIVE)
                                    ->paginate(15);

        $salespersons = User::where('role', '!=', 'Customer')
                            ->where('role', '!=', 'Agent')
                            ->where('role', '!=', 'Corporate')
                            ->where('status', UserStatus::ACTIVE)
                            ->get();

        return view('admin.payment_credits.show', compact(
            'payment_credits',
            'salespersons'
        ));
    }

    public function search(Request $request)
    {
        $so_number = $request->so_number ?? '*';
        $bir_number = $request->bir_number ?? '*';
        $invoice_number = $request->invoice_number ?? '*';
        $name = $request->name ?? '*';
        $salesperson_id = $request->salesperson_id ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('accounting.payment-credits.filter', [$so_number, $bir_number, $invoice_number, $name, $salesperson_id, $status, $from_date, $to_date])->withInput();
    }

    public function filter($so_number, $bir_number, $invoice_number, $name, $salesperson_id, $status, $from_date, $to_date)
    {
        $query = PaymentCredit::leftJoin('payments', 'payment_credits.payment_id', '=', 'payments.id')
                    ->leftJoin('users', 'payments.user_id', '=', 'users.id')
                    ->select('payment_credits.*')
                    ->where('payments.status', '!=', PaymentStatus::INACTIVE)
                    ->orderBy('payment_credits.created_at', 'desc');

        if ($so_number != '*') {
            $query->where('payments.so_number', $so_number);
        }

        if ($bir_number != '*') {
            $query->where('payments.bir_number', $bir_number);
        }

        if ($invoice_number != '*') {
            $query->where('payment_credits.invoice_number', $invoice_number);
        }

        if ($salesperson_id != '*') {
            $query->where('payments.salesperson_id', $salesperson_id);
        }

        if ($name != '*') {
            $query->whereRaw("concat(users.firstname, ' ', users.lastname) like '%" . $name . "%' ");
            
            /* original query */
            // $query->where('users.firstname', $name);
            // $query->orWhere('users.lastname', $name);
        }

        if ($status != '*') {
            $query->where('payment_credits.status', $status);
            $query->where('payments.status', '!=', PaymentStatus::CANCELLED);
        }

        /* date filter */
        // if they provided both from date and to date
        if ($from_date != '*' && $to_date != '*') {
            $query->whereBetween('payment_credits.created_at', [$from_date . ' 00:00:00', $to_date . ' 23:59:59']);
        }
        /* date filter */

        $payment_credits = $query->paginate(15);

        $salespersons = User::where('role', '!=', 'Customer')
                            ->where('role', '!=', 'Agent')
                            ->where('role', '!=', 'Corporate')
                            ->where('status', UserStatus::ACTIVE)
                            ->get();

        return view('admin.payment_credits.show', compact(
            'salespersons',
            'payment_credits'
        ));
    }

    public function view($so_number)
    {
        $main_payment = Payment::where('so_number', $so_number)
                            ->first();

        $payment_credit = PaymentCredit::where('so_number', $main_payment->so_number)
                                    ->first();
        $payment_credit_total = PaymentCredit::where('so_number', $main_payment->so_number)
                                        ->sum('price');

        $payment_credit_records = PaymentCreditRecord::where('so_number', $main_payment->so_number)
                                                ->where('status', '!=', PaymentCreditStatus::INACTIVE)
                                                ->paginate(15);

        $payments_total = Payment::where('so_number', $main_payment->so_number)
                                ->where('status', '!=', PaymentStatus::INACTIVE)
                                ->sum('total');

        $payments_discount = POSDiscount::where('so_number', $main_payment->so_number)
                                    ->where('status', POSDiscountStatus::ACTIVE)
                                    ->first()
                                    ->price ?? 0;

        $payments_vat = POSVat::where('so_number', $main_payment->so_number)
                                    ->where('status', POSVatStatus::ACTIVE)
                                    ->first()
                                    ->price ?? 0;

        $grand_total = ($payments_total + $payments_vat) - $payments_discount;
        $remaining_balance = $grand_total + $payment_credit->price;

        return view('admin.payment_credits.view', compact(
            'grand_total',
            'remaining_balance',
            'payment_credit_records',
            'payment_credit',
            'payment_credit_total',
            'main_payment'
        ));
    }

    public function pay(Request $request)
    {
        $rules = [
            'mop' => 'required',
            'price' => 'required',
            'image' => 'required|image'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) 
            return back()->withInput()->withErrors($validator);

        $payment_credit = PaymentCredit::where('so_number', $request->so_number)
                                    ->where('status', '!=', PaymentCreditStatus::INACTIVE)
                                    ->first();

        $payments_count = Payment::where('so_number', $request->so_number)
                                ->where('status', '!=', PaymentStatus::INACTIVE)
                                ->sum('qty');

        $payments_total = Payment::where('so_number', $request->so_number)
                                ->where('status', '!=', PaymentStatus::INACTIVE)
                                ->sum('total');

        $payments_discount = POSDiscount::where('so_number', $request->so_number)
                                    ->where('status', POSDiscountStatus::ACTIVE)
                                    ->first()
                                    ->price ?? 0;

        $payments_vat = POSVat::where('so_number', $request->so_number)
                                    ->where('status', POSVatStatus::ACTIVE)
                                    ->first()
                                    ->price ?? 0;

        $grand_total = ($payments_total + $payments_vat) - $payments_discount;
        $remaining_balance = $grand_total - $payment_credit->price;

        if ($request->price > $remaining_balance) {
            $request->session()->flash('error', 'Entered price is greater than the remaining balance');
            return back();
        }

        $data = $request->all();
        $term = PaymentCreditRecord::where('so_number', $request->so_number)
                        ->max('term') + 1;

        $data['term'] = $term; // first payment
        $data['price'] = $request->price; // initial downpayment
        $data['days_due'] = $payment_credit->days_due;
        $data['interest'] = $payment_credit->interest;
        $data['payment_credit_id'] = $payment_credit->id;

        if ($request->file('image')) { // if the file is present
            $image_name = $request->so_number . '-' . time() . '.' . $request->file('image')->getClientOriginalExtension(); // set unique name for that file
            $request->file('image')->move('uploads/images/payment-credits', $image_name); // move the file to the laravel project
            $data['image'] = 'uploads/images/payment-credits/' . $image_name; // save the destination of the file to the database
        }

        $data['status'] = PaymentCreditRecordStatus::PENDING;
        PaymentCreditRecord::create($data); // create data in a model

        
        $payment_credit = PaymentCredit::find($payment_credit->id);

        if ($request->price >= $remaining_balance) {
            if ($payment_credit->status == PaymentCreditStatus::PENDING) {
                $payment_credit->status = PaymentCreditStatus::PENDING;
            } elseif ($payment_credit->status == PaymentCreditStatus::PARTIALLY_PAID) {
                $payment_credit->status = PaymentCreditStatus::PARTIALLY_PAID;
            } else {
                $payment_credit->status = PaymentCreditStatus::PENDING;
            }
        } else {
            $payment_credit->status = PaymentCreditStatus::PARTIALLY_PAID;
        }

        $payment_credit->save();

        $request->session()->flash('success', 'Data has been updated');

        // return back();
        return redirect()->route('accounting.payment-credits.view', [$request->so_number]);
    }

    public function invoiceNumber(Request $request)
    {
        $rules = [
            'invoice_number' => 'required|unique:payment_credits',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) 
            return back()->withInput()->withErrors($validator);

        DB::table('payment_credits')
            ->where('so_number', $request->so_number)
            ->update([
                'invoice_number' => $request->invoice_number
            ]);

        $request->session()->flash('success', 'Data has been updated');

        return back();
    }

    public function recover(Request $request, $so_number)
    {
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

    public function delete(Request $request, $payment_credit_id)
    {
        $payment_credit = PaymentCredit::find($payment_credit_id);
        $payment_credit->status = PaymentStatus::INACTIVE; // mark data as inactive
        $payment_credit->save();

        $request->session()->flash('success', 'Data has been deleted');
        return back();
    }

    public function complete(Request $request, $payment_credit_id)
    {
        $payment_credit = PaymentCredit::find($payment_credit_id);
        $payment_credit->status = PaymentCreditStatus::FULLY_PAID; // mark data as fully_paid
        $payment_credit->save();

        $request->session()->flash('success', 'Data has been deleted');
        return back();
    }

    public function database_update(Request $request)
    {
        /* payment credit overdue checker */
        $payment_credits = PaymentCredit::all();
        foreach ($payment_credits as $payment_credit) {
            $payment_credit_records = PaymentCreditRecord::where('payment_credit_id', $payment_credit->id)
                                                    ->where('price', '>', 0)
                                                    ->where('status', PaymentCreditRecordStatus::PENDING)
                                                    ->get();

            $payments_total = Payment::where('so_number', $payment_credit->so_number)
                                ->where('status', '!=', PaymentStatus::INACTIVE)
                                ->sum('total');

            $payments_discount = POSDiscount::where('so_number', $payment_credit->so_number)
                                        ->where('status', POSDiscountStatus::ACTIVE)
                                        ->first()
                                        ->price ?? 0;

            $payments_vat = POSVat::where('so_number', $payment_credit->so_number)
                                        ->where('status', POSVatStatus::ACTIVE)
                                        ->first()
                                        ->price ?? 0;

            $grand_total = ($payments_total + $payments_vat) - $payments_discount;
            $remaining_balance = $grand_total - $payment_credit->price;

            DB::table('payment_credit_records')
                ->where('price', 0)
                ->where('status', PaymentCreditRecordStatus::PENDING)
                ->update([
                    'status' => PaymentCreditRecordStatus::INACTIVE,
                ]);

            /* fully paid checker */
            if ($remaining_balance == 0) {
                $payment_credit = PaymentCredit::find($payment_credit->id);
                $payment_credit->status = PaymentCreditStatus::FULLY_PAID;
                $payment_credit->save();
            }

            /* only touch payment credit status that are not fully or partially paid */
            if ($payment_credit->status == PaymentCreditStatus::FULLY_PAID || $payment_credit->status == PaymentCreditStatus::PARTIALLY_PAID) {
                // do nothing
            } else {
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
                        if (count($payment_credit_records) > 0) { // if the overdue credit has a pending record
                            if ($payment_credit->status == PaymentCreditStatus::PENDING) {
                                /* tag as pending */
                                $payment_credit = PaymentCredit::find($payment_credit->id);
                                $payment_credit->status = PaymentCreditStatus::PENDING;
                                $payment_credit->save();
                            }
                        } else {
                            /* tag as overdue */
                            $payment_credit = PaymentCredit::find($payment_credit->id);
                            $payment_credit->status = PaymentCreditStatus::OVERDUE;
                            $payment_credit->save();
                        }
                    }
                }
            }

            /* payment receipt exist checker */
            if ($payment_credit->payment_receipt_id == 0) {
                $rep_payment_receipt = PaymentReceipt::where('so_number', $payment_credit->so_number)
                                                ->first();
                $payment_credit->payment_receipt_id = $rep_payment_receipt->id;
                $payment_credit->save();
            }

            /* mop cash checker */
            if ($payment_credit->payment_receipt) {
                if ($payment_credit->payment_receipt->mop == 'cash') {
                    $payment_credit->status = PaymentCreditStatus::INACTIVE;
                    $payment_credit->save();
                }
            }
        }

        $request->session()->flash('success', 'Data has been updated');
        return back();
    }
}
