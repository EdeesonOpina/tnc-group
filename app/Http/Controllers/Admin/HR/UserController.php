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

class UserController extends Controller
{
    public function salary(Request $request)
    {
        $rules = [
            'salary' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = $request->all();

        $user = User::find($request->user_id);
        $user->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');
        return back();
    }
}
