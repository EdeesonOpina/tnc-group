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
use App\Models\Project;
use App\Models\ProjectStatus;

class ProjectController extends Controller
{
    public function show()
    {
        $start_date = Carbon::now();
        $first_day = Carbon::parse($start_date->firstOfMonth())->format('Y-m-d');
        $last_day = Carbon::parse($start_date->lastOfMonth())->format('Y-m-d');

        $period = CarbonPeriod::create($first_day, $last_day);

        return view('admin.reports.projects.show', compact(
            'period',
            'start_date',
            'first_day',
            'last_day'
        ));
    }

    public function month($month)
    {
        $month = $month;
        $start_date = Carbon::parse($month);
        $first_day = Carbon::parse($start_date->firstOfMonth())->format('Y-m-d');
        $last_day = Carbon::parse($start_date->lastOfMonth())->format('Y-m-d');

        $period = CarbonPeriod::create($first_day, $last_day);

        return view('admin.reports.projects.show', compact(
            'month',
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

        return redirect()->route('admin.reports.projects.filter', [$from_date, $to_date])->withInput();
    }

    public function filter($from_date, $to_date)
    {
        $from_date = $from_date . ' 00:00:00';
        $to_date = $to_date . ' 23:59:59';

        $projects = Project::whereBetween('created_at', [
            $from_date, $to_date
        ])
        ->where('status', '!=', ProjectStatus::INACTIVE)
        ->get();

        $total_count = Project::whereBetween('created_at', [
            $from_date, $to_date
        ])
        ->where('status', '!=', ProjectStatus::INACTIVE)
        ->count();

        $grand_total = Project::whereBetween('created_at', [
            $from_date, $to_date
        ])
        ->where('status', '!=', ProjectStatus::INACTIVE)
        ->sum('total');

        $asf_total = Project::whereBetween('created_at', [
            $from_date, $to_date
        ])
        ->where('status', '!=', ProjectStatus::INACTIVE)
        ->sum('asf');

        $vat_total = Project::whereBetween('created_at', [
            $from_date, $to_date
        ])
        ->where('status', '!=', ProjectStatus::INACTIVE)
        ->sum('vat');

        return view('admin.reports.projects.view', compact(
            'total_count',
            'grand_total',
            'asf_total',
            'vat_total',
            'projects',
            'from_date',
            'to_date'
        ));
    }
}
