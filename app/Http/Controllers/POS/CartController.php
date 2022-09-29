<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Auth;
use Mail;
use Validator;
use App\Models\User;
use App\Models\Cart;
use App\Models\Item;
use App\Models\Order;
use App\Models\POSVat;
use App\Models\Branch;
use App\Models\Account;
use App\Models\Country;
use App\Models\Payment;
use App\Models\Deduction;
use App\Models\Inventory;
use App\Models\ItemStatus;
use App\Models\UserStatus;
use App\Models\CartStatus;
use App\Models\OrderStatus;
use App\Models\POSDiscount;
use App\Models\BranchStatus;
use App\Models\POSVatStatus;
use App\Models\PaymentCredit;
use App\Models\AccountStatus;
use App\Models\CountryStatus;
use App\Models\PaymentStatus;
use App\Models\PaymentReceipt;
use App\Models\DeductionStatus;
use App\Models\InventoryStatus;
use App\Models\ItemSerialNumber;
use App\Models\POSDiscountStatus;
use App\Models\PaymentCreditRecord;
use App\Models\PaymentCreditStatus;
use App\Models\PaymentReceiptStatus;
use App\Models\ItemSerialNumberStatus;
use App\Models\PaymentCreditRecordStatus;

class CartController extends Controller
{
    public function checkout(Request $request)
    {
        $rules = [
            'mop' => 'required',
            'discount' => 'required',
            'vat' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = []; // declare as an array
        $pos_data = []; // declare as an array
        $receipt_data = []; // declare as an array

        $carts = Cart::where('user_id', $request->user_id)
                ->where('authorized_user_id', auth()->user()->id)
                ->where('status', CartStatus::ACTIVE)
                ->get();

        /* for testing local */
        // $so_count = 1;

        /* get the latest po sequence then add 1 */
        $so_count = str_replace('BFG-', '', Payment::orderBy('created_at', 'desc')->first()->so_number) + 1;
        // return $so_count;

        foreach ($carts as $cart) {
            /* get the current costing of the item */
            $landing_price = Order::where('item_id', $cart->item->id)
                                  ->where('goods_receipt_id', $cart->inventory->goods_receipt->id)
                                  ->where('status', OrderStatus::ACTIVE)
                                  ->latest()
                                  ->first()
                                  ->price ?? 0;

            $db_cart = Cart::find($cart->id);
            $db_inventory = Inventory::find($cart->inventory->id);

            $data['so_number'] = 'BFG-' . str_pad($so_count, 8, '0', STR_PAD_LEFT);
            $data['cart_id'] = $cart->id;
            $data['inventory_id'] = $cart->inventory->id;
            $data['item_id'] = $cart->inventory->item->id;
            $data['price'] = $cart->price;
            $data['cost'] = $landing_price;
            $data['qty'] = $cart->qty;
            $data['total'] = $cart->total;
            $data['real_total'] = $cart->total;
            $data['branch_id'] = auth()->user()->branch->id;
            $data['mop'] = $request->mop;
            $data['is_credit'] = 0; // not credit
            $data['is_pos_transaction'] = 1;
            $data['delivered_date'] = Carbon::now();
            $data['user_id'] = $request->user_id;
            $data['authorized_user_id'] = auth()->user()->id;
            $data['status'] = PaymentStatus::DELIVERED; // if you want to insert to a specific column
            Payment::create($data); // create data in a model

            // get the current inventory and deduct qty
            $db_inventory->qty -= $cart->qty;
            $db_inventory->save();

            // get cart and mark it as checked out
            $db_cart->status = CartStatus::CHECKED_OUT;
            $db_cart->save();

            // get the payment after creating it
            $payment = Payment::where('user_id', $request->user_id)
                            ->where('authorized_user_id', auth()->user()->id)
                            ->where('status', PaymentStatus::DELIVERED)
                            ->latest()
                            ->first();

            // mark all the related serial numbers of that item as sold
            DB::table('item_serial_numbers')
                ->where('cart_id', $cart->id)
                ->where('item_id', $cart->item->id)
                ->where('status', ItemSerialNumberStatus::FOR_CHECKOUT)
                ->update([
                    'payment_id' => $payment->id,
                    'status' => ItemSerialNumberStatus::SOLD,
                ]);
        }

        /* get the total of the newly created receipt */
        $payments_total = Payment::where('so_number', 'BFG-' . str_pad($so_count, 8, '0', STR_PAD_LEFT))
                                ->sum('total');

        /* save to payment receipt */
        $receipt_data['so_number'] = 'BFG-' . str_pad($so_count, 8, '0', STR_PAD_LEFT);
        $receipt_data['user_id'] = $request->user_id;
        $receipt_data['authorized_user_id'] = auth()->user()->id;
        $receipt_data['branch_id'] = auth()->user()->branch->id;
        $receipt_data['mop'] = $request->mop;
        $receipt_data['discount'] = $request->discount;
        $receipt_data['vat'] = $request->vat;
        $receipt_data['total'] = $payments_total;
        $receipt_data['real_total'] = $payments_total;
        $receipt_data['note'] = $request->note;
        $receipt_data['status'] = PaymentReceiptStatus::DELIVERED;
        PaymentReceipt::create($receipt_data); // create data in a model

        /* get created payment receipt data */
        $payment_receipt = PaymentReceipt::where('so_number', 'BFG-' . str_pad($so_count, 8, '0', STR_PAD_LEFT))
                                        ->latest()
                                        ->first();

        DB::table('item_serial_numbers')
                ->where('payment_id', $payment->id)
                ->where('status', ItemSerialNumberStatus::SOLD)
                ->update([
                    'payment_receipt_id' => $payment_receipt->id,
                ]);

        // $payment->payment_receipt_id = $payment_receipt->id;
        // $payment->save();

        DB::table('payments')
        ->where('so_number', 'BFG-' . str_pad($so_count, 8, '0', STR_PAD_LEFT))
        ->update([
            'payment_receipt_id' => $payment_receipt->id,
        ]);

        /* if mode of payment chosen is credit */
        if ($request->mop == 'credit') {
            /* save the credit */
            $pos_data['so_number'] = 'BFG-' . str_pad($so_count, 8, '0', STR_PAD_LEFT);
            $pos_data['price'] = 0; // initial downpayment
            $pos_data['days_due'] = 30; // default days due is 30 days
            $pos_data['interest'] = 0; // default interest value is 0
            $pos_data['payment_receipt_id'] = $payment_receipt->id;
            $pos_data['payment_id'] = $payment->id;
            $pos_data['status'] = PaymentCreditStatus::CREDIT;
            PaymentCredit::create($pos_data); // create data in a model

            /* get created payment credit data */
            $payment_credit = PaymentCredit::where('so_number', 'BFG-' . str_pad($so_count, 8, '0', STR_PAD_LEFT))
                                        ->latest()
                                        ->first();

            /* assign as credit for payment receipt */
            $receipt_data['is_credit'] = 1; // credit
        }

        /* save the discount */
        $pos_data['so_number'] = 'BFG-' . str_pad($so_count, 8, '0', STR_PAD_LEFT);
        $pos_data['price'] = $request->discount;
        $pos_data['payment_receipt_id'] = $payment_receipt->id;
        $pos_data['cart_id'] = $db_cart->id;
        $pos_data['payment_id'] = $payment->id;
        $pos_data['status'] = POSDiscountStatus::ACTIVE;
        POSDiscount::create($pos_data); // create data in a model

        /* save the vat */
        $pos_data['so_number'] = 'BFG-' . str_pad($so_count, 8, '0', STR_PAD_LEFT);
        $pos_data['price'] = $request->vat;
        $pos_data['payment_receipt_id'] = $payment_receipt->id;
        $pos_data['cart_id'] = $db_cart->id;
        $pos_data['payment_id'] = $payment->id;
        $pos_data['status'] = POSVatStatus::ACTIVE;
        POSVat::create($pos_data); // create data in a model

        return redirect()->route('accounting.payments.view', [$payment->so_number]);
    }

    public function credit(Request $request)
    {
        $rules = [
            'mop' => 'required',
            'price' => 'required',
            'interest' => 'nullable',
            'days_due' => 'required',
            'discount' => 'required',
            'image' => 'nullable|image',
            'vat' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = []; // declare as an array
        $pos_data = []; // declare as an array
        $receipt_data = []; // declare as an array

        $carts = Cart::where('user_id', $request->user_id)
                ->where('authorized_user_id', auth()->user()->id)
                ->where('status', CartStatus::ACTIVE)
                ->get();

        $so_count = str_replace('BFG-', '', Payment::orderBy('created_at', 'desc')->first()->so_number) + 1; // get the latest po sequence then add 1
        // return $so_count;

        foreach ($carts as $cart) {
            /* get the current costing of the item */
            $landing_price = Order::where('item_id', $cart->item->id)
                                  ->where('goods_receipt_id', $cart->inventory->goods_receipt->id)
                                  ->where('status', OrderStatus::ACTIVE)
                                  ->latest()
                                  ->first()
                                  ->price ?? 0;

            $db_cart = Cart::find($cart->id);
            $db_inventory = Inventory::find($cart->inventory->id);

            $data['so_number'] = 'BFG-' . str_pad($so_count, 8, '0', STR_PAD_LEFT);
            $data['cart_id'] = $cart->id;
            $data['inventory_id'] = $cart->inventory->id;
            $data['item_id'] = $cart->inventory->item->id;
            $data['price'] = $cart->price;
            $data['qty'] = $cart->qty;
            $data['total'] = $cart->total;
            $data['real_total'] = $cart->total;
            $data['branch_id'] = auth()->user()->branch->id;
            $data['mop'] = $request->mop;
            $data['cost'] = $landing_price;
            $data['is_credit'] = 1; // credit
            $data['is_pos_transaction'] = 1;
            $data['delivered_date'] = Carbon::now();
            $data['user_id'] = $request->user_id;
            $data['authorized_user_id'] = auth()->user()->id;
            $data['status'] = PaymentStatus::DELIVERED; // if you want to insert to a specific column
            Payment::create($data); // create data in a model

            // get the current inventory and deduct qty
            $db_inventory->qty -= $cart->qty;
            $db_inventory->save();

            // get cart and mark it as checked out
            $db_cart->status = CartStatus::CHECKED_OUT;
            $db_cart->save();

            // get the payment after creating it
            $payment = Payment::where('user_id', $request->user_id)
                            ->where('authorized_user_id', auth()->user()->id)
                            ->where('status', PaymentStatus::DELIVERED)
                            ->latest()
                            ->first();

            // mark all the related serial numbers of that item as sold
            DB::table('item_serial_numbers')
                ->where('cart_id', $cart->id)
                ->where('item_id', $cart->item->id)
                ->where('status', ItemSerialNumberStatus::FOR_CHECKOUT)
                ->update([
                    'payment_id' => $payment->id,
                    'status' => ItemSerialNumberStatus::SOLD,
                ]);
        }

        /* get the total of the newly created receipt */
        $payments_total = Payment::where('so_number', 'BFG-' . str_pad($so_count, 8, '0', STR_PAD_LEFT))
                                ->sum('total');

        /* save to payment receipt */
        $receipt_data['so_number'] = 'BFG-' . str_pad($so_count, 8, '0', STR_PAD_LEFT);
        $receipt_data['user_id'] = $request->user_id;
        $receipt_data['authorized_user_id'] = auth()->user()->id;
        $receipt_data['branch_id'] = auth()->user()->branch->id;
        $receipt_data['is_credit'] = 1;
        $receipt_data['mop'] = $request->mop;
        $receipt_data['discount'] = $request->discount;
        $receipt_data['vat'] = $request->vat;
        $receipt_data['total'] = $payments_total;
        $receipt_data['real_total'] = $payments_total;
        $receipt_data['note'] = $request->note;
        $receipt_data['status'] = PaymentReceiptStatus::DELIVERED;
        PaymentReceipt::create($receipt_data); // create data in a model

        /* get created payment receipt data */
        $payment_receipt = PaymentReceipt::where('so_number', 'BFG-' . str_pad($so_count, 8, '0', STR_PAD_LEFT))
                                        ->latest()
                                        ->first();

        DB::table('item_serial_numbers')
                ->where('payment_id', $payment->id)
                ->where('status', ItemSerialNumberStatus::SOLD)
                ->update([
                    'payment_receipt_id' => $payment_receipt->id,
                ]);

        // $payment->payment_receipt_id = $payment_receipt->id;
        // $payment->save();

        DB::table('payments')
        ->where('so_number', 'BFG-' . str_pad($so_count, 8, '0', STR_PAD_LEFT))
        ->update([
            'payment_receipt_id' => $payment_receipt->id,
        ]);

        /* save the credit */
        $pos_data['so_number'] = 'BFG-' . str_pad($so_count, 8, '0', STR_PAD_LEFT);
        $pos_data['price'] = 0; // initial downpayment
        $pos_data['days_due'] = $request->days_due;
        $pos_data['interest'] = $request->interest;
        $pos_data['payment_id'] = $payment->id;
        $pos_data['payment_receipt_id'] = $payment_receipt->id;
        if ($request->mop == 'credit') {
            // $pos_data['status'] = PaymentCreditStatus::CREDIT;
            $pos_data['status'] = PaymentCreditStatus::UNPAID;
        } else {
            $pos_data['status'] = PaymentCreditStatus::UNPAID;
        }

        PaymentCredit::create($pos_data); // create data in a model

        /* get created payment credit data */
        $payment_credit = PaymentCredit::where('so_number', 'BFG-' . str_pad($so_count, 8, '0', STR_PAD_LEFT))
                                    ->latest()
                                    ->first();

        /* if has initial downpayment */
        if ($request->price > 0) {
            /* add credit history */
            $pos_data['so_number'] = 'BFG-' . str_pad($so_count, 8, '0', STR_PAD_LEFT);
            $pos_data['mop'] = $request->mop;
            $pos_data['term'] = 1; // first payment
            $pos_data['price'] = $request->price; // initial downpayment
            $pos_data['payment_credit_id'] = $payment_credit->id;

            if ($request->file('image')) { // if the file is present
                $image_name = 'BFG-' . str_pad($so_count, 8, '0', STR_PAD_LEFT) . '-' . time() . '.' . $request->file('image')->getClientOriginalExtension(); // set unique name for that file
                $request->file('image')->move('uploads/images/payment-credits', $image_name); // move the file to the laravel project
                $pos_data['image'] = 'uploads/images/payment-credits/' . $image_name; // save the destination of the file to the database
            }

            $pos_data['status'] = PaymentCreditRecordStatus::PENDING;
            PaymentCreditRecord::create($pos_data); // create data in a model
        }

        /* save the discount */
        $pos_data['so_number'] = 'BFG-' . str_pad($so_count, 8, '0', STR_PAD_LEFT);
        $pos_data['price'] = $request->discount;
        $pos_data['cart_id'] = $db_cart->id;
        $pos_data['payment_id'] = $payment->id;
        $pos_data['payment_receipt_id'] = $payment_receipt->id;
        $pos_data['status'] = POSDiscountStatus::ACTIVE;
        POSDiscount::create($pos_data); // create data in a model

        /* save the vat */
        $pos_data['so_number'] = 'BFG-' . str_pad($so_count, 8, '0', STR_PAD_LEFT);
        $pos_data['price'] = $request->vat;
        $pos_data['cart_id'] = $db_cart->id;
        $pos_data['payment_id'] = $payment->id;
        $pos_data['payment_receipt_id'] = $payment_receipt->id;
        $pos_data['status'] = POSVatStatus::ACTIVE;
        POSVat::create($pos_data); // create data in a model

        return redirect()->route('accounting.payments.view', [$payment->so_number]);
    }

    public function qty(Request $request)
    {
        $rules = [
            'qty' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = $request->all();

        $cart = Cart::find($request->cart_id);

        $inventory = Inventory::where('item_id', $cart->item->id)
                            ->latest()
                            ->first();

        $cart_item_total = Cart::where('item_id', $inventory->item->id)
                            ->where('user_id', $cart->user_id)
                            ->where('authorized_user_id', auth()->user()->id)
                            ->where('status', CartStatus::ACTIVE)
                            ->latest()
                            ->sum('qty');

        /* if the item from inventory does not have anymore stocks */
        if ($inventory->qty < $request->qty) {
            $request->session()->flash('error', 'This item do not have enough stock/s in your inventory');
            return back();
        }

        $data['total'] = ($request->qty * $cart->inventory->price); // proceed with calculations with discount
        $cart->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');

        return back();
    }
}
