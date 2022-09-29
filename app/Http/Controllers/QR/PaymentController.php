<?php

namespace App\Http\Controllers\QR;

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
use App\Models\ItemSerialNumber;
use App\Models\POSDiscountStatus;
use App\Models\ItemSerialNumberNumber;

class PaymentController extends Controller
{
    public function view($so_number)
    {
        $main_payment = Payment::where('so_number', $so_number)
                            ->first();
        $branch = Branch::find($main_payment->branch_id); // branch of that pos
        $user = User::find($main_payment->user_id); // the customer
        $cashier = User::find($main_payment->authorized_user_id); // the cashier in charge

        $payments = Payment::where('so_number', $main_payment->so_number)
                        ->where('status', '!=', PaymentStatus::INACTIVE)
                        ->get();

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

        $item_serial_numbers = ItemSerialNumber::where('payment_id', $main_payment->id)
                                            ->get();

        return view('qr.payments.view', compact(
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
}
