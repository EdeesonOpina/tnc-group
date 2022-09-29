<?php

namespace App\Http\Controllers\Front\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Validator;
use App\Models\User;
use App\Models\Cart;
use App\Models\Review;
use App\Models\Branch;
use App\Models\Payment;
use App\Models\Inventory;
use App\Models\CartStatus;
use App\Models\ReviewStatus;
use App\Models\PaymentStatus;
use App\Models\InventoryStatus;

class ReviewController extends Controller
{
    public function add($inventory_id)
    {
        $inventory = Inventory::find($inventory_id);

        $reviews = Review::where('inventory_id', $inventory->id)
                        ->orderBy('created_at', 'desc')
                        ->where('status', ReviewStatus::ACTIVE)
                        ->paginate(6);

        return view('front.shop.items.reviews.add', compact(
            'reviews',
            'inventory'
        ));
    }

    public function create(Request $request)
    {
        $rules = [
            'value' => 'required',
            'title' => 'required',
            'description' => 'required',
            'image' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);

        $inventory = Inventory::find($request->inventory_id);

        if ($validator->fails()) 
            return back()->withInput()->withErrors($validator);

        $data = request()->all(); // get all request

        $data['user_id'] = auth()->user()->id;
        $data['inventory_id'] = $inventory->id;
        $data['item_id'] = $inventory->item->id;
        $data['status'] = ReviewStatus::ACTIVE; // if you want to insert to a specific column
        Review::create($data); // create data in a model

        $request->session()->flash('success', 'Data has been added');

        return redirect()->route('shop.items.view', [$inventory->id]);
    }
}
