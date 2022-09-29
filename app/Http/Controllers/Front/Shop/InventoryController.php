<?php

namespace App\Http\Controllers\Front\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Brand;
use App\Models\Review;
use App\Models\Inventory;
use App\Models\BrandStatus;
use App\Models\ReviewStatus;
use App\Models\InventoryStatus;
use App\Models\ItemPhoto;
use App\Models\ItemPhotoStatus;

class InventoryController extends Controller
{
    public function view($inventory_id)
    {
        $inventory = Inventory::find($inventory_id);
        /* increment the view */
        $inventory->views += 1;
        $inventory->save;

        $item_photos = ItemPhoto::where('item_id', $inventory->item->id)
                            ->where('status', ItemPhotoStatus::ACTIVE)
                            ->get();

        $reviews = Review::where('inventory_id', $inventory->id)
                        ->where('status', ReviewStatus::ACTIVE)
                        ->paginate(10);

        return view('front.shop.items.view', compact(
            'item_photos',
            'reviews',
            'inventory'
        ));
    }

    public function search(Request $request)
    {
        $name = $request->name ?? '*';
        $brand_id = $request->brand_id ?? '*';
        $category_id = $request->category_id ?? '*';
        $sub_category_id = $request->sub_category_id ?? '*';

        return redirect()->route('shop.filter', [$name, $brand_id, $category_id, $sub_category_id])->withInput();
    }

    public function filter($name, $status, $category_id, $sub_category_id)
    {
        $query = Inventory::leftJoin('items', 'inventories.item_id', '=', 'items.id')
                    ->leftJoin('brands', 'items.brand_id', '=', 'brands.id')
                    ->select('inventories.*')
                    ->where('inventories.price', '>', 0)
                    ->where('inventories.status', InventoryStatus::ACTIVE)
                    ->orderBy('inventories.created_at', 'desc');

        if ($name != '*') {
            $query->where('items.name', 'LIKE', '%' . $name . '%');
            $query->orWhere('brands.name', 'LIKE', '%' . $name . '%');
        }

        if ($category_id != '*') {
            $query->where('category_id', $category_id);
        }

        if ($sub_category_id != '*') {
            $query->where('sub_category_id', $sub_category_id);
        }

        $inventories = $query->paginate(16);

        return view('front.shop.show', compact(
            'inventories'
        ));
    }
}
