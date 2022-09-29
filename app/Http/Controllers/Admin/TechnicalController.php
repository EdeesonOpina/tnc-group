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
use App\Models\UserStatus;
use App\Models\ServiceOrder;
use App\Models\ServiceOrderStatus;

class TechnicalController extends Controller
{
    public function show($jo_number)
    {
        $service_order = ServiceOrder::where('jo_number', $jo_number)
                        ->first();
        $users = User::where('role', '!=', 'Customer')
                    ->where('role', '!=', 'Agent')
                    ->where('role', '!=', 'Corporate')
                    ->where('status', '!=', UserStatus::INACTIVE)
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

        return view('admin.service_orders.technical.show', compact(
            'service_order',
            'users'
        ));
    }

    public function search(Request $request)
    {
        $jo_number = $request->jo_number ?? '*';
        $name = $request->name ?? '*';
        $role = $request->role ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('operations.service-orders.technical.filter', [$jo_number, $name, $role, $status, $from_date, $to_date])->withInput();
    }

    public function filter($jo_number, $name, $role, $status, $from_date, $to_date)
    {
        $query = User::orderBy('created_at', 'desc');

        if ($name != '*') {
            $query->whereRaw("concat(firstname, ' ', lastname) like '%" . $name . "%' ");
            // $query->where('firstname', 'LIKE', '%' . $name . '%');
            // $query->orWhere('lastname', 'LIKE', '%' . $name . '%');
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
        $service_order = ServiceOrder::where('jo_number', $jo_number)
                        ->first();

        return view('admin.service_orders.technical.show', compact(
            'service_order',
            'users'
        ));
    }

    public function assign(Request $request, $jo_number, $user_id)
    {
        /* service orders */
        DB::table('service_orders')
            ->where('jo_number', $jo_number)
            ->update([
                'authorized_user_id' => $user_id,
            ]);

        $request->session()->flash('success', 'Data has been updated');

        return redirect()->route('operations.service-orders');
    }
}
