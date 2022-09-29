<?php

namespace App\Http\Controllers\JO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Auth;
use Mail;
use Validator;
use App\Models\ServiceOrder;
use App\Models\ServiceOrderStatus;
use App\Models\ServiceOrderDetail;
use App\Models\ServiceOrderDetailStatus;

class CartController extends Controller
{
    public function checkout(Request $request)
    {
        $rules = [
            'mop' => 'required',
            'date_in' => 'required'
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
        $data['date_in'] = $request->date_in ?? Carbon::now();
        $data['user_id'] = $request->user_id;
        $data['authorized_user_id'] = auth()->user()->id;
        $data['status'] = ServiceOrderStatus::FOR_REPAIR; // if you want to insert to a specific column
        ServiceOrder::create($data); // create data in a model
        /* create the service order */

        /* get the created service order */
        $db_service_order = ServiceOrder::where('authorized_user_id', auth()->user()->id)
                                    ->where('user_id', $request->user_id)
                                    ->where('status', ServiceOrderStatus::FOR_REPAIR)
                                    ->latest()
                                    ->first();
        /* get the created service order */

        foreach ($service_order_details as $service_order_detail) {
            $db_service_order_detail = ServiceOrderDetail::find($service_order_detail->id);
            $jo_data['service_order_id'] = $db_service_order->id;
            $jo_data['status'] = ServiceOrderDetailStatus::FOR_REPAIR; // if you want to insert to a specific column
            $db_service_order_detail->fill($jo_data)->save();
        }

        return redirect()->route('operations.service-orders.view', [$db_service_order->jo_number]);
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

        $service_order_detail = ServiceOrderDetail::find($request->service_order_detail_id);
        $data['total'] = ($request->qty * $service_order_detail->price); // proceed with calculations with discount
        $service_order_detail->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');

        return back();
    }
}
