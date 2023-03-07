<?php

namespace App\Http\Controllers\Admin\CashAdvance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Auth;
use Mail;
use Validator;
use App\Models\User;
use App\Models\UserStatus;
use App\Models\CashAdvance;
use App\Models\CashAdvanceStatus;
use App\Models\CashAdvancePayment;
use App\Models\CashAdvancePaymentStatus;

class CashAdvanceController extends Controller
{
    public function show()
    {
        $cash_advances = CashAdvance::orderBy('created_at', 'desc')
                                    ->where('status', '!=', CashAdvanceStatus::INACTIVE)
                                    ->paginate(15);

        $users = User::where('role', '!=', 'Customer')
                    ->where('role', '!=', 'Agent')
                    ->where('role', '!=', 'Corporate')
                    ->where('status', UserStatus::ACTIVE)
                    ->get();

        return view('admin.cash_advances.show', compact(
            'cash_advances',
            'users'
        ));
    }

    public function add()
    {
        $users = User::where('role', '!=', 'Customer')
                    ->where('role', '!=', 'Agent')
                    ->where('role', '!=', 'Corporate')
                    ->where('status', UserStatus::ACTIVE)
                    ->get();

        return view('admin.cash_advances.add', compact(
            'users'
        ));
    }

    public function create(Request $request)
    {
        $rules = [
            'user_id' => 'required',
            'date_borrowed' => 'required',
            'price' => 'required',
            'reason' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return back()->withInput()->withErrors($validator);

        /* for testing local */
        // $rma_count = 1;

        /* get the latest po sequence then add 1 */
        $ca_count = str_replace('CA-', '', CashAdvance::orderBy('created_at', 'desc')->first()->reference_number ?? 0) + 1;

        $data = request()->all(); // get all request
        $data['reference_number'] = 'CA-' . str_pad($ca_count, 8, '0', STR_PAD_LEFT);
        $data['status'] = CashAdvanceStatus::UNPAID; // if you want to insert to a specific column
        CashAdvance::create($data); // create data in a model

        $request->session()->flash('success', 'Data has been added');
        return redirect()->route('accounting.cash-advances');
    }

    public function view($ca_number)
    {
        $cash_advance = CashAdvance::where('reference_number', $ca_number)
                                    ->first();
        $cash_advance_payments = CashAdvancePayment::where('cash_advance_id', $cash_advance->id)
                                                ->paginate(15);
        $paid_balance = CashAdvancePayment::where('cash_advance_id', $cash_advance->id)
                                        ->where('status', CashAdvancePaymentStatus::APPROVED)
                                        ->sum('price');

        return view('admin.cash_advances.view', compact(
            'paid_balance',
            'cash_advance',
            'cash_advance_payments',
        ));
    }

    public function pay(Request $request)
    {
        $rules = [
            'price' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) 
            return back()->withInput()->withErrors($validator);

        $cash_advance = CashAdvance::find($request->cash_advance_id);

        $paid_balance = CashAdvancePayment::where('cash_advance_id', $cash_advance->id)
                                        ->where('status', CashAdvancePaymentStatus::APPROVED)
                                        ->sum('price');

        $remaining_balance = $cash_advance->price - $paid_balance;

        if ($request->price > $remaining_balance) {
            $request->session()->flash('error', 'Entered price is greater than the remaining balance');
            return back();
        }

        $data = $request->all();
        $data['price'] = $request->price; // initial downpayment
        $data['cash_advance_id'] = $cash_advance->id;
        $data['status'] = CashAdvancePaymentStatus::PENDING;
        CashAdvancePayment::create($data); // create data in a model

        $request->session()->flash('success', 'Data has been added');
        return back();
    }

    public function search(Request $request)
    {
        $ca_number = $request->ca_number ?? '*';
        $name = $request->name ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('accounting.cash-advances.filter', [$ca_number, $name, $status, $from_date, $to_date])->withInput();
    }

    public function filter($ca_number, $name, $status, $from_date, $to_date)
    {
        $query = CashAdvance::leftJoin('users', 'cash_advances.user_id', '=', 'users.id')
                    ->select('cash_advances.*')
                    ->where('cash_advances.status', '!=', CashAdvanceStatus::INACTIVE)
                    ->orderBy('cash_advances.created_at', 'desc');

        if ($ca_number != '*') {
            $query->where('cash_advances.reference_number', $ca_number);
        }

        if ($name != '*') {
            $query->whereRaw("concat(users.firstname, ' ', users.lastname) like '%" . $name . "%' ");
            
            /* original query */
            // $query->where('users.firstname', $name);
            // $query->orWhere('users.lastname', $name);
        }

        if ($status != '*') {
            $query->where('cash_advances.status', $status);
        }

        /* date filter */
        // if they provided both from date and to date
        if ($from_date != '*' && $to_date != '*') {
            $query->whereBetween('cash_advances.date_borrowed', [$from_date . ' 00:00:00', $to_date . ' 23:59:59']);
        }
        /* date filter */

        $cash_advances = $query->paginate(15);

        $users = User::where('role', '!=', 'Customer')
                            ->where('role', '!=', 'Agent')
                            ->where('role', '!=', 'Corporate')
                            ->where('status', UserStatus::ACTIVE)
                            ->get();

        return view('admin.cash_advances.show', compact(
            'users',
            'cash_advances'
        ));
    }
}
