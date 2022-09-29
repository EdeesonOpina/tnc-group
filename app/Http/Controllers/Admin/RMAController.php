<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Auth;
use Mail;
use Validator;
use App\Models\User;
use App\Models\Branch;
use App\Models\Payment;
use App\Models\UserStatus;
use App\Models\BranchStatus;
use App\Models\PaymentStatus;
use App\Models\PaymentReceipt;
use App\Models\ItemSerialNumber;
use App\Models\PaymentReceiptStatus;
use App\Models\ItemSerialNumberStatus;

use App\Models\POSVat;
use App\Models\POSDiscount;
use App\Models\POSVatStatus;
use App\Models\PaymentCredit;
use App\Models\POSDiscountStatus;
use App\Models\PaymentCreditStatus;

use App\Models\DeliveryReceipt;
use App\Models\DeliveryReceiptStatus;

use App\Models\Inventory;
use App\Models\InventoryStatus;
use App\Models\ReturnInventory;
use App\Models\ReturnInventoryStatus;
use App\Models\ReturnInventoryItem;
use App\Models\ReturnInventoryItemStatus;

class RMAController extends Controller
{
    public function show()
    {
        $return_inventories = ReturnInventory::where('status', '!=', ReturnInventoryStatus::INACTIVE)
                                        ->paginate(15);

        return view('admin.rma.show', compact(
            'return_inventories'
        ));
    }

    public function search(Request $request)
    {
        $reference_number = $request->reference_number ?? '*';
        $name = $request->name ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('internals.rma.filter', [$reference_number, $name, $status, $from_date, $to_date])->withInput();
    }

    public function filter($reference_number, $name, $status, $from_date, $to_date)
    {
        $query = ReturnInventory::leftJoin('payment_receipts', 'return_inventories.payment_receipt_id', '=', 'payment_receipts.id')
                    ->leftJoin('users', 'payment_receipts.user_id', '=', 'users.id')
                    ->select('return_inventories.*')
                    ->orderBy('return_inventories.created_at', 'desc');

        if ($reference_number != '*') {
            $query->where('return_inventories.reference_number', $reference_number);
        }

        if ($name != '*') {
            $query->whereRaw("concat(users.firstname, ' ', users.lastname) like '%" . $name . "%' ");
        }

        if ($status != '*') {
            $query->where('return_inventories.status', $status);
        }

        /* date filter */
        // if they provided both from date and to date
        if ($from_date != '*' && $to_date != '*') {
            $query->whereBetween('return_inventories.created_at', [$from_date . ' 00:00:00', $to_date . ' 23:59:59']);
        }
        /* date filter */

        $return_inventories = $query->paginate(15);

        return view('admin.rma.show', compact(
            'return_inventories'
        ));
    }

    public function view($reference_number)
    {
        $return_inventory = ReturnInventory::where('reference_number', $reference_number)->first();

        $payment_receipt = PaymentReceipt::find($return_inventory->payment_receipt->id);

        $branch = Branch::find($payment_receipt->branch_id); // branch of that pos
        $user = User::find($payment_receipt->user_id); // the customer
        $cashier = User::find($payment_receipt->authorized_user_id); // the cashier in charge

        $payments = Payment::where('so_number', $payment_receipt->so_number)
                        ->where('status', '!=', PaymentStatus::INACTIVE)
                        ->get();

        $payments_total = Payment::where('so_number', $payment_receipt->so_number)
                                ->where('status', '!=', PaymentStatus::INACTIVE)
                                ->sum('total');

        $payments_discount = POSDiscount::where('so_number', $payment_receipt->so_number)
                                    ->where('status', POSDiscountStatus::ACTIVE)
                                    ->first()
                                    ->price ?? 0;

        $payments_vat = POSVat::where('so_number', $payment_receipt->so_number)
                                    ->where('status', POSVatStatus::ACTIVE)
                                    ->first()
                                    ->price ?? 0;

        $grand_total = ($payments_vat + $payments_total) - $payments_discount;

        if (PaymentCredit::where('so_number', $payment_receipt->so_number)
                    ->exists()) {
            $payment_credits = PaymentCredit::where('so_number', $payment_receipt->so_number)
                                        ->get();
            $payment_credit_total = PaymentCredit::where('so_number', $payment_receipt->so_number)
                                            ->sum('price');
        } else {
            $payment_credits = [];
            $payment_credit_total = 0;
        }

        $item_serial_numbers = ItemSerialNumber::where('payment_receipt_id', $payment_receipt->id)
                                            ->get();

        $return_inventory_items = ReturnInventoryItem::where('return_inventory_id', $return_inventory->id)
                                                ->where('status', ReturnInventoryItemStatus::ACTIVE)
                                                ->get();

        return view('admin.rma.view', compact(
            'return_inventory_items',
            'return_inventory',
            'payment_receipt',
            'grand_total',
            'payment_credits',
            'payment_credit_total',
            'branch',
            'user',
            'cashier',
            'payments',
            'payments_total',
            'payments_discount',
            'payments_vat',
            'item_serial_numbers',
        ));
    }

    public function manage($reference_number)
    {
        $return_inventory = ReturnInventory::where('reference_number', $reference_number)->first();

        $payment_receipt = PaymentReceipt::find($return_inventory->payment_receipt->id);

        $branch = Branch::find($payment_receipt->branch_id); // branch of that pos
        $user = User::find($payment_receipt->user_id); // the customer
        $cashier = User::find($payment_receipt->authorized_user_id); // the cashier in charge

        $payments = Payment::where('so_number', $payment_receipt->so_number)
                        ->where('status', '!=', PaymentStatus::INACTIVE)
                        ->get();

        $payments_total = Payment::where('so_number', $payment_receipt->so_number)
                                ->where('status', '!=', PaymentStatus::INACTIVE)
                                ->sum('total');

        $payments_discount = POSDiscount::where('so_number', $payment_receipt->so_number)
                                    ->where('status', POSDiscountStatus::ACTIVE)
                                    ->first()
                                    ->price ?? 0;

        $payments_vat = POSVat::where('so_number', $payment_receipt->so_number)
                                    ->where('status', POSVatStatus::ACTIVE)
                                    ->first()
                                    ->price ?? 0;

        $grand_total = ($payments_vat + $payments_total) - $payments_discount;

        if (PaymentCredit::where('so_number', $payment_receipt->so_number)
                    ->exists()) {
            $payment_credits = PaymentCredit::where('so_number', $payment_receipt->so_number)
                                        ->get();
            $payment_credit_total = PaymentCredit::where('so_number', $payment_receipt->so_number)
                                            ->sum('price');
        } else {
            $payment_credits = [];
            $payment_credit_total = 0;
        }

        $item_serial_numbers = ItemSerialNumber::where('payment_receipt_id', $payment_receipt->id)
                                            ->get();

        $return_inventory_items = ReturnInventoryItem::where('return_inventory_id', $return_inventory->id)
                                                ->where('status', ReturnInventoryItemStatus::ACTIVE)
                                                ->get();

        return view('admin.rma.manage', compact(
            'return_inventory_items',
            'return_inventory',
            'payment_receipt',
            'grand_total',
            'payment_credits',
            'payment_credit_total',
            'branch',
            'user',
            'cashier',
            'payments',
            'payments_total',
            'payments_discount',
            'payments_vat',
            'item_serial_numbers',
        ));
    }

    public function add()
    {
        return view('admin.rma.add');
    }

    public function find(Request $request)
    {
        $rules = [
            'so_number' => 'required|exists:payment_receipts',
            'delivery_receipt_number' => 'nullable|exists:delivery_receipts',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return back()->withInput()->withErrors($validator);

        $payment_receipt = PaymentReceipt::where('so_number', $request->so_number)
                                        ->first();

        $data[] = $request->all();

        /* for testing local */
        // $rma_count = 1;

        /* get the latest po sequence then add 1 */
        $rma_count = str_replace('RMA-', '', ReturnInventory::orderBy('created_at', 'desc')->first()->reference_number) + 1;
        $data['reference_number'] = 'RMA-' . str_pad($rma_count, 8, '0', STR_PAD_LEFT);
        $data['type'] = $request->type;
        if ($request->delivery_receipt_number) {
            $delivery_receipt = DeliveryReceipt::where('delivery_receipt_number', $request->delivery_receipt_number)
                                            ->where('status', DeliveryReceiptStatus::ACTIVE)
                                            ->first();

            $data['goods_receipt_id'] = $delivery_receipt->goods_receipt->id;
            $data['delivery_receipt_number'] = $request->delivery_receipt_number;
        } else {
            $data['goods_receipt_id'] = 0;
        }
        $data['payment_receipt_id'] = $payment_receipt->id;
        $data['branch_id'] = $payment_receipt->branch->id;
        $data['created_by_user_id'] = auth()->user()->id;
        $data['status'] = ReturnInventoryStatus::ON_PROCESS; // if you want to insert to a specific column
        ReturnInventory::create($data); // create data in a model

        $return_inventory = ReturnInventory::where('reference_number', 'RMA-' . str_pad($rma_count, 8, '0', STR_PAD_LEFT))
                                        ->first();

        return redirect()->route('internals.rma.manage', [$return_inventory->reference_number]);
    }

    public function delivery_receipt(Request $request)
    {
        $rules = [
            'delivery_receipt_number' => 'nullable|exists:delivery_receipts',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return back()->withInput()->withErrors($validator);

        $data[] = $request->all();

        $return_inventory = ReturnInventory::find($request->return_inventory_id);
        $delivery_receipt = DeliveryReceipt::where('delivery_receipt_number', $request->delivery_receipt_number)
                                            ->where('status', DeliveryReceiptStatus::ACTIVE)
                                            ->first();

        $data['goods_receipt_id'] = $delivery_receipt->goods_receipt->id;
        $data['delivery_receipt_number'] = $request->delivery_receipt_number;
        $return_inventory->fill($data)->save();

        $request->session()->flash('success', 'Data has been recovered');
        return back();
    }

    public function recover(Request $request, $return_inventory_id)
    {
        $return_inventory = ReturnInventory::find($return_inventory_id);
        $return_inventory->status = ReturnInventoryStatus::FOR_WARRANTY; // mark data as for warranty
        $return_inventory->save();

        $request->session()->flash('success', 'Data has been recovered');
        return back();
    }

    public function delete(Request $request, $return_inventory_id)
    {
        $return_inventory = ReturnInventory::find($return_inventory_id);
        $return_inventory->status = ReturnInventoryStatus::INACTIVE; // mark data as inactive
        $return_inventory->save();

        $request->session()->flash('success', 'Data has been deleted');
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

        $payment = Payment::find($request->payment_id);

        /* update the current inventory qty */
        $inventory = Inventory::find($payment->inventory->id);

        /* check if there is available qty */
        if ($request->qty > $inventory->qty) {
            $request->session()->flash('error', 'This item do not have enough stock/s in your inventory');
            return back();
        }

        $inventory->qty -= $request->qty;
        $inventory->save();

        $data['total'] = ($request->qty * $payment->price); // proceed with calculations with discount
        $payment->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');
        return back();
    }

    public function cleared(Request $request, $return_inventory_id)
    {
        $return_inventory = ReturnInventory::where('reference_number', $return_inventory_id)->first();
        $return_inventory->status = ReturnInventoryStatus::CLEARED;
        $return_inventory->save();

        $request->session()->flash('success', 'Data has been marked as cleared');
        return redirect()->route('internals.rma.view', [$return_inventory->reference_number]);
    }

    public function waiting(Request $request, $return_inventory_id)
    {
        $return_inventory = ReturnInventory::where('reference_number', $return_inventory_id)->first();
        $return_inventory->status = ReturnInventoryStatus::WAITING;
        $return_inventory->save();

        $request->session()->flash('success', 'Data has been marked as waiting');
        return redirect()->route('internals.rma.view', [$return_inventory->reference_number]);
    }

    public function for_warranty(Request $request, $return_inventory_id)
    {
        $return_inventory = ReturnInventory::where('reference_number', $return_inventory_id)->first();
        $return_inventory->status = ReturnInventoryStatus::FOR_WARRANTY;
        $return_inventory->save();

        $request->session()->flash('success', 'Data has been marked as for warranty');
        return redirect()->route('internals.rma.view', [$return_inventory->reference_number]);
    }

    public function for_release(Request $request, $return_inventory_id)
    {
        $return_inventory = ReturnInventory::where('reference_number', $return_inventory_id)->first();
        $return_inventory->status = ReturnInventoryStatus::FOR_RELEASE;
        $return_inventory->save();

        $request->session()->flash('success', 'Data has been marked as for release');
        return redirect()->route('internals.rma.view', [$return_inventory->reference_number]);
    }

    public function out_of_warranty(Request $request, $return_inventory_id)
    {
        $return_inventory = ReturnInventory::where('reference_number', $return_inventory_id)->first();
        $return_inventory->status = ReturnInventoryStatus::OUT_OF_WARRANTY;
        $return_inventory->save();

        $request->session()->flash('success', 'Data has been marked as for out of warranty');
        return redirect()->route('internals.rma.view', [$return_inventory->reference_number]);
    }

    public function cancel(Request $request, $return_inventory_id)
    {
        $return_inventory = ReturnInventory::where('reference_number', $return_inventory_id)->first();
        $return_inventory->status = ReturnInventoryStatus::CANCELLED;
        $return_inventory->save();

        $request->session()->flash('success', 'Data has been cancelled');
        return back();
    }
}
