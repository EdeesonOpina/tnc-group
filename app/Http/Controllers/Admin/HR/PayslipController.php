<?php

namespace App\Http\Controllers\Admin\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
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

    public function search(Request $request)
    {
        $name = $request->name ?? '*';
        $role = $request->role ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('admin.payslips.filter', [$name, $role, $status, $from_date, $to_date])->withInput();
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

    public function add($user_id)
    {
        $user = User::find($user_id);

        return view('admin.payslips.time.add', compact(
            'user'
        ));
    }

    public function view($user_id)
    {
        $user = User::find($user_id);
        $attendances = PayslipAttendance::where('status', '!=', PayslipStatus::INACTIVE)
                                        ->paginate(30);

        return view('admin.payslips.view', compact(
            'attendances',
            'user'
        ));
    }

    public function manage($user_id)
    {
        $user = User::find($user_id);
        $attendances = PayslipAttendance::where('status', '!=', PayslipStatus::INACTIVE)
                                        ->paginate(30);

        return view('admin.payslips.manage', compact(
            'attendances',
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
        $from = Carbon::createFromFormat('Y-m-d H:i', $request->from_date . ' ' . $request->time_in);
        $to = Carbon::createFromFormat('Y-m-d H:i', $request->to_date . ' ' . $request->time_out);
        $hours_rendered = $to->diffInHours($from) - 1; // no break with 1 hour break

        /* convert monthly salary to hourly */
        $user = User::find($request->user_id);
        $salary_per_hour = ($user->salary / 40);

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
}