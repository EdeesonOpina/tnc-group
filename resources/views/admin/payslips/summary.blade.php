@include('layouts.auth.header')
@php
    use Carbon\Carbon;
    use App\Models\PayslipStatus;
    use App\Models\PayslipAttendance;
    use App\Models\PayslipAttendanceStatus;

    $salary_per_hour = PayslipAttendance::where('payslip_id', $payslip->id)
                                ->where('status', PayslipAttendanceStatus::APPROVED)
                                ->first()
                                ->salary_per_hour;

    $regular_hours = PayslipAttendance::where('payslip_id', $payslip->id)
                            ->where('status', PayslipAttendanceStatus::APPROVED)
                            ->sum('hours_rendered');

    $regular_total = PayslipAttendance::where('payslip_id', $payslip->id)
                            ->where('status', PayslipAttendanceStatus::APPROVED)
                            ->sum('total');

    $overtime_hours = PayslipAttendance::where('payslip_id', $payslip->id)
                            ->where('type', 'OT')
                            ->where('status', PayslipAttendanceStatus::APPROVED)
                            ->sum('hours_rendered');

    $overtime_total = PayslipAttendance::where('payslip_id', $payslip->id)
                            ->where('type', 'OT')
                            ->where('status', PayslipAttendanceStatus::APPROVED)
                            ->sum('total');
@endphp

<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('hr.payslips') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('hr.payslips.view', [$user->id]) }}">Payslip</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $user->firstname }} {{ $user->lastname }}</li>
                </ol>
            </nav>
            <h1 class="m-0">{{ $user->lastname }}, {{ $user->firstname }} Attendance</h1>
        </div>
        <a href="{{ route('hr.payslips.details.print', [$payslip->id]) }}">
            <button type="button" class="btn btn-light" id="margin-right"><i class="fa fa-print" id="margin-right"></i>Print</button>
        </a>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')

    <a href="{{ route('hr.payslips.details', [$payslip->id]) }}">
        <button type="button" class="btn btn-light" id="margin-right"><i class="fa fa-list" id="margin-right"></i>Detailed Version</button>
    </a>

    <a href="{{ route('hr.payslips.summary', [$payslip->id]) }}">
        <button type="button" class="btn btn-primary" id="margin-right"><i class="fa fa-address-card" id="margin-right"></i>Summarized Version</button>
    </a>
    <br><br>

    <div class="row">
        <div class="col">
            <div id="spaced-card" class="card card-body">
                <img src="{{ url(env('APP_LOGO_WITH_TEXT')) }}" width="150px">
                <br>
                <div class="row">
                    <div class="col">
                        <strong>Name</strong><br>
                        {{ $user->firstname }} {{ $user->lastname }}
                    </div>

                    <div class="col">
                        <strong>Position</strong><br>
                        {{ $user->position }}
                    </div>

                    <div class="col">
                        <strong>Salary</strong><br>
                        P{{ number_format($user->salary, 2) }}
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-4">
                        <strong>From Date</strong><br>
                        {{ Carbon::parse($from_date)->format('M d Y') }}
                    </div>

                    <div class="col-md-4">
                        <strong>To Date</strong><br>
                        {{ Carbon::parse($to_date)->format('M d Y') }}
                    </div>
                </div>
                <br>

                <div class="row">
                    <div class="col">
                        <table class="table mb-0 thead-border-top-0 table-bordered">
                            <thead class="text-center">
                                <tr>
                                    <th id="compact-table" colspan="2">Compensation</th>
                                </tr>
                            </thead>
                            <tbody class="list" id="companies">
                                <tr>
                                    <td id="compact-table">BASIC PAY <small>({{ $payslip->hours }}:00)</small></td>
                                    <td id="compact-table">P{{ number_format($payslip->total - $overtime_total, 2) }}</td>
                                </tr>
                                <tr>
                                    <td id="compact-table">OT PAY <small>({{ $overtime_hours }}:00)</small></td>
                                    <td id="compact-table">P{{ number_format($overtime_total, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col">
                        <table class="table mb-0 thead-border-top-0 table-bordered">
                            <thead class="text-center">
                                <tr>
                                    <th id="compact-table" colspan="2">Deductions</th>
                                </tr>
                            </thead>

                            <tbody class="list" id="companies">
                                <tr>
                                    <td id="compact-table">De Minimis</td>
                                    <td id="compact-table">P{{ number_format(0, 2) }}</td>
                                </tr>

                                <tr>
                                    <td id="compact-table">Late / Undertime</td>
                                    <td id="compact-table">P{{ number_format(0, 2) }}</td>
                                </tr>

                                <tr>
                                    <td id="compact-table">Absences</td>
                                    <td id="compact-table">P{{ number_format(0, 2) }}</td>
                                </tr>
                            </tbody>

                            <thead class="text-center">
                                <tr>
                                    <th id="compact-table" colspan="2">Contributions</th>
                                </tr>
                            </thead>

                            <tbody class="list" id="companies">
                                <tr>
                                    <td id="compact-table">W-TAX</td>
                                    <td id="compact-table"><strong style="color:red">-P{{ number_format($payslip->w_tax, 2) }}</strong></td>
                                </tr>

                                <tr>
                                    <td id="compact-table">SSS</td>
                                    <td id="compact-table"><strong style="color:red">-P{{ number_format($payslip->sss, 2) }}</strong></td>
                                </tr>

                                <tr>
                                    <td id="compact-table">SSS MPF</td>
                                    <td id="compact-table">P{{ number_format(0, 2) }}</td>
                                </tr>

                                <tr>
                                    <td id="compact-table">PHILHEALTH</td>
                                    <td id="compact-table"><strong style="color:red">-P{{ number_format($payslip->philhealth, 2) }}</strong></td>
                                </tr>

                                <tr>
                                    <td id="compact-table">HDMF</td>
                                    <td id="compact-table"><strong style="color:red">-P{{ number_format($payslip->pagibig, 2) }}</strong></td>
                                </tr>

                                <tr>
                                    <td id="compact-table">SSS Calamity Loan</td>
                                    <td id="compact-table">P{{ number_format(0, 2) }}</td>
                                </tr>

                                <tr>
                                    <td id="compact-table">SSS Salary Loan</td>
                                    <td id="compact-table">P{{ number_format(0, 2) }}</td>
                                </tr>

                                <tr>
                                    <td id="compact-table">HDMF Salary Loan</td>
                                    <td id="compact-table">P{{ number_format(0, 2) }}</td>
                                </tr>

                                <tr>
                                    <td id="compact-table">HDMF Calamity Loan</td>
                                    <td id="compact-table">P{{ number_format(0, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col">
                        <table class="table mb-0 thead-border-top-0 table-bordered">
                            <thead class="text-center">
                                <tr>
                                    <th id="compact-table" colspan="2">Year To Date</th>
                                </tr>
                            </thead>
                            <tbody class="list" id="companies">
                                <tr>
                                    <td id="compact-table">Taxable Gross</td>
                                    <td id="compact-table">P{{ number_format(0, 2) }}</td>
                                </tr>

                                <tr>
                                    <td id="compact-table">Tax</td>
                                    <td id="compact-table">P{{ number_format(0, 2) }}</td>
                                </tr>

                                <tr>
                                    <td id="compact-table">SSS</td>
                                    <td id="compact-table">P{{ number_format(0, 2) }}</td>
                                </tr>

                                <tr>
                                    <td id="compact-table">PHIC</td>
                                    <td id="compact-table">P{{ number_format(0, 2) }}</td>
                                </tr>

                                <tr>
                                    <td id="compact-table">HDMF</td>
                                    <td id="compact-table">P{{ number_format(0, 2) }}</td>
                                </tr>

                                <tr>
                                    <td id="compact-table">Gross Income</td>
                                    <td id="compact-table">P{{ number_format(0, 2) }}</td>
                                </tr>

                                <tr>
                                    <td id="compact-table">Non Taxable</td>
                                    <td id="compact-table">P{{ number_format(0, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <br>

                <div class="row">
                    <div class="col">
                        <table class="table mb-0 thead-border-top-0 table-bordered">
                            <tbody class="list" id="companies">
                                <tr>
                                    <td id="compact-table">Total Compensation</td>
                                    <td id="compact-table">P{{ number_format($payslip->total, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col">
                        <table class="table mb-0 thead-border-top-0 table-bordered">
                            <tbody class="list" id="companies">
                                <tr>
                                    <td id="compact-table">Total Deductions</td>
                                    <td id="compact-table">P{{ number_format(0, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col">
                        <table class="table mb-0 thead-border-top-0 table-bordered">
                            <tbody class="list" id="companies">
                                <tr>
                                    <td id="compact-table">Net Pay</td>
                                    <td id="compact-table">P{{ number_format(0, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


@include('layouts.auth.footer')