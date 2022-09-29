<?php

namespace App\Http\Controllers\Front\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Cart;
use App\Models\Inventory;
use App\Models\CartStatus;
use App\Models\InventoryStatus;

class CartController extends Controller
{
    public function show()
    {
        $carts = Cart::where('user_id', auth()->user()->id)
                    ->where('status', CartStatus::ACTIVE)
                    ->orderBy('created_at', 'desc')
                    ->paginate(20);

        $carts_total = Cart::where('user_id', auth()->user()->id)
                    ->where('status', CartStatus::ACTIVE)
                    ->orderBy('created_at', 'desc')
                    ->sum('total');

        return view('front.shop.carts.show', compact(
            'carts',
            'carts_total'
        ));
    }

    public function checkout()
    {
        $carts = Cart::where('user_id', auth()->user()->id)
                    ->where('status', CartStatus::ACTIVE)
                    ->orderBy('created_at', 'desc')
                    ->paginate(20);

        $carts_total = Cart::where('user_id', auth()->user()->id)
                    ->where('status', CartStatus::ACTIVE)
                    ->orderBy('created_at', 'desc')
                    ->sum('total');

        return view('front.shop.carts.checkout', compact(
            'carts',
            'carts_total'
        ));
    }

    public function create(Request $request)
    {
        $inventory = Inventory::find($request->inventory_id);

        $data = request()->all(); // get all request

        $data['user_id'] = auth()->user()->id;
        $data['price'] = $inventory->price;
        $data['total'] = $inventory->price * $request->qty;
        $data['status'] = CartStatus::ACTIVE; // if you want to insert to a specific column
        Cart::create($data); // create data in a model

        $request->session()->flash('success', 'Data has been added');

        return redirect()->route('shop.carts');
    }

    public function delete(Request $request, $cart_id)
    {
        $cart = Cart::find($cart_id);
        $cart->status = CartStatus::INACTIVE; // mark data as inactive
        $cart->save();

        $request->session()->flash('success', 'Data has been deleted');

        return back();
    }
}
