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
use App\Models\POSVat;
use App\Models\Payment;
use App\Models\Inventory;
use App\Models\UserStatus;
use App\Models\POSDiscount;
use App\Models\PaymentProof;
use App\Models\POSVatStatus;
use App\Models\BranchStatus;
use App\Models\PaymentCredit;
use App\Models\PaymentStatus;
use App\Models\PaymentReceipt;
use App\Models\InventoryStatus;
use App\Models\ItemSerialNumber;
use App\Models\POSDiscountStatus;
use App\Models\PaymentProofStatus;
use App\Models\PaymentCreditStatus;
use App\Models\PaymentReceiptStatus;
use App\Models\ItemSerialNumberStatus;

class SalespersonController extends Controller
{
    public function show($so_number)
    {
        $payment = Payment::where('so_number', $so_number)
                        ->first();
        $users = User::where('status', '!=', UserStatus::INACTIVE)
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

        return view('admin.payments.salesperson.show', compact(
            'payment',
            'users'
        ));
    }

    public function search(Request $request)
    {
        $so_number = $request->so_number ?? '*';
        $name = $request->name ?? '*';
        $role = $request->role ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('accounting.payments.salesperson.filter', [$so_number, $name, $role, $status, $from_date, $to_date])->withInput();
    }

    public function filter($so_number, $name, $role, $status, $from_date, $to_date)
    {
        $query = User::orderBy('created_at', 'desc');

        if ($name != '*') {
            $query->where('firstname', 'LIKE', '%' . $name . '%');
            $query->orWhere('lastname', 'LIKE', '%' . $name . '%');
        }

        if ($role != '*') {
            $query->where('role', $role);
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

        $users = $query->paginate(15);
        $payment = Payment::where('so_number', $so_number)
                        ->first();

        return view('admin.payments.salesperson.show', compact(
            'payment',
            'users'
        ));
    }

    public function assign(Request $request, $so_number, $user_id)
    {
        /* payment receipts */
        DB::table('payment_receipts')
            ->where('so_number', $so_number)
            ->update([
                'salesperson_id' => $user_id,
            ]);

        /* payments */
        DB::table('payments')
            ->where('so_number', $so_number)
            ->update([
                'salesperson_id' => $user_id,
            ]);

        $request->session()->flash('success', 'Data has been updated');

        return redirect()->route('accounting.payments');
    }
}
