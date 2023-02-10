@include('layouts.auth.header')
@php
    use Carbon\Carbon;
    use App\Models\PayslipAttendanceStatus;

    $total_hours = 0;
    $total_income = 0;
@endphp

<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('hr.payslips') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('hr.payslips.view', [$user->id]) }}">Payslip</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $user->firstname }} {{ $user->lastname }}</li>
                </ol>
            </nav>
            <h1 class="m-0">{{ $user->firstname }} {{ $user->lastname }} Payslip</h1>
        </div>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')

    <div class="row">
        <div class="col-md-4">
            <div id="spaced-card" class="card card-body">
                <div class="row">
                    <div class="col">
                        <h3>Create Payslip</h3>
                        <br>

                        <form action="{{ route('hr.payslips.deductions.apply') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        @foreach($attendances as $attendance)
                            @php
                                $total_hours += $attendance->hours_rendered;
                                $total_income += $attendance->hours_rendered * $attendance->salary_per_hour;
                            @endphp
                            <input type="hidden" name="attendances[]" value="{{ $attendance->id }}">
                        @endforeach

                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <input type="hidden" name="hours" value="{{ $total_hours }}">
                        <input type="hidden" name="total" value="{{ $total_income }}">
                        <input type="hidden" name="from_date" value="{{ $from_date }}">
                        <input type="hidden" name="to_date" value="{{ $to_date }}">
                        
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Witholding Tax</label>
                                    <input type="text" name="w_tax" class="form-control" value="{{ old('w_tax') ?? '0.00' }}">
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label>SSS</label>
                                    <input type="text" name="sss" class="form-control" value="{{ old('sss') ?? '0.00' }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Philhealth</label>
                                    <input type="text" name="philhealth" class="form-control" value="{{ old('philhealth') ?? '0.00' }}">
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label>PAGIBIG</label>
                                    <input type="text" name="pagibig" class="form-control" value="{{ old('pagibig') ?? '0.00' }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>GSIS</label>
                                    <input type="text" name="gsis" class="form-control" value="{{ old('gsis') ?? '0.00' }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group m-0">
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div id="spaced-card" class="card card-body">
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
                                <td><strong>{{ $total_hours }}</strong></td>
                                <td><strong>Income</strong></td>
                                <td><strong>P{{ number_format($total_income, 2) }}</strong></td>
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