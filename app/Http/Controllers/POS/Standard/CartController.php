<?php

namespace App\Http\Controllers\POS\Standard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Auth;
use Mail;
use Validator;
use App\Models\Cart;
use App\Models\Item;
use App\Models\User;
use App\Models\Branch;
use App\Models\Account;
use App\Models\Payment;
use App\Models\Deduction;
use App\Models\Inventory;
use App\Models\UserStatus;
use App\Models\ItemStatus;
use App\Models\CartStatus;
use App\Models\BranchStatus;
use App\Models\AccountStatus;
use App\Models\PaymentStatus;
use App\Models\DeductionStatus;
use App\Models\InventoryStatus;
use App\Models\ItemSerialNumber;
use App\Models\ItemSerialNumberStatus;

class CartController extends Controller
{
    public function show($user_id)
    {
        $branch = Branch::find(Branch::MAIN_BRANCH); // branch of that pos
        $user = User::find($user_id); // the customer
        $cashier = User::find(auth()->user()->id); // the cashier in charge

        $carts = Cart::where('authorized_user_id', auth()->user()->id)
                    ->where('user_id', $user_id)
                    ->where('status', CartStatus::ACTIVE)
                    ->orderBy('created_at', 'desc')
                    ->get();

        $carts_total = Cart::where('authorized_user_id', auth()->user()->id)
                    ->where('user_id', $user_id)
                    ->where('status', CartStatus::ACTIVE)
                    ->orderBy('created_at', 'desc')
                    ->sum('total');

        return view('admin.pos.standard.show', compact(
            'cashier',
            'branch',
            'user',
            'carts_total',
            'carts'
        ));
    }

    public function scan(Request $request)
    {
        $rules = [
            'barcode' => 'required|exists:items',
            'serial_number' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $item = Item::where('barcode', $request->barcode)
                    ->first();

        $inventory = Inventory::where('item_id', $item->id)
                            ->latest()
                            ->first();

        $cart_item_total = Cart::where('item_id', $inventory->item->id)
                            ->where('user_id', $request->user_id)
                            ->where('authorized_user_id', auth()->user()->id)
                            ->where('status', CartStatus::ACTIVE)
                            ->latest()
                            ->sum('qty');

        /* if the item from inventory does not have anymore stocks */
        if ($inventory->qty <= $cart_item_total) {
            $request->session()->flash('error', 'This item do not have enough stock/s in your inventory');
            return back();
        }

        /* check if there is an available serial number for this item */
        if (! ItemSerialNumber::where('item_id', $item->id)
                    ->where('code', $request->serial_number)
                    ->where('status', ItemSerialNumberStatus::AVAILABLE)
                    ->first()) {
            $request->session()->flash('error', 'There is no available serial number for this item');
            return back();
        }

        $data = request()->all(); // get all request

        if (Cart::where('inventory_id', $inventory->id)
                ->where('item_id', $inventory->item->id)
                ->where('user_id', $request->user_id)
                ->where('authorized_user_id', auth()->user()->id)
                ->where('status', CartStatus::ACTIVE)
                ->exists()
        ) {
            $current_inventory = Inventory::find($inventory->id);
            $cart = Cart::where('inventory_id', $inventory->id)
                                    ->where('item_id', $inventory->item->id)
                                    ->where('user_id', $request->user_id)
                                    ->where('authorized_user_id', auth()->user()->id)
                                    ->where('status', CartStatus::ACTIVE)
                                    ->first();
            $data['qty'] = $cart->qty + 1;
            $data['total'] = ($inventory->price * $data['qty']);
            $cart->fill($data)->save();
        } else {
            $data['inventory_id'] = $inventory->id;
            $data['item_id'] = $inventory->item->id;
            $data['price'] = $inventory->price;
            $data['qty'] = 1;
            $data['total'] = ($inventory->price * 1);
            $data['user_id'] = $request->user_id;
            $data['authorized_user_id'] = auth()->user()->id;
            $data['status'] = CartStatus::ACTIVE; // if you want to insert to a specific column
            Cart::create($data); // create data in a model
        }

        /* tag the serial number as for checkout */
        $item_serial_number = ItemSerialNumber::where('item_id', $item->id)
                    ->where('code', $request->serial_number)
                    ->where('status', ItemSerialNumberStatus::AVAILABLE)
                    ->first();

        $cart = Cart::where('authorized_user_id', auth()->user()->id)
                    ->where('item_id', $item->id)
                    ->where('user_id', $request->user_id)
                    ->where('status', CartStatus::ACTIVE)
                    ->latest()
                    ->first();
        $item_serial_number->cart_id = $cart->id;
        $item_serial_number->status = ItemSerialNumberStatus::FOR_CHECKOUT;
        $item_serial_number->save();

        $request->session()->flash('success', 'Data has been added');

        return back();
    }

    public function delete(Request $request, $user_id, $cart_id)
    {
        $cart = Cart::find($cart_id);
        $cart->status = CartStatus::INACTIVE; // mark data as inactive
        $cart->save();

        DB::table('item_serial_numbers')
                ->where('item_id', $cart->item->id)
                ->where('status', ItemSerialNumberStatus::FOR_CHECKOUT)
                ->update([
                    'status' => ItemSerialNumberStatus::AVAILABLE,
                ]);

        $request->session()->flash('success', 'Data has been deleted');

        return back();
    }
}
