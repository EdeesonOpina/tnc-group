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

class TimeController extends Controller
{
    public function add($user_id)
    {
        $user = User::find($user_id);

        return view('admin.payslips.time.add', compact(
            'user'
        ));
    }
    
    public function create(Request $request)
    {
        $rules = [
            'user_id' => 'required',
            'from_date' => 'required',
            'to_date' => 'required',
            'time_in' => 'required',
            'time_out' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        /* get the hours rendered */
        $from = Carbon::createFromFormat('Y-m-d H:m', $request->from_date . ' ' . $request->time_in);
        $to = Carbon::createFromFormat('Y-m-d H:m', $request->to_date . ' ' . $request->time_out);
        $hours_rendered = $to->diffInHours($from) - 1; // no break with 1 hour break

        /* convert monthly salary to per day to hourly */
        $user = User::find($request->user_id);
        $salary_per_hour = (($user->salary / 40) / 8);

        if ($hours_rendered > 8) {
            $hours_rendered = 8;
        }

        $data = request()->all(); // get all request
        $data['hours_rendered'] = $hours_rendered;
        $data['salary_per_hour'] = $salary_per_hour;
        $data['total'] = $salary_per_hour * $hours_rendered;
        $data['status'] = PayslipAttendanceStatus::PENDING; // if you want to insert to a specific column
        PayslipAttendance::create($data); // create data in a model

        $request->session()->flash('success', 'Data has been added');
        return redirect()->route('hr.payslips.manage', [$user->id]);
    }

    public function approve(Request $request, $attendance_id)
    {
        $attendance = PayslipAttendance::find($attendance_id);
        $attendance->status = PayslipAttendanceStatus::APPROVED; // mark data as cancelled
        $attendance->save();

        $request->session()->flash('success', 'Data has been approved');
        return back();
    }

    public function disapprove(Request $request, $attendance_id)
    {
        $attendance = PayslipAttendance::find($attendance_id);
        $attendance->status = PayslipAttendanceStatus::DISAPPROVED; // mark data as cancelled
        $attendance->save();

        $request->session()->flash('success', 'Data has been disapproved');
        return back();
    }

    public function approve_all(Request $request, $user_id)
    {
        DB::table('payslip_attendance')
        ->where('user_id', $user_id)
        ->update([
            'status' => PayslipAttendanceStatus::APPROVED,
        ]);

        $request->session()->flash('success', 'Data has been approved');
        return back();
    }

    public function disapprove_all(Request $request, $user_id)
    {
        DB::table('payslip_attendance')
        ->where('user_id', $user_id)
        ->update([
            'status' => PayslipAttendanceStatus::DISAPPROVED,
        ]);

        return User::find($user_id)->firstname;

        $request->session()->flash('success', 'Data has been disapproved');
        return back();
    }

    public function delete_all(Request $request, $user_id)
    {
        DB::table('payslip_attendance')
        ->where('user_id', $user_id)
        ->update([
            'status' => PayslipAttendanceStatus::INACTIVE,
        ]);

        $request->session()->flash('success', 'Data has been deleted');
        return back();
    }
}
