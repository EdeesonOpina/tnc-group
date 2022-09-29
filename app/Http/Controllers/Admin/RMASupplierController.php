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

use App\Models\Supplier;
use App\Models\SupplierStatus;

use App\Models\Inventory;
use App\Models\InventoryStatus;
use App\Models\ReturnInventory;
use App\Models\ReturnInventoryStatus;
use App\Models\ReturnInventoryItem;
use App\Models\ReturnInventoryItemStatus;

class RMASupplierController extends Controller
{
    public function show($reference_number) {
        $return_inventory = ReturnInventory::where('reference_number', $reference_number)
                                        ->first();

        $return_inventory_items = ReturnInventoryItem::where('return_inventory_id', $return_inventory->id)
                                                ->where('status', ReturnInventoryItemStatus::ACTIVE)
                                                ->get();

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

        $suppliers = Supplier::where('status', SupplierStatus::ACTIVE)
                        ->paginate(15);

        $item_serial_numbers = ItemSerialNumber::where('payment_receipt_id', $payment_receipt->id)
                                            ->get();

        return view('admin.rma.suppliers', compact(
            'item_serial_numbers',
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
            'suppliers',
        ));
    }

    public function search(Request $request)
    {
        $name = $request->name ?? '*';

        return redirect()->route('internals.rma.suppliers.filter', [$request->reference_number, $name])->withInput();
    }

    public function filter($reference_number, $name)
    {
        $query = Supplier::where('status', SupplierStatus::ACTIVE);

        if ($name != '*') {
            $query->where('name', 'LIKE', '%' . $name . '%');
        }

        $suppliers = $query->paginate(10);

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

        return view('admin.rma.suppliers', compact(
            'suppliers',
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

    public function select(Request $request, $reference_number, $supplier_id)
    {
        $supplier = Supplier::find($supplier_id);
        $return_inventory = ReturnInventory::where('reference_number', $reference_number)
                                        ->first();

        $return_inventory->supplier_id = $supplier->id;
        $return_inventory->save();

        $request->session()->flash('success', 'Data has been selected');
        return redirect()->route('internals.rma.manage', [$reference_number]);
    }
}
