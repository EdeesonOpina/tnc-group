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
use App\Models\UserStatus;
use App\Models\BranchStatus;
use App\Models\ServiceOrder;
use App\Models\ServiceOrderType;
use App\Models\ServiceOrderDetail;
use App\Models\ServiceOrderStatus;
use App\Models\ServiceOrderTypeStatus;
use App\Models\ServiceOrderDetailStatus;

class ServiceOrderController extends Controller
{
    public function show()
    {
        $service_orders = ServiceOrder::orderBy('created_at', 'desc')
                        ->paginate(15);

        return view('admin.service_orders.show', compact(
            'service_orders'
        ));
    }

    public function search(Request $request)
    {
        $jo_number = $request->jo_number ?? '*';
        $name = $request->name ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('operations.service-orders.filter', [$jo_number, $name, $status, $from_date, $to_date])->withInput();
    }

    public function filter($jo_number, $name, $status, $from_date, $to_date)
    {
        $query = ServiceOrder::leftJoin('users', 'service_orders.user_id', '=', 'users.id')
                    ->select('service_orders.*')
                    ->orderBy('service_orders.created_at', 'desc');

        if ($jo_number != '*') {
            $query->where('service_orders.jo_number', $jo_number);
        }

        if ($name != '*') {
            $query->where('users.firstname', $name);
            $query->orWhere('users.lastname', $name);
        }

        if ($status != '*') {
            $query->where('service_orders.status', $status);
        }

        /* date filter */
        // if they provided both from date and to date
        if ($from_date != '*' && $to_date != '*') {
            $query->whereBetween('service_orders.created_at', [$from_date . ' 00:00:00', $to_date . ' 23:59:59']);
        }
        /* date filter */

        $service_orders = $query->paginate(15);

        return view('admin.service_orders.show', compact(
            'service_orders'
        ));
    }

    public function view($jo_number)
    {
        $service_order = ServiceOrder::where('jo_number', $jo_number)
                            ->first();
        $branch = Branch::find($service_order->branch_id); // branch of that pos
        $user = User::find($service_order->user_id); // the customer
        $cashier = User::find($service_order->authorized_user_id); // the cashier in charge

        $service_order_details = ServiceOrderDetail::where('service_order_id', $service_order->id)
                        ->where('status', '!=', ServiceOrderDetailStatus::INACTIVE)
                        ->get();

        $service_order_details_total = ServiceOrderDetail::where('service_order_id', $service_order->id)
                                ->where('status', '!=', ServiceOrderDetailStatus::INACTIVE)
                                ->sum('total');

        return view('admin.service_orders.view', compact(
            'service_order_details',
            'service_order_details_total',
            'service_order',
            'branch',
            'user',
            'cashier',
        ));
    }

    public function mop(Request $request)
    {
        $rules = [
            'mop' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) 
            return back()->withInput()->withErrors($validator);

        $data = $request->all();

        $service_order = ServiceOrder::find($request->service_order_id);
        $service_order->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');

        return back();
    }

    public function price(Request $request)
    {
        $rules = [
            'price' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) 
            return back()->withInput()->withErrors($validator);

        $data = $request->all();
        $service_order_detail = ServiceOrderDetail::find($request->service_order_detail_id);
        $data['total'] = $request->price * $service_order_detail->qty;
        $service_order_detail->fill($data)->save();

        $service_order_details_total = ServiceOrderDetail::where('id', $service_order_detail->id)
                                                ->where('status', '!=', ServiceOrderDetailStatus::INACTIVE)
                                                ->where('status', '!=', ServiceOrderDetailStatus::CANCELLED)
                                                ->sum('total');

        $service_order = ServiceOrder::find($service_order_detail->service_order_id);
        $service_order->total = $service_order_details_total;
        $service_order->save();

        $request->session()->flash('success', 'Data has been updated');

        return back();
    }

    public function date_out(Request $request)
    {
        $rules = [
            'date_out' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) 
            return back()->withInput()->withErrors($validator);

        $data = $request->all();

        $service_order = ServiceOrder::find($request->service_order_id);
        $data['status'] = ServiceOrderStatus::COMPLETED;
        $service_order->fill($data)->save();

        DB::table('service_order_details')
            ->where('service_order_id', $service_order->id)
            ->update([
                'status' => ServiceOrderStatus::COMPLETED
            ]);

        $request->session()->flash('success', 'Data has been updated');

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

        $data = $request->all();

        $service_order_detail = ServiceOrderDetail::find($request->service_order_detail_id);
        $service_order_detail->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');

        return back();
    }

    public function for_release(Request $request, $jo_number)
    {
        $service_order = ServiceOrder::where('jo_number', $jo_number)->first();
        $service_order->status = ServiceOrderStatus::FOR_RELEASE;
        $service_order->save();

        DB::table('service_order_details')
            ->where('service_order_id', $service_order->id)
            ->update([
                'status' => ServiceOrderStatus::FOR_RELEASE
            ]);
        $request->session()->flash('success', 'Data has been marked as for release');

        return back();
    }

    public function for_repair(Request $request, $jo_number)
    {
        $service_order = ServiceOrder::where('jo_number', $jo_number)->first();
        $service_order->status = ServiceOrderStatus::FOR_REPAIR;
        $service_order->save();

        DB::table('service_order_details')
            ->where('service_order_id', $service_order->id)
            ->update([
                'status' => ServiceOrderStatus::FOR_REPAIR
            ]);
        $request->session()->flash('success', 'Data has been marked as for repair');

        return back();
    }

    public function completed(Request $request, $jo_number)
    {
        $service_order = ServiceOrder::where('jo_number', $jo_number)->first();
        $service_order->status = ServiceOrderStatus::COMPLETED;
        $service_order->save();

        DB::table('service_order_details')
            ->where('service_order_id', $service_order->id)
            ->update([
                'status' => ServiceOrderStatus::COMPLETED
            ]);

        $request->session()->flash('success', 'Data has been completed');

        return back();
    }

    public function cancel(Request $request, $jo_number)
    {
        $service_order = ServiceOrder::where('jo_number', $jo_number)->first();
        $service_order->status = ServiceOrderStatus::CANCELLED;
        $service_order->save();

        DB::table('service_order_details')
            ->where('service_order_id', $service_order->id)
            ->update([
                'status' => ServiceOrderStatus::CANCELLED
            ]);

        $request->session()->flash('success', 'Data has been cancelled');

        return back();
    }

    public function recover(Request $request, $jo_number)
    {
        $service_order = ServiceOrder::where('jo_number', $jo_number)->first();
        $service_order->status = ServiceOrderStatus::PENDING;
        $service_order->save();

        DB::table('service_order_details')
            ->where('service_order_id', $service_order->id)
            ->update([
                'status' => ServiceOrderStatus::PENDING
            ]);

        $request->session()->flash('success', 'Data has been recovered');

        return back();
    }

    public function delete(Request $request, $jo_number)
    {
        $service_order = ServiceOrder::where('jo_number', $jo_number)->first();
        $service_order->status = ServiceOrderStatus::INACTIVE; // mark data as inactive
        $service_order->save();

        DB::table('service_order_details')
            ->where('service_order_id', $service_order->id)
            ->update([
                'status' => ServiceOrderStatus::INACTIVE
            ]);

        $request->session()->flash('success', 'Data has been deleted');

        return back();
    }

    public function back_job(Request $request)
    {
        $data = $request->all();

        $service_order = ServiceOrder::find($request->service_order_id);
        $data['status'] = ServiceOrderStatus::BACK_JOB;
        $service_order->fill($data)->save();

        DB::table('service_order_details')
            ->where('service_order_id', $service_order->id)
            ->update([
                'status' => ServiceOrderStatus::BACK_JOB
            ]);

        $request->session()->flash('success', 'Data has been tagged for back job');

        return back();
    }
}
