<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Validator;
use App\Models\Order;
use App\Models\Branch;
use App\Models\Supply;
use App\Models\Supplier;
use App\Models\OrderStatus;
use App\Models\SupplyStatus;
use App\Models\BranchStatus;
use App\Models\GoodsReceipt;
use App\Models\PurchaseOrder;
use App\Models\SupplierStatus;
use App\Models\GoodsReceiptStatus;
use App\Models\PurchaseOrderStatus;

class PurchaseOrderController extends Controller
{
    public function show()
    {
        $purchase_orders = PurchaseOrder::orderBy('created_at', 'desc')
                    ->paginate(15);

        return view('admin.purchase_orders.show', compact(
            'purchase_orders'
        ));
    }

    public function search(Request $request)
    {
        $reference_number = $request->reference_number ?? '*';
        $name = $request->name ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('internals.purchase-orders.filter', [$reference_number, $name, $status, $from_date, $to_date])->withInput();
    }

    public function filter($reference_number, $name, $status, $from_date, $to_date)
    {
        $query = PurchaseOrder::leftJoin('suppliers', 'purchase_orders.supplier_id', '=', 'suppliers.id')
                        ->select('purchase_orders.*')
                        ->orderBy('purchase_orders.created_at', 'desc');

        if ($reference_number != '*') {
            $query->where('purchase_orders.reference_number', $reference_number);
        }

        if ($name != '*') {
            $query->where('suppliers.name', 'LIKE', '%' . $name . '%');
        }

        if ($status != '*') {
            $query->where('purchase_orders.status', $status);
        }

        /* date filter */
        // if they provided both from date and to date
        if ($from_date != '*' && $to_date != '*') {
            $query->whereBetween('purchase_orders.created_at', [$from_date . ' 00:00:00', $to_date . ' 23:59:59']);
        }
        /* date filter */

        $purchase_orders = $query->paginate(15);

        return view('admin.purchase_orders.show', compact(
            'purchase_orders'
        ));
    }

    public function add()
    {
        $branches = Branch::where('status', BranchStatus::ACTIVE)
                        ->orderBy('name', 'asc')
                        ->get();

        $suppliers = Supplier::where('status', SupplierStatus::ACTIVE)
                        ->orderBy('name', 'asc')
                        ->get();

        return view('admin.purchase_orders.add', compact(
            'branches',
            'suppliers'
        ));
    }

    public function create(Request $request)
    {
        $rules = [
            'supplier_id' => 'required',
            'branch_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $po_count = str_replace('PO-', '', PurchaseOrder::orderBy('created_at', 'desc')->first()->reference_number) + 1; // get the latest po sequence then add 1

        $data = request()->all(); // get all request
        $data['reference_number'] = 'PO-' . str_pad($po_count, 8, '0', STR_PAD_LEFT);
        $data['supplier_id'] = $request->supplier_id;
        $data['branch_id'] = $request->branch_id;
        $data['created_by_user_id'] = auth()->user()->id;
        $data['status'] = PurchaseOrderStatus::ON_PROCESS; // if you want to insert to a specific column
        PurchaseOrder::create($data); // create data in a model

        $purchase_order = PurchaseOrder::where('created_by_user_id', auth()->user()->id)
                                    ->latest()
                                    ->first();

        /* log the activity */
        app('App\Http\Controllers\Log\PurchaseOrderController')->create($request, $purchase_order->id);

        $request->session()->flash('success', 'Data has been added');

        return redirect()->route('internals.purchase-orders.manage', [$purchase_order->id]);
    }

    public function view($purchase_order_id)
    {
        $purchase_order = PurchaseOrder::find($purchase_order_id);

        $orders = Order::where('purchase_order_id', $purchase_order->id)
                    ->where('status', OrderStatus::ACTIVE)
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);

        $orders_total = Order::where('purchase_order_id', $purchase_order->id)
                    ->where('status', OrderStatus::ACTIVE)
                    ->sum('total');

        return view('admin.purchase_orders.view', compact(
            'purchase_order',
            'orders_total',
            'orders'
        ));
    }

    public function manage($purchase_order_id)
    {
        $purchase_order = PurchaseOrder::find($purchase_order_id);

        $supplies = Supply::where('supplier_id', $purchase_order->supplier->id)
                    ->where('status', SupplyStatus::ACTIVE)
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);

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

    public function edit($purchase_order_id)
    {
        $purchase_order = PurchaseOrder::find($purchase_order_id);

        return view('admin.purchase_orders.edit', compact(
            'purchase_order'
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

        $purchase_order = PurchaseOrder::find($request->purchase_order_id);
        $purchase_order->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');

        return redirect()->route('internals.purchase-orders');
    }   

    public function recover(Request $request, $purchase_order_id)
    {
        $purchase_order = PurchaseOrder::find($purchase_order_id);
        $purchase_order->status = PurchaseOrderStatus::ON_PROCESS; // mark data as on process again upon recovering
        $purchase_order->save();

        /* log the activity */
        app('App\Http\Controllers\Log\PurchaseOrderController')->recover($request, $purchase_order->id);

        $request->session()->flash('success', 'Data has been recovered');

        return back();
    }

    public function approve(Request $request, $purchase_order_id)
    {
        $purchase_order = PurchaseOrder::find($purchase_order_id);
        $purchase_order->approved_by_user_id = auth()->user()->id;
        $purchase_order->status = PurchaseOrderStatus::APPROVED; // mark data as approved
        $purchase_order->save();

        /* log the activity */
        app('App\Http\Controllers\Log\PurchaseOrderController')->approve($request, $purchase_order->id);

        $request->session()->flash('success', 'Data has been approved');

        return back();
    }

    public function disapprove(Request $request, $purchase_order_id)
    {
        $purchase_order = PurchaseOrder::find($purchase_order_id);
        $purchase_order->status = PurchaseOrderStatus::DISAPPROVED; // mark data as disapproved
        $purchase_order->save();

        /* log the activity */
        app('App\Http\Controllers\Log\PurchaseOrderController')->disapprove($request, $purchase_order->id);

        $request->session()->flash('success', 'Data has been disapproved');

        return back();
    }

    public function cancel(Request $request, $purchase_order_id)
    {
        $purchase_order = PurchaseOrder::find($purchase_order_id);
        $purchase_order->status = PurchaseOrderStatus::CANCELLED; // mark data as cancelled
        $purchase_order->save();

        /* log the activity */
        app('App\Http\Controllers\Log\PurchaseOrderController')->cancel($request, $purchase_order->id);

        $request->session()->flash('success', 'Data has been cancelled');

        return back();
    }

    public function finalize(Request $request)
    {
        $data = [];

        $purchase_order = PurchaseOrder::find($request->purchase_order_id);
        $data['status'] = PurchaseOrderStatus::FOR_APPROVAL; // mark data as for approval
        $purchase_order->fill($data)->save();

        $request->session()->flash('success', 'Data has been finalized');

        return redirect()->route('internals.purchase-orders');
    }
}
