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
use App\Models\Payable;
use App\Models\GoodsReceipt;
use App\Models\PayableStatus;

class PayableController extends Controller
{
    public function show()
    {
        $period = now()->firstOfYear()->monthsUntil(now()->lastOfYear());

        $data = [];
        foreach ($period as $date)
        {
           $data[] = [
               'month' => $date->format('F'),
               'year' => $date->year,
           ];
        }

        $period = $data;

        return view('admin.reports.payables.show', compact(
            'period',
        ));
    }

    public function search(Request $request)
    {
        $from_date = $request->input('from_date') ?? '*';
        $to_date = $request->input('to_date') ?? '*';
        $status = $request->input('status') ?? '*';

        return redirect()->route('admin.reports.payables.filter', [$status, $from_date, $to_date])->withInput();
    }

    public function filter($status, $from_date, $to_date)
    {
        $from_date = $from_date . ' 00:00:00';
        $to_date = $to_date . ' 23:59:59';

        $goods_receipts = GoodsReceipt::orderBy('created_at', 'desc')->get();
        $query = Payable::orderBy('created_at', 'desc');

        if ($status != '*') {
            $query->where('status', $status);
        }

        /* date filter */
        /* if they provided both from date and to date */
        if ($from_date != '*' && $to_date != '*') {
            $query->whereBetween('created_at', [$from_date, $to_date]);
        }
        /* date filter */

        $payables = $query->get();
        $grand_total = $query->sum('price');

        return view('admin.reports.payables.view', compact(
            'goods_receipts',
            'payables',
            'grand_total',
            'from_date',
            'to_date'
        ));
    }
}
