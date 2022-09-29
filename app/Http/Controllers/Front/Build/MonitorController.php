<?php

namespace App\Http\Controllers\Front\Build;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\BuildItem;
use App\Models\SubCategory;
use App\Models\CategoryStatus;
use App\Models\InventoryStatus;
use App\Models\BuildItemStatus;
use App\Models\SubCategoryStatus;

class MonitorController extends Controller
{
    public function show(Request $request)
    {
        $inventories = Inventory::leftJoin('items', 'inventories.item_id', 'items.id')
                            ->leftJoin('sub_categories', 'items.sub_category_id', 'sub_categories.id')
                            ->select('inventories.*')
                            ->where('sub_categories.id', SubCategory::MONITOR)
                            ->where('inventories.status', InventoryStatus::ACTIVE)
                            ->where('inventories.price', '>', 0)
                            ->orderBy('inventories.views', 'asc')
                            ->paginate(12);

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

        return view('front.build.monitor', compact(
            'build_items_total',
            'build_items',
            'inventories'
        ));
    }

    public function search(Request $request)
    {
        $name = $request->name ?? '*';
        $brand_id = $request->brand_id ?? '*';
        $category_id = $request->category_id ?? '*';
        $sub_category_id = $request->sub_category_id ?? '*';

        return redirect()->route('build.monitor.filter', [$name, $brand_id, $category_id, $sub_category_id])->withInput();
    }

    public function filter(Request $request, $name, $status, $category_id, $sub_category_id)
    {
        $query = Inventory::leftJoin('items', 'inventories.item_id', 'items.id')
                        ->leftJoin('brands', 'items.brand_id', 'brands.id')
                        ->leftJoin('sub_categories', 'items.sub_category_id', 'sub_categories.id')
                        ->select('inventories.*')
                        ->where('sub_categories.id', SubCategory::MONITOR)
                        ->where('inventories.status', InventoryStatus::ACTIVE)
                        ->where('inventories.price', '>', 0)
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

        $inventories = $query->paginate(15);
        
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

        return view('front.build.monitor', compact(
            'build_items_total',
            'build_items',
            'inventories'
        ));
    }

    public function create(Request $request, $inventory_id)
    {
        $inventory = Inventory::find($inventory_id);

        $data = []; // set variable as an array
        $data['ip_address'] = $request->getClientIp();
        $data['inventory_id'] = $inventory->id;
        $data['price'] = $inventory->price;
        $data['discount'] = $inventory->discount;
        $data['qty'] = 1;
        $data['total'] = $inventory->price * 1;
        $data['user_id'] = auth()->user()->id ?? 0;
        $data['status'] = BuildItemStatus::ACTIVE;
        BuildItem::create($data); // create data in a model

        return redirect()->route('build.keyboard');
    }
}
