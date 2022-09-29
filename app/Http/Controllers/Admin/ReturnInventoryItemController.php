<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Validator;
use App\Models\Item;
use App\Models\ReturnInventoryItem;
use App\Models\Supply;
use App\Models\Inventory;
use App\Models\ItemStatus;
use App\Models\ReturnInventoryItemStatus;
use App\Models\SupplyStatus;
use App\Models\GoodsReceipt;
use App\Models\ReturnInventory;
use App\Models\InventoryStatus;
use App\Models\GoodsReceiptStatus;
use App\Models\ReturnInventoryStatus;
use App\Models\InventoryReturnRecord;
use App\Models\InventoryReceiveRecord;

class ReturnInventoryItemController extends Controller
{
    public function masterlist(Request $request, $return_inventory_id)
    {
        $return_inventory = ReturnInventory::find($return_inventory_id);
        $return_inventory_items = ReturnInventoryItem::where('return_inventory_id', $return_inventory->id)
                    ->where('status', ReturnInventoryItemStatus::ACTIVE)
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);

        return view('admin.return_inventories.inventories.masterlist', compact(
            'return_inventory',
            'return_inventory_items'
        ));
    }

    public function search(Request $request)
    {
        $name = $request->name ?? '*';

        return redirect()->route('internals.return-inventories.inventories.filter', [$request->return_inventory_id, $name])->withInput();
    }

    public function filter($return_inventory_id, $name)
    {
        $return_inventory = ReturnInventory::find($return_inventory_id);

        $query = Inventory::leftJoin('items', 'inventories.item_id', '=', 'items.id')
                    ->leftJoin('brands', 'items.brand_id', '=', 'brands.id')
                    ->select('inventories.*')
                    ->where('inventories.qty', '>', 0)
                    ->where('inventories.status', InventoryStatus::ACTIVE)
                    ->orderBy('inventories.created_at', 'desc');

        if ($name != '*') {
            $query->where('items.name', 'LIKE', '%' . $name . '%');
            $query->orWhere('brands.name', 'LIKE', '%' . $name . '%');
        }

        $inventories = $query->paginate(15);

        $return_inventory_items = ReturnInventoryItem::where('return_inventory_id', $return_inventory->id)
                    ->where('status', ReturnInventoryItemStatus::ACTIVE)
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);

        return view('admin.return_inventories.manage', compact(
            'return_inventory_items',
            'return_inventory',
            'inventories'
        ));
    }

    public function create(Request $request)
    {
        $rules = [
            'qty' => 'required|min:1',
            'note' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $inventory = Inventory::find($request->inventory_id);

        $data = request()->all(); // get all request
        $data['performed_by_user_id'] = auth()->user()->id;
        $data['replacement_item_id'] = $inventory->item->id;
        $data['replacement_qty'] = $request->qty;
        $data['status'] = ReturnInventoryItemStatus::ACTIVE; // if you want to insert to a specific column
        ReturnInventoryItem::create($data); // create data in a model

        $request->session()->flash('success', 'Data has been added');

        return back()->withInput();
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

        $return_inventory_item = ReturnInventoryItem::find($request->return_inventory_item_id);
        $return_inventory_item->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');

        return back();
    }

    public function delete(Request $request, $return_inventory_id, $return_inventory_item_id)
    {
        $return_inventory_item = ReturnInventoryItem::find($return_inventory_item_id);
        $return_inventory_item->status = ReturnInventoryItemStatus::INACTIVE;
        $return_inventory_item->save();

        $request->session()->flash('success', 'Data has been deleted');

        return back();
    }
}
