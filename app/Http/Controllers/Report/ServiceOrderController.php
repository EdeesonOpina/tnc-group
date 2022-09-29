<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\CarbonPeriod;
use Carbon\Carbon;
use DB;
use Auth;
use Mail;
use Validator;
use App\Models\User;
use App\Models\UserStatus;
use App\Models\ServiceOrder;
use App\Models\ServiceOrderStatus;
use App\Models\ServiceOrderDetail;
use App\Models\ServiceOrderDetailStatus;

class ServiceOrderController extends Controller
{
    public function show()
    {
        $start_date = Carbon::now();
        $first_day = Carbon::parse($start_date->firstOfMonth())->format('Y-m-d');
        $last_day = Carbon::parse($start_date->lastOfMonth())->format('Y-m-d');

        $period = CarbonPeriod::create($first_day, $last_day);

        return view('admin.reports.service-orders.show', compact(
            'period',
            'start_date',
            'first_day',
            'last_day'
        ));
    }

    public function search(Request $request)
    {
        $from_date = $request->input('from_date') ?? '*';
        $to_date = $request->input('to_date') ?? '*';
        $user_id = $request->input('user_id') ?? '*';

        return redirect()->route('admin.reports.service-orders.filter', [$from_date, $to_date])->withInput();
    }

    public function filter($from_date, $to_date)
    {
        $from_date = $from_date . ' 00:00:00';
        $to_date = $to_date . ' 23:59:59';

        $service_orders = ServiceOrder::whereBetween('date_out', [
                                $from_date, $to_date
                            ])
                            ->where('status', '!=', ServiceOrderStatus::FOR_RELEASE)
                            ->where('status', '!=', ServiceOrderStatus::INACTIVE)
                            ->get();

        return view('admin.reports.service-orders.view', compact(
            'service_orders',
            'from_date',
            'to_date'
        ));
    }
}
