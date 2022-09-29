<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Auth;
use Mail;
use Validator;
use App\Models\ReturnInventory;
use App\Models\ItemSerialNumber;
use App\Models\ReturnInventoryItem;
use App\Models\ReturnInventoryStatus;
use App\Models\ItemSerialNumberStatus;
use App\Models\ReturnInventoryItemStatus;
use App\Models\ReturnInventoryItemSerialNumber;
use App\Models\ReturnInventoryItemSerialNumberStatus;

class RMAItemSerialNumberController extends Controller
{
    public function create(Request $request)
    {
        $rules = [
            'serial_number' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return back()->withInput()->withErrors($validator);

        /* check if there is an available serial number for this item */
        if (! ItemSerialNumber::where('item_id', $payment->item->id)
                    ->where('code', $request->serial_number)
                    ->where('status', ItemSerialNumberStatus::AVAILABLE)
                    ->first()) {
            $request->session()->flash('error', 'There is no available serial number for this item');
            return back();
        }



        $request->session()->flash('success', 'Data has been added');
        return back();
    }

    public function recover(Request $request, $rma_item_serial_number_id)
    {
        $return_inventory_item_serial_number = ReturnInventoryItemSerialNumber::find($return_inventory_item_serial_number_id);
        $return_inventory_item_serial_number->status = ReturnInventoryItemSerialNumberStatus::ACTIVE; // mark data as for warranty
        $return_inventory_item_serial_number->save();

        $request->session()->flash('success', 'Data has been recovered');
        return back();
    }

    public function delete(Request $request, $rma_item_serial_number_id)
    {
        $return_inventory_item_serial_number = ReturnInventoryItemSerialNumber::find($return_inventory_item_serial_number_id);
        $return_inventory_item_serial_number->status = ReturnInventoryItemSerialNumberStatus::INACTIVE; // mark data as inactive
        $return_inventory_item_serial_number->save();

        $request->session()->flash('success', 'Data has been deleted');
        return back();
    }
}
