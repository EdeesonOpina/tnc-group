@include('layouts.auth.header')
@php
    use App\Models\Supply;
    use App\Models\ItemStatus;
    use App\Models\SupplyStatus;
    use App\Models\PaymentCreditStatus;
    use App\Models\PaymentReceiptStatus;
    use App\Models\PaymentCreditRecordStatus;
@endphp
<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('accounting.payment-credits') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('accounting.payment-credits') }}">Credits</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Credit Records</li>
                </ol>
            </nav>
            <h1 class="m-0">Credit Records</h1>
        </div>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h4 class="card-header__title flex m-0">Credit Records</h4>
                    <div data-toggle="flatpickr" data-flatpickr-wrap="true" data-flatpickr-static="true" data-flatpickr-mode="range" data-flatpickr-alt-format="d/m/Y" data-flatpickr-date-format="d/m/Y">
                        
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table"></th>
                                <th id="compact-table">MOP</th>
                                <th id="compact-table">OR#</th>
                                <th id="compact-table">Term</th>
                                <th id="compact-table">Price</th>
                                <th id="compact-table">Status</th>
                                <th id="compact-table">Paid At</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                            @foreach($payment_credit_records as $payment_credit_record)
                                <tr>
                                    <td id="compact-table">
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex align-items-center">
                                                @if ($payment_credit_record->image)
                                                    <a href="{{ url($payment_credit_record->image) }}" target="_blank">
                                                        <img src="{{ url($payment_credit_record->image) }}" width="100px">
                                                    </a>
                                                @else
                                                    <img src="{{ url(env('APP_ICON')) }}" width="40px" style="margin-right: 7px;">
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td id="compact-table">
                                        <b>{{ $payment_credit_record->mop }}</b>
                                        <br>
                                        @if ($payment_credit_record->status == PaymentCreditRecordStatus::PENDING)
                                            <a href="#" data-href="{{ route('accounting.payment-credits.approve', [$payment_credit_record->id, $payment_credit_record->id]) }}" data-toggle="modal" data-target="#confirm-action">Approve</a> | 
                                            <a href="#" data-href="{{ route('accounting.payment-credits.disapprove', [$payment_credit_record->id, $payment_credit_record->id]) }}" data-toggle="modal" data-target="#confirm-action">Disapprove</a>
                                        @endif
                                    </td>
                                    <td id="compact-table"><b>{{ $payment_credit_record->bir_number }}</b></td>
                                    <td id="compact-table"><b>{{ $payment_credit_record->term }}</b></td>
                                    <td id="compact-table"><b>P{{ number_format($payment_credit_record->price, 2) }}</b></td>
                                    <td id="compact-table">
                                        @if ($payment_credit_record->status == PaymentCreditRecordStatus::PENDING)
                                            <div class="badge badge-warning ml-2">PENDING</div>
                                        @elseif ($payment_credit_record->status == PaymentCreditRecordStatus::APPROVED)
                                            <div class="badge badge-success ml-2">APPROVED</div>
                                        @elseif ($payment_credit_record->status == PaymentCreditRecordStatus::DISAPPROVED)
                                            <div class="badge badge-danger ml-2">DISAPPROVED</div>
                                        @endif
                                    </td>
                                    <td id="compact-table">{{ $payment_credit_record->created_at->format('M d Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div style="padding-top: 20px">
                        {{ $payment_credit_records->links() }}
                    </div>

                    @if (count($payment_credit_records) <= 0)
                        <div style="padding: 20px">
                            <center><i class="material-icons icon-16pt mr-1 text-muted">assignment</i> No record/s found</center>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col">
            <div id="spaced-card" class="card card-body">
                <h3>Payment Details</h3>
                <br>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>SO #</h6>
                            <strong><a href="{{ route('accounting.payments.view', [$payment_credit->so_number]) }}">{{ $payment_credit->so_number }}</a></strong>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>SI #</h6>
                            <strong>{{ $payment_credit->invoice_number }}</strong>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Status</h6>
                            @if ($payment_credit->status == PaymentCreditStatus::PARTIALLY_PAID)
                              <div class="badge badge-info">PARTIALLY PAID</div>
                            @elseif ($payment_credit->status == PaymentCreditStatus::CREDIT)
                              <div class="badge badge-warning">CREDIT</div>
                            @elseif ($payment_credit->status == PaymentCreditStatus::PENDING)
                              <div class="badge badge-warning">PENDING</div>
                            @elseif ($payment_credit->status == PaymentCreditStatus::OVERDUE)
                              <div class="badge badge-danger">OVERDUE</div>
                            @elseif ($payment_credit->status == PaymentCreditStatus::UNPAID)
                              <div class="badge badge-info">UNPAID</div>
                            @elseif ($payment_credit->status == PaymentCreditStatus::FULLY_PAID)
                              <div class="badge badge-success">FULLY PAID</div>
                            @endif

                            @if ($payment_credit->payment_receipt->status == PaymentReceiptStatus::CANCELLED)
                                <div class="badge badge-danger ml-2">CANCELLED</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Customer</h6>
                            {{ $main_payment->user->firstname }} {{ $main_payment->user->lastname }}
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col">
                      <strong>Grand Total:</strong>
                    </div>
                    <div class="col">
                      <strong>P{{ number_format($grand_total, 2) }}</strong>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col">
                      <strong>Paid Balance:</strong>
                    </div>
                    <div class="col">
                      <strong>P{{ number_format($payment_credit->price, 2) }}</strong>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col">
                      <strong>Remaining Balance:</strong>
                    </div>
                    <div class="col">
                      <strong>P{{ number_format(($grand_total - $payment_credit->price), 2) }}</strong>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@include('layouts.auth.footer')