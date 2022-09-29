<?php

namespace App\Http\Controllers\Front\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;
use App\Models\Wishlist;
use App\Models\Inventory;
use App\Models\WishlistStatus;
use App\Models\InventoryStatus;

class WishlistController extends Controller
{
    public function show()
    {
        $wishlists = Wishlist::where('user_id', auth()->user()->id)
                        ->where('status', WishlistStatus::ACTIVE)
                        ->orderBy('created_at', 'desc')
                        ->paginate(20);

        return view('front.shop.wishlists.show', compact(
            'wishlists'
        ));
    }

    public function item(Request $request, $item_id)
    {
        $data = request()->all(); // get all request

        $data['user_id'] = auth()->user()->id;
        $data['item_id'] = $item_id;
        $data['status'] = WishlistStatus::ACTIVE; // if you want to insert to a specific column
        Wishlist::create($data); // create data in a model

        $request->session()->flash('success', 'Data has been listed to wishlist');

        return back();
    }

    public function package(Request $request, $package_id)
    {
        $data = request()->all(); // get all request

        $data['user_id'] = auth()->user()->id;
        $data['package_id'] = $package_id;
        $data['status'] = WishlistStatus::ACTIVE; // if you want to insert to a specific column
        Wishlist::create($data); // create data in a model

        $request->session()->flash('success', 'Data has been listed to wishlist');

        return back();
    }

    public function delete(Request $request, $wishlist_id)
    {
        $wishlist = Wishlist::find($wishlist_id);
        $wishlist->status = WishlistStatus::INACTIVE; // mark data as inactive
        $wishlist->save();

        $request->session()->flash('success', 'Data has been deleted');

        return back();
    }
}
