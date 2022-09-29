<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Validator;
use App\Models\Item;
use App\Models\Order;
use App\Models\Supply;
use App\Models\Inventory;
use App\Models\ItemStatus;
use App\Models\OrderStatus;
use App\Models\SupplyStatus;
use App\Models\GoodsReceipt;
use App\Models\PurchaseOrder;
use App\Models\InventoryStatus;
use App\Models\GoodsReceiptStatus;
use App\Models\PurchaseOrderStatus;
use App\Models\InventoryReturnRecord;
use App\Models\InventoryReceiveRecord;

class OrderController extends Controller
{
    public function masterlist(Request $request, $purchase_order_id)
    {
        $purchase_order = PurchaseOrder::find($purchase_order_id);
        $orders = Order::where('purchase_order_id', $purchase_order->id)
                    ->where('status', OrderStatus::ACTIVE)
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);

        return view('admin.purchase_orders.orders.masterlist', compact(
            'purchase_order',
            'orders'
        ));
    }

    public function search(Request $request)
    {
        $name = $request->name ?? '*';

        return redirect()->route('internals.orders.filter', [$request->purchase_order_id, $name])->withInput();
    }

    public function filter($purchase_order_id, $name)
    {
        $purchase_order = PurchaseOrder::find($purchase_order_id);

        $query = Supply::leftJoin('items', 'supplies.item_id', '=', 'items.id')
                    ->leftJoin('brands', 'items.brand_id', '=', 'brands.id')
                    ->select('items.*', 'brands.*', 'supplies.*')
                    ->where('supplies.supplier_id', $purchase_order->supplier->id)
                    ->where('supplies.status', SupplyStatus::ACTIVE)
                    ->orderBy('supplies.created_at', 'desc');

        if ($name != '*') {
            $query->where('items.name', 'LIKE', '%' . $name . '%');
            $query->orWhere('brands.name', 'LIKE', '%' . $name . '%');
        }

        $supplies = $query->paginate(15);

        $current_orders = Order::where('purchase_order_id', $purchase_order->id)
                    ->where('status', OrderStatus::ACTIVE)
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);

        return view('admin.purchase_orders.manage', compact(
            'current_orders',
            'purchase_order',
            'supplies'
        ));
    }

    public function create(Request $request)
    {
        $rules = [
            'price' => 'required',
            'discount' => 'nullable|min:0',
            'qty' => 'required|min:1',
            'free_qty' => 'nullable|min:0',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = request()->all(); // get all request
        $data['total'] = ($request->qty * $request->price) - $request->discount; // proceed with calculations with discount
        $data['status'] = OrderStatus::ACTIVE; // if you want to insert to a specific column
        Order::create($data); // create data in a model

        $order = Order::where('purchase_order_id', $request->purchase_order_id)
                    ->where('supply_id', $request->supply_id)
                    ->where('item_id', $request->item_id)
                    ->latest()
                    ->first();

        /* save the edited price */
        $supply = Supply::find($order->supply_id);
        $supply->price = $request->price;
        $supply->save();

        /* log the activity */
        app('App\Http\Controllers\Log\OrderController')->create($request, $order->id);

        $request->session()->flash('success', 'Data has been added');

        return back()->withInput();
    }

    public function price(Request $request)
    {
        $rules = [
            'price' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = $request->all();

        $order = Order::find($request->order_id);
        $data['total'] = ($order->qty * $request->price) - $order->discount; // proceed with calculations with discount
        $order->fill($data)->save();

        /* save the edited price */
        $supply = Supply::find($order->supply_id);
        $supply->price = $request->price;
        $supply->save();

        /* log the activity */
        app('App\Http\Controllers\Log\OrderController')->price($request, $order->id);

        $request->session()->flash('success', 'Data has been updated');

        return back();
    }

    public function discount(Request $request)
    {
        $rules = [
            'discount' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = $request->all();

        $order = Order::find($request->order_id);
        $data['total'] = ($order->qty * $order->price) - $request->discount; // proceed with calculations with discount
        $order->fill($data)->save();

        /* log the activity */
        app('App\Http\Controllers\Log\OrderController')->discount($request, $order->id);

        $request->session()->flash('success', 'Data has been updated');

        return back();
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

        $order = Order::find($request->order_id);
        $data['total'] = ($request->qty * $order->price) - $order->discount; // proceed with calculations with discount
        $order->fill($data)->save();

        /* log the activity */
        app('App\Http\Controllers\Log\OrderController')->qty($request, $order->id);

        $request->session()->flash('success', 'Data has been updated');

        return back();
    }

    public function freeQty(Request $request)
    {
        $rules = [
            'free_qty' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = $request->all();

        $order = Order::find($request->order_id);
        $order->fill($data)->save();

        /* log the activity */
        app('App\Http\Controllers\Log\OrderController')->freeQty($request, $order->id);

        $request->session()->flash('success', 'Data has been updated');

        return back();
    }

    public function receive(Request $request)
    {
        $rules = [
            'received_qty' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = $request->all();

        $order = Order::find($request->order_id);
        $data['performed_by_user_id'] = auth()->user()->id;
        $data['received_qty'] = $order->received_qty + $request->received_qty;
        $order->fill($data)->save();

        $item = Item::find($order->item_id);
        $item->fill($data)->save();

        // check item on the inventory if it exists
        if (Inventory::where('item_id', $order->item->id)
            ->where('company_id', $order->goods_receipt->purchase_order->company->id)
            ->where('status', InventoryStatus::ACTIVE)
            ->exists()) {
            // find the existing inventory data
            $existing_inventory = Inventory::where('item_id', $order->item->id)
                                        ->where('company_id', $order->goods_receipt->purchase_order->company->id)
                                        ->where('status', InventoryStatus::ACTIVE)
                                        ->first();
            $existing_inventory->goods_receipt_id = $order->goods_receipt->id;
            $existing_inventory->company_id = $order->goods_receipt->purchase_order->company->id;
            $existing_inventory->qty += $request->received_qty;
            $existing_inventory->save();

            /* record the qty for inventory */
            $data['inventory_id'] = $existing_inventory->id;
            $data['goods_receipt_id'] = $order->goods_receipt_id;
            $data['user_id'] = auth()->user()->id;
            $data['free_qty'] = $order->free_qty;
            $data['qty'] = $request->received_qty;
            $data['price'] = $order->price;
            $data['discount'] = $order->discount;
            $data['total'] = $order->total;
            InventoryReceiveRecord::create($data);
        } else {
            $inventory = new Inventory;
            $inventory->goods_receipt_id = $order->goods_receipt->id;
            $inventory->company_id = $order->goods_receipt->purchase_order->company->id;
            $inventory->item_id = $order->item->id;
            $inventory->qty = $request->received_qty;
            $inventory->status = InventoryStatus::ACTIVE;
            $inventory->save();

            /* record the qty for inventory */
            $data['inventory_id'] = $inventory->id;
            $data['goods_receipt_id'] = $order->goods_receipt_id;
            $data['user_id'] = auth()->user()->id;
            $data['free_qty'] = $order->free_qty;
            $data['qty'] = $request->received_qty;
            $data['price'] = $order->price;
            $data['discount'] = $order->discount;
            $data['total'] = $order->total;
            InventoryReceiveRecord::create($data);
        }

        $request->session()->flash('success', 'Data has been updated');

        return back();
    }

    public function return(Request $request)
    {
        $rules = [
            'returning_qty' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = $request->all();

        $order = Order::find($request->order_id);
        $data['performed_by_user_id'] = auth()->user()->id;
        $data['received_qty'] = $order->received_qty - $request->returning_qty;
        $order->fill($data)->save();

        // check item on the inventory if it exists
        if (Inventory::where('item_id', $order->item->id)
            ->where('company_id', $order->goods_receipt->purchase_order->company->id)
            ->where('status', InventoryStatus::ACTIVE)
            ->exists()) {
            // find the existing inventory data
            $existing_inventory = Inventory::where('item_id', $order->item->id)
                                        ->where('company_id', $order->goods_receipt->purchase_order->company->id)
                                        ->where('status', InventoryStatus::ACTIVE)
                                        ->first();

            $existing_inventory->goods_receipt_id = $order->goods_receipt->id;
            $existing_inventory->company_id = $order->goods_receipt->purchase_order->company->id;
            $existing_inventory->qty -= $request->returning_qty;
            $existing_inventory->save();

            /* record the qty for inventory */
            $data['inventory_id'] = $existing_inventory->id;
            $data['goods_receipt_id'] = $order->goods_receipt_id;
            $data['user_id'] = auth()->user()->id;
            $data['qty'] = $request->returning_qty;
            $data['price'] = $order->price;
            $data['discount'] = $order->discount;
            $data['total'] = $order->price * $request->returning_qty;
            InventoryReturnRecord::create($data);
        } else {
            $inventory = new Inventory;
            $inventory->goods_receipt_id = $order->goods_receipt->id;
            $inventory->company_id = $order->goods_receipt->purchase_order->company->id;
            $inventory->item_id = $order->item->id;
            $inventory->qty = $request->returning_qty;
            $inventory->status = InventoryStatus::ACTIVE;
            $inventory->save();

            /* record the qty for inventory */
            $data['inventory_id'] = $inventory->id;
            $data['goods_receipt_id'] = $order->goods_receipt_id;
            $data['user_id'] = auth()->user()->id;
            $data['qty'] = $request->returning_qty;
            $data['price'] = $order->price;
            $data['discount'] = $order->discount;
            $data['total'] = $order->price * $request->returning_qty;
            InventoryReturnRecord::create($data);
        }

        $request->session()->flash('success', 'Data has been updated');

        return back();
    }

    public function delete(Request $request, $purchase_order_id, $order_id)
    {
        $order = Order::find($order_id);
        $order->status = OrderStatus::INACTIVE;
        $order->save();

        /* log the activity */
        app('App\Http\Controllers\Log\OrderController')->delete($request, $order->id);

        $request->session()->flash('success', 'Data has been deleted');

        return back();
    }
}
