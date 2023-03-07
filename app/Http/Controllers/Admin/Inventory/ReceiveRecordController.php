<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Validator;
use App\Models\Order;
use App\Models\Branch;
use App\Models\Inventory;
use App\Models\OrderStatus;
use App\Models\InventoryStatus;
use App\Models\InventoryReceiveRecord;
use App\Models\InventoryReceiveRecordStatus;

class ReceiveRecordController extends Controller
{
    public function masterlist(Request $request, $branch_id, $inventory_id)
    {
        $branch = Branch::find($branch_id);
        $inventory = Inventory::find($inventory_id);
        $orders = Order::where('item_id', $inventory->item->id)
                    ->where('status', OrderStatus::ACTIVE)
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);

        return view('admin.inventories.landing-prices.masterlist', compact(
            'inventory',
            'branch',
            'orders'
        ));
    }

    public function create(Request $request)
    {
        $rules = [
            'item_id' => 'required',
            'price' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return back()->withInput()->withErrors($validator);

        $inventory = Inventory::find($request->inventory_id);

        $data = request()->all(); // get all request
        $data['purchase_order_id'] = 0;
        $data['supply_id'] = 0;
        $data['total'] = 0;
        $data['performed_by_user_id'] = auth()->user()->id;
        $data['status'] = OrderStatus::ACTIVE;
        Order::create($data);

        $request->session()->flash('success', 'Data has been added');
        return back();
    }
}
