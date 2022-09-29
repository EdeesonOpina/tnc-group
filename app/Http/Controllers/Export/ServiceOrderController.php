<?php

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\ServiceOrderExport;
use Carbon\Carbon;
use PDF; // declare when creating a pdf
use Auth;
use Mail;
use Validator;
use App\Models\User;
use App\Models\Branch;
use App\Models\ServiceOrder;
use App\Models\ServiceOrderStatus;
use App\Models\ServiceOrderDetail;
use App\Models\ServiceOrderDetailStatus;
use Maatwebsite\Excel\Facades\Excel; // declare when creating a excel

class ServiceOrderController extends Controller
{
    public function excel($jo_number)
    {
        $service_order = ServiceOrder::where('jo_number', $jo_number)
                        ->first();

        return Excel::download(new ServiceOrderExport($service_order->jo_number), $service_order->jo_number . '.xlsx');
    }

    public function print($jo_number)
    {
        $service_order = ServiceOrder::where('jo_number', $jo_number)
                        ->first();
        $branch = Branch::find($service_order->branch_id); // branch of that pos
        $user = User::find($service_order->user_id); // the customer
        $cashier = User::find($service_order->authorized_user_id); // the cashier in charge

        $service_order_details_total = ServiceOrderDetail::where('service_order_id', $service_order->id)
                                ->where('status', '!=', ServiceOrderDetailStatus::INACTIVE)
                                ->sum('total');
        $service_order_details = ServiceOrderDetail::where('service_order_id', $service_order->id)
                    ->where('status', '!=', ServiceOrderDetailStatus::INACTIVE)
                    ->get();

        $grand_total = $service_order_details_total;

        return view('admin.service_orders.print', compact(
            'grand_total',
            'branch',
            'user',
            'cashier',
            'service_order',
            'service_order_details_total',
            'service_order_details'
        ));
    }

    public function pdf($jo_number)
    {
        $service_order = ServiceOrder::where('jo_number', $jo_number)
                            ->first();
        $branch = Branch::find($service_order->branch_id); // branch of that pos
        $user = User::find($service_order->user_id); // the customer
        $technical = User::find($service_order->authorized_user_id); // the technical in charge

        $service_order_details_total = ServiceOrderDetail::where('service_order_id', $service_order->id)
                                ->where('status', '!=', ServiceOrderDetailStatus::INACTIVE)
                                ->sum('total');
        $service_order_details = ServiceOrderDetail::where('service_order_id', $service_order->id)
                    ->where('status', '!=', ServiceOrderDetailStatus::INACTIVE);

        $grand_total = $service_order_details_total;
                    
        // retreive all records from db
        $data = $service_order_details->get();

        // share data to view
        view()->share('data', $data);
        view()->share('service_order', $service_order);
        view()->share('service_order_details_total', $service_order_details_total);
        view()->share('service_order_details', $service_order_details);
        view()->share('branch', $branch);
        view()->share('user', $user);
        view()->share('technical', $technical);

        $pdf = PDF::loadView('admin.service_orders.pdf', compact($data));

        // download PDF file with download method
        // return $pdf->download(date('M-d-Y') . ' PURCHASE ORDER.pdf'); // return as download
        // return $pdf->stream($service_order->jo_number . '.pdf'); // return as view
        return $pdf->download($service_order->jo_number . '.pdf'); // return as view
    }
}
