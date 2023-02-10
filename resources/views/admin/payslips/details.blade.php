@include('layouts.auth.header')
@php
    use Carbon\Carbon;
    use App\Models\PayslipAttendanceStatus;
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
            <h1 class="m-0">{{ $user->firstname }} {{ $user->lastname }} Attendance</h1>
        </div>
        <a href="{{ route('hr.payslips.details.print', [$payslip->id]) }}">
            <button type="button" class="btn btn-light" id="margin-right"><i class="fa fa-print" id="margin-right"></i>Print</button>
        </a>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')

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

                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table">Date</th>
                                <th id="compact-table">Type</th>
                                <th id="compact-table">Time In</th>
                                <th id="compact-table">Time Out</th>
                                <th id="compact-table">Hours</th>
                                <th id="compact-table">Per Hour</th>
                                <th id="compact-table">Total</th>
                                <th id="compact-table">Status</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                            @foreach($attendances as $attendance)
                                <tr>
                                    <td id="compact-table"><i class="material-icons icon-16pt text-muted mr-1">today</i> {{ Carbon::parse($attendance->from_date)->format('M d Y') }}
                                        <div class="d-flex">
                                            @if ($attendance->status == PayslipAttendanceStatus::PENDING)
                                                <a href="#" data-href="{{ route('hr.payslips.time.approve', [$attendance->id]) }}" data-toggle="modal" data-target="#confirm-action" style="margin-right: 7px">Approve</a> | 
                                                <a href="#" data-href="{{ route('hr.payslips.time.disapprove', [$attendance->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Disapprove</a>
                                            @endif 
                                        </div>
                                    </td>
                                    <td id="compact-table">{{ $attendance->type }}</td>
                                    <td id="compact-table">{{ $attendance->time_in }}</td>
                                    <td id="compact-table">{{ $attendance->time_out }}</td>
                                    <td id="compact-table">{{ $attendance->hours_rendered }}</td>
                                    <td id="compact-table">P{{ number_format($attendance->salary_per_hour, 2) }}</td>
                                    <td id="compact-table">P{{ number_format($attendance->total, 2) }}</td>
                                    <td>
                                        @if ($attendance->status == PayslipAttendanceStatus::PENDING)
                                            <div class="badge badge-warning ml-2">PENDING</div>
                                        @elseif ($attendance->status == PayslipAttendanceStatus::APPROVED)
                                            <div class="badge badge-success ml-2">APPROVED</div>
                                        @elseif ($attendance->status == PayslipAttendanceStatus::DISAPPROVED)
                                            <div class="badge badge-danger ml-2">DISAPPROVED</div>
                                        @elseif ($attendance->status == PayslipAttendanceStatus::INACTIVE)
                                            <div class="badge badge-danger ml-2">INACTIVE</div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="3"></td>
                                <td><strong>Hours</strong></td>
                                <td><strong>{{ $payslip->hours }}</strong></td>
                                <td><strong>Income</strong></td>
                                <td><strong>P{{ number_format($payslip->total, 2) }}</strong></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="5"></td>
                                <td><strong>W-Tax</strong></td>
                                <td><strong>P{{ number_format($payslip->w_tax, 2) }}</strong></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="5"></td>
                                <td><strong>SSS</strong></td>
                                <td><strong>P{{ number_format($payslip->sss, 2) }}</strong></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="5"></td>
                                <td><strong>PHILHEALTH</strong></td>
                                <td><strong>P{{ number_format($payslip->philhealth, 2) }}</strong></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="5"></td>
                                <td><strong>PAGIBIG</strong></td>
                                <td><strong>P{{ number_format($payslip->pagibig, 2) }}</strong></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="5"></td>
                                <td><strong>GSIS</strong></td>
                                <td><strong>P{{ number_format($payslip->gsis, 2) }}</strong></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="5"></td>
                                <td><strong>Total Income</strong></td>
                                <td><strong>P{{ number_format(($payslip->total - $total_deductions), 2) }}</strong></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>

                    @if (count($attendances) <= 0)
                        <div style="padding: 20px">
                            <center><i class="material-icons icon-16pt mr-1 text-muted">assignment</i> No record/s found</center>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>


@include('layouts.auth.footer')