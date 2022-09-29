<?php

namespace App\Http\Controllers\POS\ServiceOrder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Auth;
use Mail;
use Validator;
use App\Models\User;
use App\Models\Branch;
use App\Models\Account;
use App\Models\UserStatus;
use App\Models\ServiceType;
use App\Models\ServiceOrder;
use App\Models\BranchStatus;
use App\Models\AccountStatus;
use App\Models\ServiceTypeStatus;
use App\Models\ServiceOrderStatus;
use App\Models\ServiceOrderDetail;
use App\Models\ServiceOrderDetailStatus;

class CartController extends Controller
{
    public function show($user_id)
    {
        $branch = Branch::find(Branch::MAIN_BRANCH); // branch of that pos
        $user = User::find($user_id); // the customer
        $cashier = User::find(auth()->user()->id); // the cashier in charge

        $service_order_details = ServiceOrderDetail::where('authorized_user_id', auth()->user()->id)
                    ->where('user_id', $user_id)
                    ->where('status', '!=', ServiceOrderDetailStatus::DELIVERED)
                    ->where('status', '!=', ServiceOrderDetailStatus::INACTIVE)
                    ->orderBy('created_at', 'desc')
                    ->get();

        $service_order_details_total = ServiceOrderDetail::where('authorized_user_id', auth()->user()->id)
                    ->where('user_id', $user_id)
                    ->where('status', '!=', ServiceOrderDetailStatus::DELIVERED)
                    ->where('status', '!=', ServiceOrderDetailStatus::INACTIVE)
                    ->orderBy('created_at', 'desc')
                    ->sum('total');

        return view('admin.pos.service-order.show', compact(
            'cashier',
            'branch',
            'user',
            'service_order_details_total',
            'service_order_details'
        ));
    }

    public function checkout(Request $request)
    {
        $rules = [
            'mop' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = []; // declare as an array
        $jo_data = []; // declare as an array

        // get the latest po sequence then add 1
        // $jo_count = 1;
        $jo_count = str_replace('JO-', '', ServiceOrder::orderBy('created_at', 'desc')->first()->jo_number) + 1;
        // return $jo_count;

        /* get the service order on the cart */
        $service_order_details = ServiceOrderDetail::where('authorized_user_id', auth()->user()->id)
                    ->where('user_id', $request->user_id)
                    ->where('status', ServiceOrderDetailStatus::PENDING)
                    ->orderBy('created_at', 'desc')
                    ->get();

        $service_order_details_total = ServiceOrderDetail::where('authorized_user_id', auth()->user()->id)
                    ->where('user_id', $request->user_id)
                    ->where('status', ServiceOrderDetailStatus::PENDING)
                    ->orderBy('created_at', 'desc')
                    ->sum('total');
        /* get the service order on the cart */

        /* create the service order */
        $data['jo_number'] = 'JO-' . str_pad($jo_count, 8, '0', STR_PAD_LEFT);
        $data['total'] = $service_order_details_total;
        $data['branch_id'] = auth()->user()->branch->id;
        $data['mop'] = $request->mop;
        $data['total'] = $service_order_details_total;
        $data['delivered_date'] = Carbon::now();
        $data['user_id'] = $request->user_id;
        $data['authorized_user_id'] = auth()->user()->id;
        $data['status'] = ServiceOrderStatus::DELIVERED; // if you want to insert to a specific column
        ServiceOrder::create($data); // create data in a model
        /* create the service order */

        /* get the created service order */
        $db_service_order = ServiceOrder::where('authorized_user_id', auth()->user()->id)
                                    ->where('user_id', $request->user_id)
                                    ->where('status', ServiceOrderStatus::DELIVERED)
                                    ->latest()
                                    ->first();
        /* get the created service order */

        foreach ($service_order_details as $service_order_detail) {
            $db_service_order_detail = ServiceOrderDetail::find($service_order_detail->id);
            $jo_data['service_order_id'] = $db_service_order->id;
            $jo_data['status'] = ServiceOrderDetailStatus::DELIVERED; // if you want to insert to a specific column
            $db_service_order_detail->fill($jo_data)->save();
        }

        return redirect()->route('operations.service-orders.view', [$db_service_order->jo_number]);
    }

    public function create(Request $request)
    {
        $rules = [
            'name' => 'required',
            'price' => 'required',
            'qty' => 'required',
            'remarks' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = request()->all(); // get all request

        $data['service_order_id'] = 0;
        $data['total'] = ($request->price * $request->qty);
        $data['user_id'] = $request->user_id;
        $data['authorized_user_id'] = auth()->user()->id;
        $data['status'] = ServiceOrderDetailStatus::PENDING; // if you want to insert to a specific column
        ServiceOrderDetail::create($data); // create data in a model

        $request->session()->flash('success', 'Data has been added');

        return back();
    }

    public function delete(Request $request, $user_id, $service_order_detail_id)
    {
        $service_order_detail = ServiceOrderDetail::find($service_order_detail_id);
        $service_order_detail->status = ServiceOrderDetailStatus::INACTIVE; // mark data as inactive
        $service_order_detail->save();

        $request->session()->flash('success', 'Data has been deleted');

        return back();
    }
}
