<?php

namespace App\Http\Controllers\Admin\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Auth;
use Mail;
use Validator;
use App\Models\User;
use App\Models\Country;
use App\Models\Company;
use App\Models\CompanyStatus;
use App\Models\UserStatus;
use App\Models\Payslip;
use App\Models\PayslipStatus;
use App\Models\PayslipAttendance;
use App\Models\PayslipAttendanceStatus;

class PayslipController extends Controller
{
    public function show()
    {
        $users = User::orderBy('created_at', 'desc')
                    ->where('status', '!=', UserStatus::INACTIVE)
                    ->paginate(15);

        return view('admin.payslips.show', compact(
            'users'
        ));
    }

    public function date_select($user_id)
    {
        $user = User::find($user_id);

        return view('admin.payslips.date-select', compact(
            'user'
        ));
    }

    public function date_select_search(Request $request)
    {
        $user_id = $request->user_id ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('hr.payslips.deductions', [$user_id, $from_date, $to_date])->withInput();
    }

    public function deductions($user_id, $from_date, $to_date)
    {
        $user = User::find($user_id);
        $from_date = $from_date;
        $to_date = $to_date;

        $query = PayslipAttendance::where('status', PayslipAttendanceStatus::APPROVED);

        $query->whereBetween('from_date', [$from_date . ' 00:00:00', $to_date . ' 23:59:59']);
        $query->whereBetween('to_date', [$from_date . ' 00:00:00', $to_date . ' 23:59:59']);

        $attendances = $query->get();

        return view('admin.payslips.deductions', compact(
            'attendances',
            'from_date',
            'to_date',
            'user'
        ));
    }

    public function search(Request $request)
    {
        $name = $request->name ?? '*';
        $role = $request->role ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('hr.payslips.filter', [$name, $role, $status, $from_date, $to_date])->withInput();
    }

    public function filter($name, $role, $status, $from_date, $to_date)
    {
        $query = User::where('status', '!=', UserStatus::INACTIVE)
                    ->orderBy('created_at', 'desc');

        if ($name != '*') {
            $query->whereRaw("concat(firstname, ' ', lastname) like '%" . $name . "%' ");
            
            /* original query */
            // $query->where('firstname', $name);
            // $query->orWhere('lastname', $name);
        }

        if ($role != '*') {
            $query->where('role', $role);
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

        $users = $query->paginate(15);

        return view('admin.payslips.show', compact(
            'users'
        ));
    }

    public function view($user_id)
    {
        $user = User::find($user_id);
        $payslips = Payslip::where('user_id', $user_id)
                        ->where('status', '!=', PayslipStatus::INACTIVE)
                        ->paginate(30);

        return view('admin.payslips.view', compact(
            'payslips',
            'user'
        ));
    }

    public function details($payslip_id)
    {
        $payslip = Payslip::find($payslip_id);
        $user = User::find($payslip->user_id);
        $from_date = $payslip->from_date;
        $to_date = $payslip->to_date;

        $query = PayslipAttendance::where('payslip_id', $payslip->id)
                            ->where('status', PayslipAttendanceStatus::APPROVED);

        $attendances = $query->get();

        $total_deductions = $payslip->w_tax + $payslip->sss + $payslip->philhealth + $payslip->pagibig + $payslip->gsis;

        return view('admin.payslips.details', compact(
            'total_deductions',
            'payslip',
            'from_date',
            'to_date',
            'attendances',
            'user'
        ));
    }

    public function summary($payslip_id)
    {
        $payslip = Payslip::find($payslip_id);
        $user = User::find($payslip->user_id);
        $from_date = $payslip->from_date;
        $to_date = $payslip->to_date;

        $query = PayslipAttendance::where('payslip_id', $payslip->id)
                            ->where('status', PayslipAttendanceStatus::APPROVED);

        $attendances = $query->get();

        $total_deductions = $payslip->w_tax + $payslip->sss + $payslip->philhealth + $payslip->pagibig + $payslip->gsis;

        return view('admin.payslips.summary', compact(
            'total_deductions',
            'payslip',
            'from_date',
            'to_date',
            'attendances',
            'user'
        ));
    }

    public function manage($user_id)
    {
        $user = User::find($user_id);
        $attendances = PayslipAttendance::where('user_id', $user_id)
                            ->where('status', '!=', PayslipAttendanceStatus::INACTIVE)
                            ->paginate(30);

        return view('admin.payslips.manage', compact(
            'attendances',
            'user'
        ));
    }

    public function apply_deductions(Request $request)
    {
        $rules = [
            'w_tax' => 'required',
            'sss' => 'required',
            'philhealth' => 'required',
            'pagibig' => 'required',
            'gsis' => 'optional',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = request()->all(); // get all request
        $data['status'] = PayslipStatus::PENDING; // if you want to insert to a specific column
        $payslip = Payslip::create($data); // create data in a model

        $attendances = $request->attendances;

        foreach ($attendances as $attendance) {
            $atd = PayslipAttendance::find($attendance);
            $atd->payslip_id = $payslip->id;
            $atd->save();
        }

        $request->session()->flash('success', 'Data has been added');
        return redirect()->route('hr.payslips.view', [$payslip->user_id]);
    }
}