<?php

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\PaymentExport;
use Carbon\Carbon;
use PDF; // declare when creating a pdf
use Auth;
use Mail;
use Validator;
use App\Models\Payment;
use App\Models\User;
use App\Models\Branch;
use App\Models\PaymentReceipt;
use App\Models\PaymentReceiptStatus;
use App\Models\PaymentCredit;
use App\Models\PaymentCreditStatus;
use App\Models\PaymentStatus;
use App\Models\DeliveryReceipt;
use App\Models\ItemSerialNumber;
use App\Models\DeliveryReceiptStatus;
use App\Models\ItemSerialNumberStatus;
use App\Models\POSVat;
use App\Models\POSVatStatus;
use App\Models\POSDiscount;
use App\Models\POSDiscountStatus;
use Maatwebsite\Excel\Facades\Excel; // declare when creating a excel

class PaymentController extends Controller
{
    public function excel($so_number)
    {
        $payment = Payment::where('so_number', $so_number)
                        ->first();

        return Excel::download(new PaymentExport($payment->so_number), $payment->so_number . '.xlsx');
    }

    public function print($so_number)
    {
        $payment_receipt = PaymentReceipt::where('so_number', $so_number)
                                    ->first();
        $main_payment = Payment::where('so_number', $so_number)
                        ->first();
        $branch = Branch::find($main_payment->branch_id); // branch of that pos
        $user = User::find($main_payment->user_id); // the customer
        $cashier = User::find($main_payment->authorized_user_id); // the cashier in charge

        $payments_discount = POSDiscount::where('so_number', $main_payment->so_number)
                                    ->where('status', POSDiscountStatus::ACTIVE)
                                    ->first()
                                    ->price ?? 0;

        $payments_vat = POSVat::where('so_number', $main_payment->so_number)
                                    ->where('status', POSVatStatus::ACTIVE)
                                    ->first()
                                    ->price ?? 0;

        $item_serial_numbers = ItemSerialNumber::where('payment_id', $main_payment->id)
                                            ->get();

        $payments_total = Payment::where('so_number', $main_payment->so_number)
                                ->where('status', '!=', PaymentStatus::INACTIVE)
                                ->sum('total');
        $payments = Payment::where('so_number', $so_number)
                    ->where('status', '!=', PaymentStatus::INACTIVE)
                    ->get();

        $grand_total = $payments_total - ($payments_discount + $payments_vat);

        if (PaymentCredit::where('so_number', $main_payment->so_number)
                    ->exists()) {
            $payment_credits = PaymentCredit::where('so_number', $main_payment->so_number)
                                        ->get();
            $payment_credit_total = PaymentCredit::where('so_number', $main_payment->so_number)
                                            ->sum('price');
        } else {
            $payment_credits = [];
            $payment_credit_total = 0;
        }

        return view('admin.payments.print', compact(
            'payment_receipt',
            'grand_total',
            'payment_credits',
            'payment_credit_total',
            'branch',
            'user',
            'cashier',
            'payments_discount',
            'payments_vat',
            'item_serial_numbers',
            'main_payment',
            'payments_total',
            'payments'
        ));
    }

    public function pdf($so_number)
    {
        $main_payment = Payment::where('so_number', $so_number)
                        ->first();
        $branch = Branch::find($main_payment->branch_id); // branch of that pos
        $user = User::find($main_payment->user_id); // the customer
        $cashier = User::find($main_payment->authorized_user_id); // the cashier in charge

        $payments_discount = POSDiscount::where('so_number', $main_payment->so_number)
                                    ->where('status', POSDiscountStatus::ACTIVE)
                                    ->first()
                                    ->price ?? 0;

        $payments_vat = POSVat::where('so_number', $main_payment->so_number)
                                    ->where('status', POSVatStatus::ACTIVE)
                                    ->first()
                                    ->price ?? 0;

        $item_serial_numbers = ItemSerialNumber::where('payment_id', $main_payment->id)
                                            ->get();

        $payments_total = Payment::where('so_number', $main_payment->so_number)
                                ->where('status', '!=', PaymentStatus::INACTIVE)
                                ->sum('total');
        $query = Payment::where('so_number', $so_number)
                    ->where('status', '!=', PaymentStatus::INACTIVE);

        $grand_total = $payments_total - ($payments_discount + $payments_vat);

        if (PaymentCredit::where('so_number', $main_payment->so_number)
                    ->exists()) {
            $payment_credits = PaymentCredit::where('so_number', $main_payment->so_number)
                                        ->get();
            $payment_credit_total = PaymentCredit::where('so_number', $main_payment->so_number)
                                            ->sum('price');
        } else {
            $payment_credits = [];
            $payment_credit_total = 0;
        }
                    
        // retreive all records from db
        $data = $query->get();

        // share data to view
        view()->share('data', $data);
        view()->share('payment_credits', $payment_credits);
        view()->share('payment_credit_total', $payment_credit_total);
        view()->share('payments_discount', $payments_discount);
        view()->share('payments_vat', $payments_vat);
        view()->share('payments_total', $payments_total);
        view()->share('main_payment', $main_payment);
        view()->share('branch', $branch);
        view()->share('user', $user);
        view()->share('cashier', $cashier);
        view()->share('item_serial_numbers', $item_serial_numbers);

        $pdf = PDF::loadView('admin.payments.pdf', compact($data));

        // download PDF file with download method
        // return $pdf->download(date('M-d-Y') . ' PURCHASE ORDER.pdf'); // return as download
        // return $pdf->stream($main_payment->so_number . '.pdf'); // return as view
        return $pdf->download($main_payment->so_number . '.pdf'); // return as view
    }
}
