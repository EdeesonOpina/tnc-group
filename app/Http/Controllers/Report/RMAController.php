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
use App\Models\ReturnInventory;
use App\Models\ReturnInventoryStatus;

class RMAController extends Controller
{
    public function show()
    {
        $start_date = Carbon::now();
        $first_day = Carbon::parse($start_date->firstOfMonth())->format('Y-m-d');
        $last_day = Carbon::parse($start_date->lastOfMonth())->format('Y-m-d');

        $period = CarbonPeriod::create($first_day, $last_day);

        return view('admin.reports.rma.show', compact(
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
        $status = $request->input('status') ?? '*';

        return redirect()->route('admin.reports.rma.filter', [$status, $from_date, $to_date])->withInput();
    }

    public function filter($status, $from_date, $to_date)
    {
        $from_date = $from_date . ' 00:00:00';
        $to_date = $to_date . ' 23:59:59';

        $query = ReturnInventory::where('status', '!=', ReturnInventoryStatus::ON_PROCESS)
                            ->where('status', '!=', ReturnInventoryStatus::INACTIVE);

        if ($status != '*') {
            $query->where('status', $status);
        }

        if ($from_date != '*' && $to_date != '*') {
            $query->whereBetween('created_at', [
                                $from_date, $to_date
                            ]);
        }

        $return_inventories = $query->get();

        $rma_count = ReturnInventory::whereBetween('created_at', [
                                $from_date, $to_date
                            ])
                            ->where('status', '!=', ReturnInventoryStatus::ON_PROCESS)
                            ->where('status', '!=', ReturnInventoryStatus::INACTIVE)
                            ->count();

        $for_warranty_count = ReturnInventory::whereBetween('created_at', [
                                    $from_date, $to_date
                                ])
                                ->where('status', ReturnInventoryStatus::FOR_WARRANTY)
                                ->where('status', '!=', ReturnInventoryStatus::ON_PROCESS)
                                ->where('status', '!=', ReturnInventoryStatus::INACTIVE)
                                ->count();

        $waiting_count = ReturnInventory::whereBetween('created_at', [
                                $from_date, $to_date
                            ])
                            ->where('status', ReturnInventoryStatus::WAITING)
                            ->where('status', '!=', ReturnInventoryStatus::ON_PROCESS)
                            ->where('status', '!=', ReturnInventoryStatus::INACTIVE)
                            ->count();

        $for_release_count = ReturnInventory::whereBetween('created_at', [
                                $from_date, $to_date
                            ])
                            ->where('status', ReturnInventoryStatus::FOR_RELEASE)
                            ->where('status', '!=', ReturnInventoryStatus::ON_PROCESS)
                            ->where('status', '!=', ReturnInventoryStatus::INACTIVE)
                            ->count();

        $cleared_count = ReturnInventory::whereBetween('created_at', [
                                $from_date, $to_date
                            ])
                            ->where('status', ReturnInventoryStatus::CLEARED)
                            ->where('status', '!=', ReturnInventoryStatus::ON_PROCESS)
                            ->where('status', '!=', ReturnInventoryStatus::INACTIVE)
                            ->count();

        $cancelled_count = ReturnInventory::whereBetween('created_at', [
                                $from_date, $to_date
                            ])
                            ->where('status', ReturnInventoryStatus::CANCELLED)
                            ->where('status', '!=', ReturnInventoryStatus::ON_PROCESS)
                            ->where('status', '!=', ReturnInventoryStatus::INACTIVE)
                            ->count();

        return view('admin.reports.rma.view', compact(
            'rma_count',
            'for_warranty_count',
            'waiting_count',
            'for_release_count',
            'cleared_count',
            'cancelled_count',
            'return_inventories',
            'from_date',
            'to_date'
        ));
    }
}
