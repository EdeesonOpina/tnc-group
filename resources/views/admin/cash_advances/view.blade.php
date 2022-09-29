@include('layouts.auth.header')
@php
    use App\Models\CashAdvanceStatus;
    use App\Models\CashAdvancePaymentStatus;
@endphp
<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('accounting.cash-advances') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('accounting.cash-advances') }}">Cash Advances</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Cash Advance Payments</li>
                </ol>
            </nav>
            <h1 class="m-0">Cash Advance Payments</h1>
        </div>
        @if ($cash_advance->status == CashAdvanceStatus::PARTIALLY_PAID || $cash_advance->status == CashAdvanceStatus::UNPAID)
            <a href="#" data-toggle="modal" data-target="#cash-advance-payment-{{ $cash_advance->id }}" id="space-table"><button class="btn btn-success">Pay</button></a>
        @endif
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h4 class="card-header__title flex m-0">Cash Advance Payments</h4>
                    <div data-toggle="flatpickr" data-flatpickr-wrap="true" data-flatpickr-static="true" data-flatpickr-mode="range" data-flatpickr-alt-format="d/m/Y" data-flatpickr-date-format="d/m/Y">
                        
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table">Price</th>
                                <th id="compact-table">Status</th>
                                <th id="compact-table">Paid At</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                            @foreach($cash_advance_payments as $cash_advance_payment)
                                <tr>
                                    <td id="compact-table">
                                        <b>P{{ number_format($cash_advance_payment->price, 2) }}</b>
                                        <div class="d-flex">
                                            @if ($cash_advance_payment->status == CashAdvancePaymentStatus::PENDING)
                                                <a href="#" id="margin-right" data-href="{{ route('accounting.cash-advances.approve', [$cash_advance_payment->id, $cash_advance_payment->id]) }}" data-toggle="modal" data-target="#confirm-action">Approve</a> | 
                                                <a href="#" id="space-table" data-href="{{ route('accounting.cash-advances.disapprove', [$cash_advance_payment->id, $cash_advance_payment->id]) }}" data-toggle="modal" data-target="#confirm-action">Disapprove</a>
                                            @endif
                                        </div>
                                    </td>
                                    <td id="compact-table">
                                        @if ($cash_advance_payment->status == CashAdvancePaymentStatus::PENDING)
                                            <div class="badge badge-warning ml-2">PENDING</div>
                                        @elseif ($cash_advance_payment->status == CashAdvancePaymentStatus::APPROVED)
                                            <div class="badge badge-success ml-2">APPROVED</div>
                                        @elseif ($cash_advance_payment->status == CashAdvancePaymentStatus::DISAPPROVED)
                                            <div class="badge badge-danger ml-2">DISAPPROVED</div>
                                        @endif
                                    </td>
                                    <td id="compact-table">{{ $cash_advance_payment->created_at->format('M d Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div style="padding-top: 20px">
                        {{ $cash_advance_payments->links() }}
                    </div>

                    @if (count($cash_advance_payments) <= 0)
                        <div style="padding: 20px">
                            <center><i class="material-icons icon-16pt mr-1 text-muted">assignment</i> No record/s found</center>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col">
            <div id="spaced-card" class="card card-body">
                <h3>Cash Advance Details</h3>
                <br>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>CA #</h6>
                            <strong>{{ $cash_advance->reference_number }}</strong>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Status</h6>
                            @if ($cash_advance->status == CashAdvanceStatus::PARTIALLY_PAID)
                              <div class="badge badge-warning">PARTIALLY PAID</div>
                            @elseif ($cash_advance->status == CashAdvanceStatus::UNPAID)
                              <div class="badge badge-info">UNPAID</div>
                            @elseif ($cash_advance->status == CashAdvanceStatus::FULLY_PAID)
                              <div class="badge badge-success">FULLY PAID</div>
                            @endif

                            @if ($cash_advance->status == CashAdvanceStatus::CANCELLED)
                                <div class="badge badge-danger ml-2">CANCELLED</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Employee</h6>
                            {{ $cash_advance->user->firstname }} {{ $cash_advance->user->lastname }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Date Borrowed</h6>
                            {{ $cash_advance->reason }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Reason</h6>
                            {{ $cash_advance->reason }}
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col">
                      <strong>Grand Total:</strong>
                    </div>
                    <div class="col">
                      <strong>P{{ number_format($cash_advance->price, 2) }}</strong>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col">
                      <strong>Paid Balance:</strong>
                    </div>
                    <div class="col">
                      <strong>P{{ number_format($paid_balance, 2) }}</strong>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col">
                      <strong>Remaining Balance:</strong>
                    </div>
                    <div class="col">
                      <strong>P{{ number_format(($cash_advance->price - $paid_balance), 2) }}</strong>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@include('layouts.auth.footer')