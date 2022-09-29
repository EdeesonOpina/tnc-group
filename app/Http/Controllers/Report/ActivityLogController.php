<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Validator;
use App\Models\ActivityLogAuth;
use App\Models\ActivityLogOrder;
use App\Models\InventoryReturnRecord;
use App\Models\InventoryReceiveRecord;
use App\Models\ActivityLogGoodsReceipt;
use App\Models\ActivityLogPurchaseOrder;

class ActivityLogController extends Controller
{
    public function show()
    {
        $activity_log_auth = ActivityLogAuth::orderBy('created_at', 'desc')
                                        ->paginate(5);

        $activity_log_orders = ActivityLogOrder::orderBy('created_at', 'desc')
                                        ->paginate(5);

        $activity_log_goods_receipts = ActivityLogGoodsReceipt::orderBy('created_at', 'desc')
                                        ->paginate(5);

        $activity_log_purchase_orders = ActivityLogPurchaseOrder::orderBy('created_at', 'desc')
                                        ->paginate(5);

        $inventory_receive_records = InventoryReceiveRecord::orderBy('created_at', 'desc')
                                        ->paginate(5);

        $inventory_return_records = InventoryReturnRecord::orderBy('created_at', 'desc')
                                        ->paginate(5);

        return view('admin.reports.activity-logs.show', compact(
            'activity_log_auth',
            'activity_log_orders',
            'activity_log_goods_receipts',
            'activity_log_purchase_orders',
            'inventory_receive_records',
            'inventory_return_records'
        ));
    }

    public function search(Request $request)
    {
        $name = $request->name ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('admin.reports.activity-logs.filter', [$name, $status, $from_date, $to_date])->withInput();
    }

    public function filter($name, $status, $from_date, $to_date)
    {
        $query = ActivityLogAuth::orderBy('created_at', 'desc');

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

        $activity_log_auth = $query->paginate(15);

        return view('admin.reports.activity-logs.show', compact(
            'activity_log_auth'
        ));
    }
}
