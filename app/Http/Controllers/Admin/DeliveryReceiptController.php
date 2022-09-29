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
use App\Models\DeliveryReceipt;
use App\Models\GoodsReceiptStatus;
use App\Models\PurchaseOrderStatus;
use App\Models\DeliveryReceiptStatus;

class DeliveryReceiptController extends Controller
{
    public function create(Request $request)
    {
        $rules = [
            'delivery_receipt_number' => 'required|unique:delivery_receipts',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) 
            return back()->withInput()->withErrors($validator);

        $data = request()->all(); // get all request
        $data['received_by_user_id'] = auth()->user()->id;
        $data['status'] = DeliveryReceiptStatus::ACTIVE; // if you want to insert to a specific column
        DeliveryReceipt::create($data); // create data in a model

        $request->session()->flash('success', 'Data has been added');

        return back();
    }

    public function recover(Request $request, $goods_receipt_id, $delivery_receipt_id)
    {
        $delivery_receipt = DeliveryReceipt::find($delivery_receipt_id);
        $delivery_receipt->status = DeliveryReceiptStatus::ACTIVE; // mark data as on process again upon recovering
        $delivery_receipt->save();

        $request->session()->flash('success', 'Data has been recovered');

        return back();
    }

    public function delete(Request $request, $goods_receipt_id, $delivery_receipt_id)
    {
        $delivery_receipt = DeliveryReceipt::find($delivery_receipt_id);
        $delivery_receipt->status = DeliveryReceiptStatus::INACTIVE; // mark data as on process again upon recovering
        $delivery_receipt->save();

        $request->session()->flash('success', 'Data has been deleted');

        return back();
    }
}
