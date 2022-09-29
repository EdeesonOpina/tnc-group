<?php

namespace App\Http\Controllers\Front\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Validator;
use App\Models\User;
use App\Models\Cart;
use App\Models\POSVat;
use App\Models\Branch;
use App\Models\Account;
use App\Models\Payment;
use App\Models\Inventory;
use App\Models\CartStatus;
use App\Models\POSDiscount;
use App\Models\POSVatStatus;
use App\Models\PaymentStatus;
use App\Models\InventoryStatus;
use App\Models\POSDiscountStatus;

class PaymentController extends Controller
{
    public function show()
    {
        $payments = Payment::where('user_id', auth()->user()->id)
                        ->orderBy('created_at', 'desc')
                        ->paginate(20);

        $payments_total = Payment::where('user_id', auth()->user()->id)
                        ->orderBy('created_at', 'desc')
                        ->sum('total');

        return view('front.shop.payments.show', compact(
            'payments_total',
            'payments'
        ));
    }

    public function track()
    {
        return view('front.shop.payments.track');
    }

    public function create(Request $request)
    {
        $rules = [
            'firstname' => 'required',
            'lastname' => 'required',
            'line_address_1' => 'required',
            'country_id' => 'required',
            'mobile' => 'required',
            'phone' => 'nullable',
            'mop' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) 
            return back()->withInput()->withErrors($validator);

        $data = request()->all(); // get all request
        $receipt_data = []; // declare as an array
        $credit_data = []; // declare as an array
        $payment_data = []; // declare as an array

        $user = User::find(auth()->user()->id);
        $user->fill($data)->save();

        // get all the customer's items
        $carts = Cart::where('user_id', auth()->user()->id)
                    ->where('status', CartStatus::ACTIVE)
                    ->get();

        $so_count = str_replace('BFG-', '', Payment::orderBy('created_at', 'desc')->max('so_number')) + 1;
        // $so_count = str_replace('BFG-', '', 0) + 1;

        foreach ($carts as $cart) {
            $data['so_number'] = 'BFG-' . str_pad($so_count, 8, '0', STR_PAD_LEFT);
            $data['account_id'] = Account::BDO; // use company BDO bank
            $data['branch_id'] = Branch::MAIN_BRANCH; // use company BDO bank
            $data['user_id'] = auth()->user()->id;
            $data['cart_id'] = $cart->id;
            $data['inventory_id'] = $cart->inventory->id;
            $data['item_id'] = $cart->inventory->item->id;
            $data['price'] = $cart->price;
            $data['qty'] = $cart->qty;
            $data['total'] = $cart->price * $cart->qty;
            $data['real_total'] = $cart->price * $cart->qty;
            $data['is_pos_transaction'] = 0;
            $data['status'] = PaymentStatus::PENDING; // if you want to insert to a specific column
            Payment::create($data); // create data in a model
        }

        // get the payment after creating it
        $payment = Payment::where('so_number', 'BFG-' . str_pad($so_count, 8, '0', STR_PAD_LEFT))
                        ->where('user_id', auth()->user()->id)
                        ->where('status', PaymentStatus::PENDING)
                        ->latest()
                        ->first();

        /* get the total of the newly created receipt */
        $payments_total = Payment::where('so_number', 'BFG-' . str_pad($so_count, 8, '0', STR_PAD_LEFT))
                                ->sum('total');

        /* save to payment receipt */
        $receipt_data['so_number'] = 'BFG-' . str_pad($so_count, 8, '0', STR_PAD_LEFT);
        $receipt_data['user_id'] = auth()->user()->id;
        $receipt_data['authorized_user_id'] = 1;
        $receipt_data['branch_id'] = 1;
        $receipt_data['is_credit'] = 1;
        $receipt_data['mop'] = 'credit';
        $receipt_data['discount'] = 0;
        $receipt_data['vat'] = 0;
        $receipt_data['total'] = $payments_total;
        $receipt_data['real_total'] = $payments_total;
        $receipt_data['status'] = PaymentReceiptStatus::PENDING;
        PaymentReceipt::create($receipt_data); // create data in a model

        /* get created payment receipt data */
        $payment_receipt = PaymentReceipt::where('so_number', 'BFG-' . str_pad($so_count, 8, '0', STR_PAD_LEFT))
                                        ->latest()
                                        ->first();

        /* save the credit */
        $credit_data['so_number'] = 'BFG-' . str_pad($so_count, 8, '0', STR_PAD_LEFT);
        $credit_data['price'] = 0; // initial downpayment
        $credit_data['days_due'] = $request->days_due;
        $credit_data['interest'] = $request->interest;
        $credit_data['payment_id'] = $payment->id;
        $credit_data['payment_receipt_id'] = $payment_receipt->id;
        if ($request->mop == 'credit') {
            $credit_data['status'] = PaymentCreditStatus::UNPAID;
        } else {
            $credit_data['status'] = PaymentCreditStatus::UNPAID;
        }

        PaymentCredit::create($credit_data); // create data in a model

        /* get created payment credit data */
        $payment_credit = PaymentCredit::where('so_number', 'BFG-' . str_pad($so_count, 8, '0', STR_PAD_LEFT))
                                    ->latest()
                                    ->first();

        /* save the discount */
        $payment_data['so_number'] = 'BFG-' . str_pad($so_count, 8, '0', STR_PAD_LEFT);
        $payment_data['price'] = 0;
        $payment_data['cart_id'] = $cart->id;
        $payment_data['payment_id'] = $payment->id;
        $payment_data['payment_receipt_id'] = $payment_receipt->id;
        $payment_data['status'] = POSDiscountStatus::ACTIVE;
        POSDiscount::create($payment_data); // create data in a model

        /* save the vat */
        $payment_data['so_number'] = 'BFG-' . str_pad($so_count, 8, '0', STR_PAD_LEFT);
        $payment_data['price'] = 0;
        $payment_data['cart_id'] = $cart->id;
        $payment_data['payment_id'] = $payment->id;
        $payment_data['payment_receipt_id'] = $payment_receipt->id;
        $payment_data['status'] = POSVatStatus::ACTIVE;
        POSVat::create($payment_data); // create data in a model

        // label the carts as checked out
        DB::table('carts')
            ->where('user_id', auth()->user()->id)
            ->where('status', CartStatus::ACTIVE)
            ->update([
                'status' => CartStatus::CHECKED_OUT,
            ]);

        $request->session()->flash('success', 'Data has been added');

        return view('front.shop.carts.success');
    }

    public function find(Request $request)
    {
        return back()->withInput();
    }
}
