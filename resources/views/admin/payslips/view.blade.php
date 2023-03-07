@include('layouts.auth.header')
@php
    use Carbon\Carbon;
    use App\Models\PayslipStatus;
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
            <h1 class="m-0">{{ $user->lastname }}, {{ $user->firstname }} Attendance</h1>
        </div>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')

    <div class="row">
        <div class="col">
            <div id="spaced-card" class="card card-body">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Name</h6>
                            {{ $user->firstname }} {{ $user->lastname }}
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Position</h6>
                            {{ $user->position }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Salary</h6>
                            P{{ number_format($user->salary, 2) }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Email Address</h6>
                            {{ $user->email }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Phone</h6>
                            {{ $user->phone ?? 'N/A' }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Mobile</h6>
                            {{ $user->mobile }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Line Address 1</h6>
                            {{ $user->line_address_1 }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Line Address 2</h6>
                            {{ $user->line_address_2 }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h4 class="card-header__title flex m-0">{{ $user->lastname }}, {{ $user->firstname }} Attendance</h4>
                    <div data-toggle="flatpickr" data-flatpickr-wrap="true" data-flatpickr-static="true" data-flatpickr-mode="range" data-flatpickr-alt-format="d/m/Y" data-flatpickr-date-format="d/m/Y">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table">Date</th>
                                <th id="compact-table">W-Tax</th>
                                <th id="compact-table">SSS</th>
                                <th id="compact-table">PHILHEALTH</th>
                                <th id="compact-table">PAGIBIG</th>
                                <th id="compact-table">GSIS</th>
                                <th id="compact-table">Hours</th>
                                <th id="compact-table">Total</th>
                                <th id="compact-table">Status</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                            @foreach($payslips as $payslip)
                                <tr>
                                    <td id="compact-table"><i class="material-icons icon-16pt text-muted mr-1">today</i> <a href="{{ route('hr.payslips.details', [$payslip->id]) }}">{{ Carbon::parse($payslip->from_date)->format('M d Y') }}</a></td>
                                    <td id="compact-table">P{{ number_format($payslip->w_tax, 2) }}</td>
                                    <td id="compact-table">P{{ number_format($payslip->sss, 2) }}</td>
                                    <td id="compact-table">P{{ number_format($payslip->philhealth, 2) }}</td>
                                    <td id="compact-table">P{{ number_format($payslip->pagibig, 2) }}</td>
                                    <td id="compact-table">P{{ number_format($payslip->gsis, 2) }}</td>
                                    <td id="compact-table">{{ $payslip->hours }}</td>
                                    <td id="compact-table">P{{ number_format($payslip->total, 2) }}</td>
                                    <td>
                                        @if ($payslip->status == PayslipStatus::PENDING)
                                            <div class="badge badge-warning ml-2">PENDING</div>
                                        @elseif ($payslip->status == PayslipStatus::APPROVED)
                                            <div class="badge badge-success ml-2">APPROVED</div>
                                        @elseif ($payslip->status == PayslipStatus::DISAPPROVED)
                                            <div class="badge badge-danger ml-2">DISAPPROVED</div>
                                        @elseif ($payslip->status == PayslipStatus::INACTIVE)
                                            <div class="badge badge-danger ml-2">INACTIVE</div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if (count($payslips) <= 0)
                        <div style="padding: 20px">
                            <center><i class="material-icons icon-16pt mr-1 text-muted">assignment</i> No record/s found</center>
                        </div>
                    @endif
                </div>
            </div>
            {{ $payslips->links() }}
        </div>
    </div>
</div>


@include('layouts.auth.footer')