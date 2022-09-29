<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Validator;
use App\Models\Account;
use App\Models\AccountStatus;

class AccountController extends Controller
{
    public function show()
    {
        $accounts = Account::orderBy('created_at', 'desc')
                    ->paginate(15);

        return view('admin.accounts.show', compact(
            'accounts'
        ));
    }

    public function search(Request $request)
    {
        $name = $request->name ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('admin.accounts.filter', [$name, $status, $from_date, $to_date])->withInput();
    }

    public function filter($name, $status, $from_date, $to_date)
    {
        $query = Account::orderBy('created_at', 'desc');

        if ($name != '*') {
            $query->where('name', 'LIKE', '%' . $name . '%');
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

        $accounts = $query->paginate(15);

        return view('admin.accounts.show', compact(
            'accounts'
        ));
    }

    public function add()
    {
        return view('admin.accounts.add');
    }

    public function create(Request $request)
    {
        $rules = [
            'bank' => 'required',
            'name' => 'required',
            'number' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) 
            return back()->withInput()->withErrors($validator);

        $data = request()->all(); // get all request
        $data['status'] = AccountStatus::ACTIVE; // if you want to insert to a specific column
        Account::create($data); // create data in a model

        $request->session()->flash('success', 'Data has been added');

        return redirect()->route('admin.accounts');
    }

    public function view($account_id)
    {
        $account = Account::find($account_id);

        return view('admin.accounts.view', compact(
            'account'
        ));
    }

    public function edit($account_id)
    {
        $account = Account::find($account_id);

        return view('admin.accounts.edit', compact(
            'account'
        ));
    }

    public function update(Request $request)
    {
        $rules = [
            'bank' => 'required',
            'name' => 'required',
            'number' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = $request->all();

        $account = Account::find($request->account_id);
        $account->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');

        return redirect()->route('admin.accounts');
    }

    public function recover(Request $request, $account_id)
    {
        $account = Account::find($account_id);
        $account->status = AccountStatus::ACTIVE; // mark data as active
        $account->save();

        $request->session()->flash('success', 'Data has been recovered');

        return back();
    }

    public function delete(Request $request, $account_id)
    {
        $account = Account::find($account_id);
        $account->status = AccountStatus::INACTIVE; // mark data as inactive
        $account->save();

        $request->session()->flash('success', 'Data has been deleted');

        return back();
    }
}
