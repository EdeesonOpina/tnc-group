<?php

namespace App\Http\Controllers\Front\Build;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\BuildItem;
use App\Models\CartStatus;
use App\Models\SubCategory;
use App\Models\CategoryStatus;
use App\Models\InventoryStatus;
use App\Models\BuildItemStatus;
use App\Models\SubCategoryStatus;

class BuildController extends Controller
{
    public function show()
    {
        // return view('errors.dev');
        return redirect()->route('build.motherboard');
    }

    public function delete(Request $request, $build_item_id)
    {
        $build_item = BuildItem::find($build_item_id);
        $build_item->status = BuildItemStatus::INACTIVE;
        $build_item->save();

        $request->session()->flash('success', 'Data has been deleted');

        return back();
    }

    public function create(Request $request)
    {
        $build_items = BuildItem::where('status', BuildItemStatus::ACTIVE)
                        ->where('user_id', auth()->user()->id)
                        ->get();

        $build_items_total = BuildItem::where('status', BuildItemStatus::ACTIVE)
                                    ->where('user_id', auth()->user()->id)
                                    ->sum('total');

        foreach ($build_items as $build_item) {
            $db_build_item = BuildItem::find($build_item->id);
            $inventory = Inventory::find($build_item->inventory_id);

            $data = [];

            $data['inventory_id'] = $inventory->id;
            $data['user_id'] = auth()->user()->id;
            $data['price'] = $build_item->price;
            $data['qty'] = $build_item->qty;
            $data['total'] = $build_item->price * $build_item->qty;
            $data['status'] = CartStatus::ACTIVE;
            Cart::create($data); // create data in a model

            $db_build_item->status = BuildItemStatus::INACTIVE;
            $db_build_item->save();
        }

        return redirect()->route('shop.carts');
    }

    public function result(Request $request)
    {
        if (auth()->check()) { /* if the user is logged */
            $build_items = BuildItem::where('status', BuildItemStatus::ACTIVE)
                            ->where('user_id', auth()->user()->id)
                            ->get();

            $build_items_total = BuildItem::where('status', BuildItemStatus::ACTIVE)
                                        ->where('user_id', auth()->user()->id)
                                        ->sum('total');
        } else { /* if the user is just a guest */
            $build_items = BuildItem::where('status', BuildItemStatus::ACTIVE)
                            ->where('ip_address', $request->getClientIp())
                            ->get();

            $build_items_total = BuildItem::where('status', BuildItemStatus::ACTIVE)
                                        ->where('ip_address', $request->getClientIp())
                                        ->sum('total');
        }

        return view('front.build.result', compact(
            'build_items_total',
            'build_items'
        ));
    }

    public function inquire(Request $request)
    {
        if (auth()->check()) { /* if the user is logged */
            $build_items = BuildItem::where('status', BuildItemStatus::ACTIVE)
                            ->where('user_id', auth()->user()->id)
                            ->get();

            $build_items_total = BuildItem::where('status', BuildItemStatus::ACTIVE)
                                        ->where('user_id', auth()->user()->id)
                                        ->sum('total');
        } else { /* if the user is just a guest */
            $build_items = BuildItem::where('status', BuildItemStatus::ACTIVE)
                            ->where('ip_address', $request->getClientIp())
                            ->get();

            $build_items_total = BuildItem::where('status', BuildItemStatus::ACTIVE)
                                        ->where('ip_address', $request->getClientIp())
                                        ->sum('total');
        }

        return view('front.build.inquire', compact(
            'build_items_total',
            'build_items'
        ));
    }
}
