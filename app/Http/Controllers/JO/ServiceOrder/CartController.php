<?php

namespace App\Http\Controllers\JO\ServiceOrder;

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
                    ->where('status', '!=', ServiceOrderDetailStatus::FOR_REPAIR)
                    ->where('status', '!=', ServiceOrderDetailStatus::FOR_RELEASE)
                    ->where('status', '!=', ServiceOrderDetailStatus::COMPLETED)
                    ->where('status', '!=', ServiceOrderDetailStatus::CANCELLED)
                    ->where('status', '!=', ServiceOrderDetailStatus::INACTIVE)
                    ->orderBy('created_at', 'desc')
                    ->get();

        $service_order_details_total = ServiceOrderDetail::where('authorized_user_id', auth()->user()->id)
                    ->where('user_id', $user_id)
                    ->where('status', '!=', ServiceOrderDetailStatus::FOR_REPAIR)
                    ->where('status', '!=', ServiceOrderDetailStatus::FOR_RELEASE)
                    ->where('status', '!=', ServiceOrderDetailStatus::COMPLETED)
                    ->where('status', '!=', ServiceOrderDetailStatus::CANCELLED)
                    ->where('status', '!=', ServiceOrderDetailStatus::INACTIVE)
                    ->orderBy('created_at', 'desc')
                    ->sum('total');

        return view('admin.service_orders.jo.service-order.show', compact(
            'cashier',
            'branch',
            'user',
            'service_order_details_total',
            'service_order_details'
        ));
    }

    public function create(Request $request)
    {
        $rules = [
            'name' => 'required',
            'price' => 'required',
            'qty' => 'required',
            'serial_number_notes' => 'nullable',
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
        $data['name'] = strtoupper($request->name);
        $data['remarks'] = strtoupper($request->remarks);
        $data['serial_number_notes'] = strtoupper($request->serial_number_notes);
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
