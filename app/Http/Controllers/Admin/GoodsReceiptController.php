<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Auth;
use Mail;
use Validator;
use App\Models\Order;
use App\Models\Branch;
use App\Models\Supply;
use App\Models\Supplier;
use App\Models\Inventory;
use App\Models\OrderStatus;
use App\Models\SupplyStatus;
use App\Models\BranchStatus;
use App\Models\GoodsReceipt;
use App\Models\PurchaseOrder;
use App\Models\SupplierStatus;
use App\Models\InventoryStatus;
use App\Models\DeliveryReceipt;
use App\Models\GoodsReceiptStatus;
use App\Models\PurchaseOrderStatus;
use App\Models\DeliveryReceiptStatus;
use App\Models\InventoryReturnRecord;
use App\Models\InventoryReceiveRecord;

class GoodsReceiptController extends Controller
{
    public function show()
    {
        $goods_receipts = GoodsReceipt::orderBy('created_at', 'desc')
                    ->paginate(15);

        return view('admin.goods_receipts.show', compact(
            'goods_receipts'
        ));
    }

    public function search(Request $request)
    {
        $reference_number = $request->reference_number ?? '*';
        $name = $request->name ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('internals.goods-receipts.filter', [$reference_number, $name, $status, $from_date, $to_date])->withInput();
    }

    public function filter($reference_number, $name, $status, $from_date, $to_date)
    {
        $query = GoodsReceipt::leftJoin('purchase_orders', 'goods_receipts.purchase_order_id', '=', 'purchase_orders.id')
                        ->leftJoin('suppliers', 'purchase_orders.supplier_id', '=', 'suppliers.id')
                        ->select('goods_receipts.*')
                        ->orderBy('goods_receipts.created_at', 'desc');

        if ($reference_number != '*') {
            $query->where('goods_receipts.reference_number', $reference_number);
        }

        if ($name != '*') {
            $query->where('suppliers.name', 'LIKE', '%' . $name . '%');
        }

        if ($status != '*') {
            $query->where('goods_receipts.status', $status);
        }

        /* date filter */
        // if they provided both from date and to date
        if ($from_date != '*' && $to_date != '*') {
            $query->whereBetween('goods_receipts.created_at', [$from_date . ' 00:00:00', $to_date . ' 23:59:59']);
        }
        /* date filter */

        $goods_receipts = $query->paginate(15);

        return view('admin.goods_receipts.show', compact(
            'goods_receipts'
        ));
    }

    public function create(Request $request)
    {
        $rules = [
            'purchase_order_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        // check if there is already a goods receipt with this purchase_order id to avoid duplication
        if (GoodsReceipt::where('purchase_order_id', $request->purchase_order_id)
            ->latest()
            ->exists()) {
            $existing_goods_receipt = GoodsReceipt::where('purchase_order_id', $request->purchase_order_id)
                                            ->latest()
                                            ->first();

            return redirect()->route('internals.goods-receipts.manage', [$existing_goods_receipt->id]);
        }

        $grpo_count = str_replace('GRPO-', '', GoodsReceipt::orderBy('created_at', 'desc')->first()->reference_number ?? 0) + 1; // get the latest po sequence then add 1

        $data = request()->all(); // get all request
        $data['reference_number'] = 'GRPO-' . str_pad($grpo_count, 8, '0', STR_PAD_LEFT);
        $data['created_by_user_id'] = auth()->user()->id;
        $data['status'] = GoodsReceiptStatus::FULFILLING; // if you want to insert to a specific column
        GoodsReceipt::create($data); // create data in a model

        $goods_receipt = GoodsReceipt::where('created_by_user_id', auth()->user()->id)
                                    ->latest()
                                    ->first();

        // mark the purchase order as checking
        $purchase_order = PurchaseOrder::find($goods_receipt->purchase_order->id);
        $purchase_order->status = PurchaseOrderStatus::CHECKING_FOR_GRPO;
        $purchase_order->save();

        // label the orders to this goods receipt
        DB::table('orders')
            ->where('purchase_order_id', $purchase_order->id)
            ->update([
                'goods_receipt_id' => $goods_receipt->id
            ]);

        /* log the activity */
        app('App\Http\Controllers\Log\GoodsReceiptController')->create($request, $goods_receipt->id);

        $request->session()->flash('success', 'Data has been added');

        return redirect()->route('internals.goods-receipts.manage', [$goods_receipt->id]);
    }

    public function view($goods_receipt_id)
    {
        $goods_receipt = GoodsReceipt::find($goods_receipt_id);

        $orders = Order::where('purchase_order_id', $goods_receipt->purchase_order->id)
                    ->where('status', OrderStatus::ACTIVE)
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);

        $delivery_receipts = DeliveryReceipt::where('status', DeliveryReceiptStatus::ACTIVE)
                    ->where('goods_receipt_id', $goods_receipt->id)
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);

        return view('admin.goods_receipts.view', compact(
            'delivery_receipts',
            'goods_receipt',
            'orders'
        ));
    }

    public function manage($goods_receipt_id)
    {
        $goods_receipt = GoodsReceipt::find($goods_receipt_id);

        $orders = Order::where('purchase_order_id', $goods_receipt->purchase_order->id)
                    ->where('status', OrderStatus::ACTIVE)
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);

        $delivery_receipts = DeliveryReceipt::where('status', DeliveryReceiptStatus::ACTIVE)
                    ->where('goods_receipt_id', $goods_receipt->id)
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);

        return view('admin.goods_receipts.manage', compact(
            'delivery_receipts',
            'goods_receipt',
            'orders'
        ));
    }

    public function edit($goods_receipt_id)
    {
        $goods_receipt = GoodsReceipt::find($goods_receipt_id);

        return view('admin.goods_receipts.edit', compact(
            'goods_receipt'
        ));
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = $request->all();

        $goods_receipt = GoodsReceipt::find($request->goods_receipt_id);
        $goods_receipt->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');

        return redirect()->route('internals.goods-receipts');
    }   

    public function recover(Request $request, $goods_receipt_id)
    {
        $goods_receipt = GoodsReceipt::find($goods_receipt_id);
        $goods_receipt->status = GoodsReceiptStatus::FULFILLING; // mark data as on process again upon recovering
        $goods_receipt->save();

        $request->session()->flash('success', 'Data has been recovered');

        return back();
    }

    public function approve(Request $request, $goods_receipt_id)
    {
        $goods_receipt = GoodsReceipt::find($goods_receipt_id);
        $goods_receipt->approved_by_user_id = auth()->user()->id;
        $goods_receipt->status = GoodsReceiptStatus::APPROVED; // mark data as approved
        $goods_receipt->save();

        /* log the activity */
        app('App\Http\Controllers\Log\GoodsReceiptController')->approve($request, $goods_receipt->id);

        $request->session()->flash('success', 'Data has been approved');

        return back();
    }

    public function disapprove(Request $request, $goods_receipt_id)
    {
        $goods_receipt = GoodsReceipt::find($goods_receipt_id);
        $goods_receipt->status = GoodsReceiptStatus::DISAPPROVED; // mark data as disapproved
        $goods_receipt->save();

        /* log the activity */
        app('App\Http\Controllers\Log\GoodsReceiptController')->disapprove($request, $goods_receipt->id);

        $request->session()->flash('success', 'Data has been cancelled');

        return back();
    }

    public function cancel(Request $request, $goods_receipt_id)
    {
        $goods_receipt = GoodsReceipt::find($goods_receipt_id);
        $goods_receipt->status = GoodsReceiptStatus::CANCELLED; // mark data as cancelled
        $goods_receipt->save();

        $orders = Order::where('goods_receipt_id', $goods_receipt->id)
                        ->get();

        foreach ($orders as $order) {
            $current_inventory = Inventory::where('item_id', $order->item->id)
                                        ->where('branch_id', $order->purchase_order->branch->id)
                                        ->first();
            if ($current_inventory) {
                $current_inventory->qty -= $order->received_qty;
                $current_inventory->save();
            }
        }

        $purchase_order = PurchaseOrder::find($goods_receipt->purchase_order->id);
        $purchase_order->status = PurchaseOrderStatus::CANCELLED; // mark data as cancelled
        $purchase_order->save();

        /* log the activity */
        app('App\Http\Controllers\Log\GoodsReceiptController')->cancel($request, $goods_receipt->id);
        app('App\Http\Controllers\Log\PurchaseOrderController')->cancel($request, $purchase_order->id);

        $request->session()->flash('success', 'Data has been cancelled');

        return back();
    }

    public function clear(Request $request, $goods_receipt_id)
    {
        $data = [];

        $goods_receipt = GoodsReceipt::find($request->goods_receipt_id);
        $data['status'] = GoodsReceiptStatus::CLEARED; // mark data as for cleared
        $goods_receipt->fill($data)->save();

        $purchase_order = PurchaseOrder::find($goods_receipt->purchase_order->id);
        $data['status'] = PurchaseOrderStatus::DONE; // mark data as for cleared
        $purchase_order->fill($data)->save();

        /* log the activity */
        app('App\Http\Controllers\Log\GoodsReceiptController')->clear($request, $goods_receipt->id);
        app('App\Http\Controllers\Log\PurchaseOrderController')->clear($request, $purchase_order->id);

        $request->session()->flash('success', 'Data has been finalized');

        return redirect()->route('internals.goods-receipts');
    }
}
