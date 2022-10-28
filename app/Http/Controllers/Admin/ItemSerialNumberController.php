<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Validator;
use App\Models\Item;
use App\Models\Inventory;
use App\Models\ItemStatus;
use App\Models\DeliveryReceipt;
use App\Models\InventoryStatus;
use App\Models\ItemSerialNumber;
use App\Models\DeliveryReceiptStatus;
use App\Models\ItemSerialNumberStatus;

class ItemSerialNumberController extends Controller
{
    public function show($item_id)
    {
        $item = Item::find($item_id);

        $item_serial_numbers = ItemSerialNumber::where('item_id', $item->id)
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);

        return view('admin.items.serial-numbers.show', compact(
            'item_serial_numbers',
            'item'
        ));
    }

    public function search(Request $request)
    {
        $code = $request->code ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('internals.inventories.items.serial-numbers.filter', [$request->item_id, $code, $status, $from_date, $to_date])->withInput();
    }

    public function filter($item_id, $code, $status, $from_date, $to_date)
    {
        $item = Item::find($item_id);
        $query = ItemSerialNumber::where('item_id', $item->id)
                            ->orderBy('created_at', 'desc');

        if ($code != '*') {
            $query->where('code', $code);
        }

        if ($status != '*') {
            $query->where('status', $status);
        }

        /* date filter */
        // if they provided both from date and to date
        if ($from_date != '*' && $to_date != '*') {
            $query->whereBetween('created_at', [$from_date . ' 00:00:00', $to_date . ' 23:59:59']);
        }
        /* date filter */

        $item_serial_numbers = $query->paginate(15);

        return view('admin.items.serial-numbers.show', compact(
            'item_serial_numbers',
            'item'
        ));
    }

    public function add()
    {
        return view('admin.items.serial-numbers.add');
    }

    public function create(Request $request)
    {
        $rules = [
            'code' => 'required',
            'delivery_receipt_number' => 'required|exists:delivery_receipts',
        ];

        $validator = Validator::make($request->all(), $rules);

        $codes = $request->code;

        foreach ($codes as $code) {
            // check if there is a same serial number on that item
            if (ItemSerialNumber::where('item_id', $request->item_id)
                            ->where('code', $code)
                            ->exists()) {
                $request->session()->flash('error', $code . ' already exists on this item');
                return back();
            }

            if ($validator->fails()) 
                return back()->withInput()->withErrors($validator);

            $delivery_receipt = DeliveryReceipt::where('delivery_receipt_number', $request->delivery_receipt_number)
                                        ->where('status', DeliveryReceiptStatus::ACTIVE)
                                        ->first();

            $data = request()->all(); // get all request
            $data['goods_receipt_id'] = $delivery_receipt->goods_receipt->id;
            $data['delivery_receipt_id'] = $delivery_receipt->id;
            $data['code'] = $code;
            $data['status'] = ItemSerialNumberStatus::AVAILABLE; // if you want to insert to a specific column
            ItemSerialNumber::create($data); // create data in a model
        }

        $request->session()->flash('success', 'Data has been added');

        return back();
    }

    public function edit($item_serial_number_id)
    {
        $item_serial_number = ItemSerialNumber::find($item_serial_number_id);

        return view('admin.items.serial-numbers.edit', compact(
            'item_serial_number'
        ));
    }

    public function update(Request $request)
    {
        $rules = [
            'item_id' => 'required',
            'code' => 'required',
            'delivery_receipt_number' => 'required|exists:delivery_receipts',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) 
            return back()->withInput()->withErrors($validator);

        $delivery_receipt = DeliveryReceipt::where('delivery_receipt_number', $request->delivery_receipt_number)
                                    ->where('status', DeliveryReceiptStatus::ACTIVE)
                                    ->first();
                                    
        $data = $request->all();
        $data['goods_receipt_id'] = $delivery_receipt->goods_receipt->id;
        $data['delivery_receipt_id'] = $delivery_receipt->id;
        $item_serial_number = ItemSerialNumber::find($request->item_serial_number_id);

        // check if there is a same serial number on that item
        // if (ItemSerialNumber::where('item_id', $item_serial_number->item_id)
        //                 ->where('code', $request->code)
        //                 ->exists()) {
        //     $request->session()->flash('error', 'There is an existing serial number on this item');
        //     return back();
        // }


        $item_serial_number->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');

        return redirect()->route('internals.inventories.items.serial-numbers', [$item_serial_number->item_id]);
    }

    public function available(Request $request, $item_id, $item_serial_number_id)
    {
        $item_serial_number = ItemSerialNumber::find($item_serial_number_id);
        $item_serial_number->status = ItemSerialNumberStatus::AVAILABLE; // mark data as available
        $item_serial_number->save();

        $cart = Cart::find($item_serial_number->cart_id);
        $cart->status = CartStatus::INACTIVE;
        $cart->save();

        $request->session()->flash('success', 'Data has been tagged as available');

        return back();
    }

    public function sold(Request $request, $item_id, $item_serial_number_id)
    {
        /* This is used only for force tagging a serial number as sold for it to be equal on inventory qty */

        $item_serial_number = ItemSerialNumber::find($item_serial_number_id);
        $item_serial_number->status = ItemSerialNumberStatus::SOLD; // mark data as available
        $item_serial_number->save();

        if (Cart::where('id', $item_serial_number->cart_id)->exists())
        {
            $cart = Cart::find($item_serial_number->cart_id);
            $cart->status = CartStatus::INACTIVE;
            $cart->save();
        }

        $request->session()->flash('success', 'Data has been tagged as sold');

        return back();
    }

    public function floating(Request $request, $item_id, $item_serial_number_id)
    {
        $item_serial_number = ItemSerialNumber::find($item_serial_number_id);
        $item_serial_number->status = ItemSerialNumberStatus::FLOATING; // mark data as floating
        $item_serial_number->save();

        $request->session()->flash('success', 'Data has been tagged as floating');

        return back();
    }

    public function revert(Request $request, $item_id, $item_serial_number_id)
    {
        /* get the current inventory qty and return 1 */
        $inventory = Inventory::where('item_id', $item_id)
                        ->where('status', InventoryStatus::ACTIVE)
                        ->latest()
                        ->first();
        $inventory->qty += 1;
        $inventory->save();

        $item_serial_number = ItemSerialNumber::find($item_serial_number_id);
        $item_serial_number->payment_id = 0;
        $item_serial_number->status = ItemSerialNumberStatus::AVAILABLE; // mark data as available
        $item_serial_number->save();

        $request->session()->flash('success', 'Data has been tagged as available');

        return back();
    }

    public function recover(Request $request, $item_id, $item_serial_number_id)
    {
        $item_serial_number = ItemSerialNumber::find($item_serial_number_id);
        $item_serial_number->status = ItemSerialNumberStatus::AVAILABLE; // mark data as active
        $item_serial_number->save();

        $request->session()->flash('success', 'Data has been recovered');

        return back();
    }

    public function delete(Request $request, $item_id, $item_serial_number_id)
    {
        $item_serial_number = ItemSerialNumber::find($item_serial_number_id);
        $item_serial_number->payment_id = 0;
        $item_serial_number->payment_receipt_id = 0;
        $item_serial_number->status = ItemSerialNumberStatus::INACTIVE; // mark data as inactive
        $item_serial_number->save();

        $request->session()->flash('success', 'Data has been deleted');

        return back();
    }

    public function assignSO(Request $request)
    {
        $rules = [
            'so_number' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        // check if the payment so number does not exists if yes then redirect back
        if (!Payment::where('so_number', $request->so_number)->exists()) {
            $request->session()->flash('error', 'The SO# that you have entered does not exist');
            return back();
        }

        if ($validator->fails()) 
            return back()->withInput()->withErrors($validator);

        $payment = Payment::where('so_number', $request->so_number)->first();

        $item_serial_number = ItemSerialNumber::find($request->item_serial_number_id);
        $item_serial_number->payment_id = $payment->id;
        $item_serial_number->payment_receipt_id = $payment->payment_receipt_id;
        $item_serial_number->status = ItemSerialNumberStatus::SOLD;
        $item_serial_number->save();

        $request->session()->flash('success', 'Data has been updated');

        return redirect()->route('internals.inventories.items.serial-numbers', [$item_serial_number->item_id]);
    }
}
