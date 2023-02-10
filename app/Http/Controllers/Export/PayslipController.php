<?php

namespace App\Http\Controllers\Export;

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
    public function print_details($payslip_id)
    {
        $payslip = Payslip::find($payslip_id);
        $user = User::find($payslip->user_id);
        $from_date = $payslip->from_date;
        $to_date = $payslip->to_date;

        $query = PayslipAttendance::where('payslip_id', $payslip->id)
                            ->where('status', PayslipAttendanceStatus::APPROVED);

        $attendances = $query->get();

        $total_deductions = $payslip->w_tax + $payslip->sss + $payslip->philhealth + $payslip->pagibig + $payslip->gsis;

        return view('admin.payslips.prints.details', compact(
            'total_deductions',
            'payslip',
            'from_date',
            'to_date',
            'attendances',
            'user'
        ));
    }
}
