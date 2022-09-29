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
use App\Models\OrderStatus;
use App\Models\User;
use App\Models\UserStatus;
use App\Models\POSVat;
use App\Models\POSVatStatus;
use App\Models\ReturnInventory;
use App\Models\ReturnInventoryStatus;
use App\Models\POSDiscount;
use App\Models\POSDiscountStatus;
use App\Models\Branch;
use App\Models\BranchStatus;
use App\Models\Inventory;
use App\Models\InventoryStatus;
use App\Models\Payment;
use App\Models\PaymentStatus;
use App\Models\PaymentReceipt;
use App\Models\PaymentReceiptStatus;
use App\Models\PaymentCredit;
use App\Models\PaymentCreditStatus;
use App\Models\Item;
use App\Models\ItemStatus;
use App\Models\ItemSerialNumber;
use App\Models\ItemSerialNumberStatus;
use App\Models\ReturnInventoryItem;
use App\Models\ReturnInventoryItemStatus;

class RMAItemController extends Controller
{
    public function show($reference_number) {
        $return_inventory = ReturnInventory::where('reference_number', $reference_number)
                                        ->first();

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

        $inventories = Inventory::where('status', InventoryStatus::ACTIVE)
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);

        return view('admin.rma.items', compact(
            'return_inventory',
            'return_inventory_items',
            'inventories',
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

    public function search(Request $request)
    {
        $name = $request->name ?? '*';
        $barcode = $request->barcode ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('internals.rma.items.filter', [$request->reference_number, $barcode, $name, $status, $from_date, $to_date])->withInput();
    }

    public function filter($reference_number, $barcode, $name, $status, $from_date, $to_date)
    {
        $query = Inventory::leftJoin('items', 'inventories.item_id', '=', 'items.id')
                    ->leftJoin('brands', 'items.brand_id', '=', 'brands.id')
                    ->select('items.*', 'brands.*', 'inventories.*')
                    ->where('inventories.status', InventoryStatus::ACTIVE)
                    ->where('items.status', ItemStatus::ACTIVE)
                    ->orderBy('inventories.created_at', 'desc');

        if ($barcode != '*') {
            $query->where('items.barcode', $barcode);
        }

        if ($name != '*') {
            $query->where('items.name', 'LIKE', '%' . $name . '%');
            $query->orWhere('brands.name', 'LIKE', '%' . $name . '%');
        }

        /* date filter */
        // if they provided both from date and to date
        if ($from_date != '*' && $to_date != '*') {
            $query->whereBetween('inventories.created_at', [$from_date . ' 00:00:00', $to_date . ' 23:59:59']);
        }
        /* date filter */

        /* date filter */
        // if they provided both from date and to date
        if ($from_date != '*' && $to_date != '*') {
            $query->whereBetween('inventories.created_at', [$from_date . ' 00:00:00', $to_date . ' 23:59:59']);
        }
        /* date filter */

        $inventories = $query->paginate(10);

        $return_inventory = ReturnInventory::where('reference_number', $reference_number)->first();

        $payment_receipt = PaymentReceipt::where('so_number', $return_inventory->payment_receipt->so_number)
                                    ->first();

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

        return view('admin.rma.items', compact(
            'inventories',
            'return_inventory',
            'return_inventory_items',
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

    public function create(Request $request)
    {
        $rules = [
            'qty' => 'required',
            'type' => 'required',
            'remarks' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return back()->withInput()->withErrors($validator);

        $return_inventory = ReturnInventory::find($request->return_inventory_id);
        $inventory = Inventory::find($request->inventory_id);

        $data[] = $request->all();
        $data['return_inventory_id'] = $return_inventory->id;
        $data['inventory_id'] = $inventory->id;
        $data['qty'] = $request->qty;
        $data['type'] = $request->type;
        $data['remarks'] = strtoupper($request->remarks);
        $data['performed_by_user_id'] = auth()->user()->id;
        $data['status'] = ReturnInventoryItemStatus::ACTIVE;
        ReturnInventoryItem::create($data);

        $request->session()->flash('success', 'Data has been added');
        return back();
    }

    public function select(Request $request, $inventory_id, $reference_number)
    {
        $inventory = Inventory::find($inventory_id);
        $return_inventory = ReturnInventory::where('reference_number', $reference_number)
                                        ->first();

        // $request->session()->flash('success', 'Data has been added');
        return redirect()->route('internals.rma.items.review', [$reference_number, $inventory_id]);
    }

    public function review($reference_number, $inventory_id)
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

        $inventory = Inventory::find($inventory_id);

        return view('admin.rma.review', compact(
            'inventory',
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

    public function delete(Request $request, $reference_number, $return_inventory_item_id)
    {
        $return_inventory_item = ReturnInventoryItem::find($return_inventory_item_id);
        $return_inventory_item->status = ReturnInventoryItemStatus::INACTIVE;
        $return_inventory_item->save();

        $request->session()->flash('success', 'Data has been deleted');
        return back();
    }

    public function action_taken(Request $request)
    {
        $rules = [
            'action_taken' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return back()->withInput()->withErrors($validator);

        $data[] = $request->all();

        $return_inventory_item = ReturnInventoryItem::find($request->return_inventory_item_id);
        $data['action_taken'] = strtoupper($request->action_taken);
        $return_inventory_item->fill($data)->save();

        $request->session()->flash('success', 'Data has been added');
        return back();
    }
}
