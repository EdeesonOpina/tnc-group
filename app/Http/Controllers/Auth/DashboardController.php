<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Payment;
use App\Models\Supplier;
use App\Models\Inventory;
use App\Models\ActivityLogAuth;

class DashboardController extends Controller
{
    public function show()
    {
        $users = User::orderBy('created_at', 'desc')
                    ->paginate(5);
        $suppliers = Supplier::orderBy('created_at', 'desc')
                    ->paginate(7);
        $payments = Payment::orderBy('created_at', 'desc')
                    ->paginate(5);

        $activity_log_auth = ActivityLogAuth::orderBy('created_at', 'desc')
                                        ->paginate(5);

        /* last 30 days */
        $last_30_records = Payment::select(\DB::raw("COUNT(*) as count"), \DB::raw("DAYNAME(created_at) as day_name"), \DB::raw("DAY(created_at) as day"))
                    ->where('created_at', '>', Carbon::today()->subDay(30))
                    ->groupBy('day_name','day')
                    ->orderBy('day')
                    ->get();
          
        $last_30_days = [];
     
        foreach($last_30_records as $last_30_row) {
            $last_30_days['label'][] = $last_30_row->day_name;
            $last_30_days['data'][] = (int) $last_30_row->count;
        }

        /* last 7 days */
        $last_7_records = Payment::select(\DB::raw("COUNT(*) as count"), \DB::raw("DAYNAME(created_at) as day_name"), \DB::raw("DAY(created_at) as day"))
                    ->where('created_at', '>', Carbon::today()->subDay(7))
                    ->groupBy('day_name','day')
                    ->orderBy('day')
                    ->get();
          
        $last_7_days = [];
     
        foreach($last_7_records as $last_7_row) {
            $last_7_days['label'][] = $last_7_row->day_name;
            $last_7_days['data'][] = (int) $last_7_row->count;
        }
     
        /* set as json */
        $last_30_days_chart['chart_data'] = json_encode($last_30_days);
        $last_7_days_chart['chart_data'] = json_encode($last_7_days);

        return view('auth.dashboard', compact(
            'activity_log_auth',
            'last_30_days_chart',
            'last_7_days_chart',
            'users',
            'suppliers',
            'payments'
        ));
    }
}
