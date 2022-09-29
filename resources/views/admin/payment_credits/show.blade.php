@include('layouts.auth.header')
@php
    use Carbon\Carbon;
    use App\Models\POSVat;
    use App\Models\Payment;
    use App\Models\Inventory;
    use App\Models\POSDiscount;
    use App\Models\POSVatStatus;
    use App\Models\PaymentCredit;
    use App\Models\PaymentStatus;
    use App\Models\PaymentReceipt;
    use App\Models\InventoryStatus;
    use App\Models\POSDiscountStatus;
    use App\Models\PaymentCreditRecord;
    use App\Models\PaymentCreditStatus;
    use App\Models\PaymentReceiptStatus;
    use App\Models\PaymentCreditRecordStatus;
@endphp

<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('accounting.payment-credits') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Credits</li>
                </ol>
            </nav>
            <h1 class="m-0">Credits</h1>
        </div>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')
    @include('layouts.partials.payment-credits.top-tabs')

    <form action="{{ route('accounting.payment-credits.search') }}" method="post">
        {{ csrf_field() }}
        <div class="card card-form d-flex flex-column flex-sm-row">
            <div class="card-form__body card-body-form-group flex">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>SO#</label>
                            <input name="so_number" type="text" class="form-control" placeholder="Search by SO number" value="{{ old('so_number') }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>OR#</label>
                            <input name="bir_number" type="text" class="form-control" placeholder="Search by OR number" value="{{ old('bir_number') }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>SI#</label>
                            <input name="invoice_number" type="text" class="form-control" placeholder="Search by SI number" value="{{ old('invoice_number') }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Salesperson</label>
                            <select name="salesperson_id" class="form-control" data-toggle="select">
                                <option value="*">All</option>
                                @foreach ($salespersons as $salesperson)
                                    <option value="{{ $salesperson->id }}">{{ $salesperson->firstname }} {{ $salesperson->lastname }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>Customer</label>
                            <input name="name" type="text" class="form-control" placeholder="Search by name" value="{{ old('name') }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" data-toggle="select">
                                @if (old('status'))
                                    @if (old('status') != '*')
                                        @if (old('status') == PaymentCreditStatus::PARTIALLY_PAID)
                                            <option value="{{ old('status') }}">Partially Paid</option>
                                        @endif

                                        @if (old('status') == PaymentCreditStatus::FULLY_PAID)
                                            <option value="{{ old('status') }}">Fully Paid</option>
                                        @endif
                                    @endif
                                @endif
                                <option value="*">All</option>
                                <option value="{{ PaymentCreditStatus::CREDIT }}">Credit</option>
                                <option value="{{ PaymentCreditStatus::PARTIALLY_PAID }}">Partially Paid</option>
                                <option value="{{ PaymentCreditStatus::PENDING }}">Pending</option>
                                <option value="{{ PaymentCreditStatus::UNPAID }}">Unpaid</option>
                                <option value="{{ PaymentCreditStatus::FULLY_PAID }}">Fully Paid</option>
                                <option value="{{ PaymentCreditStatus::OVERDUE }}">Overdue</option>
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>From</label>
                            <input name="from_date" type="date" class="form-control" data-toggle="flatpickr" max="{{ date('Y-m-d') }}" value="{{ old('from_date') }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>To</label>
                            <input name="to_date" type="date" class="form-control" data-toggle="flatpickr" max="{{ date('Y-m-d') }}" value="{{ old('to_date') }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label><a href="{{ route('accounting.payment-credits') }}" id="no-underline">Clear Filters</a></label>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn bg-white border-left border-top border-top-sm-0 rounded-top-0 rounded-top-sm rounded-left-sm-0"><i class="material-icons text-primary icon-20pt">search</i></button>
        </div>
    </form>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h4 class="card-header__title flex m-0">Credits</h4>
                    <a href="#" data-href="{{ route('accounting.payment-credits.database.update') }}" data-toggle="modal" data-target="#confirm-action">
                        <button class="btn btn-light"><i class="fa fa-database" id="margin-right"></i>Update Database</button>
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table">SO#</th>
                                <th id="compact-table">OR#</th>
                                <th id="compact-table">SI#</th>
                                <th id="compact-table">Customer</th>
                                <th id="compact-table">Status</th>
                                <th id="compact-table">Total</th>
                                <th id="compact-table">VAT</th>
                                <th id="compact-table">Discount</th>
                                <th id="compact-table">Grand Total</th>
                                <th id="compact-table">Paid Balance</th>
                                <th id="compact-table">Remaining Balance</th>
                                <th id="compact-table">Due Date</th>
                                <th id="compact-table">Created At</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                            @foreach($payment_credits as $payment_credit)
                            @php
                                $payment_receipt = Payment::where('so_number', $payment_credit->so_number)->first();

                                $payments_count = Payment::where('so_number', $payment_credit->so_number)
                                                        ->where('status', '!=', PaymentStatus::INACTIVE)
                                                        ->sum('qty');

                                $payments_total = Payment::where('so_number', $payment_credit->so_number)
                                                        ->where('status', '!=', PaymentStatus::INACTIVE)
                                                        ->sum('total');

                                $payments_discount = POSDiscount::where('so_number', $payment_credit->so_number)
                                                            ->where('status', POSDiscountStatus::ACTIVE)
                                                            ->first()
                                                            ->price ?? 0;

                                $payments_vat = POSVat::where('so_number', $payment_credit->so_number)
                                                            ->where('status', POSVatStatus::ACTIVE)
                                                            ->first()
                                                            ->price ?? 0;

                                $payment_credit_records = PaymentCreditRecord::where('payment_credit_id', $payment_credit->id)
                                                                        // ->where('status', '!=', PaymentCreditRecordStatus::DISAPPROVED)
                                                                        ->get();

                                $grand_total = ($payments_total + $payments_vat) - $payments_discount;
                                $remaining_balance = ($grand_total - $payment_credit->price);
                            @endphp
                                    <tr>
                                        <td id="compact-table">
                                            <strong># {{ $payment_credit->so_number }}</strong>
                                            <div class="d-flex">
                                                @if ($payment_receipt->status == PaymentReceiptStatus::CANCELLED)
                                                    <a href="{{ route('accounting.payments.view', [$payment_credit->so_number]) }}" id="table-letter-margin">SO #</a> |  

                                                    <a href="{{ route('accounting.payment-credits.view', [$payment_credit->so_number]) }}" id="space-table">View</a>
                                                @endif

                                                @if ($payment_receipt->status != PaymentReceiptStatus::CANCELLED)
                                                    <a href="{{ route('accounting.payments.view', [$payment_credit->so_number]) }}" id="table-letter-margin">SO #</a> |  

                                                    <a href="{{ route('accounting.payment-credits.view', [$payment_credit->so_number]) }}" id="space-table">View</a> |  

                                                    <!-- IF PENDING -->
                                                    @if ($payment_credit->status == PaymentCreditStatus::PARTIALLY_PAID || $payment_credit->status == PaymentCreditStatus::CREDIT || $payment_credit->status == PaymentCreditStatus::OVERDUE || $payment_credit->status == PaymentCreditStatus::UNPAID)
                                                        <a href="#" data-toggle="modal" data-target="#credit-payment-pay-{{ $payment_credit->id }}" id="space-table">Pay</a>
                                                    @endif
                                                    
                                                @endif
                                            </div>
                                        </td>
                                        <td id="compact-table">
                                            @foreach ($payment_credit_records as $payment_credit_record)
                                                @if ($payment_credit_record->bir_number)
                                                    <strong># {{ $payment_credit_record->bir_number }}</strong><br>
                                                @endif
                                            @endforeach
                                        </td>
                                        <td id="compact-table">
                                            @if ($payment_credit->invoice_number)
                                                <strong># {{ $payment_credit->invoice_number }} <a href="#" data-toggle="modal" data-target="#assign-invoice-number-{{ $payment_credit->so_number }}"><i class="material-icons icon-16pt text-success" data-toggle="tooltip" data-placement="top" title="Edit SI number">edit</i></a></strong><br>
                                            @endif

                                            @if (!$payment_credit->invoice_number)
                                                <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#assign-invoice-number-{{ $payment_credit->so_number }}"><i class="material-icons icon-16pt mr-1 text-white">edit</i> Assign</button>
                                            @endif
                                        </td>
                                        <td id="compact-table"><i class="material-icons icon-16pt mr-1 text-muted">face</i>
                                        {{ $payment_credit->payment->user->firstname }} {{ $payment_credit->payment->user->lastname }}
                                        </td>
                                        <td>
                                            @if ($payment_receipt->status == PaymentReceiptStatus::CANCELLED)
                                                <div class="badge badge-danger ml-2">CANCELLED</div>
                                            @else
                                                @if ($payment_credit->status == PaymentCreditStatus::PARTIALLY_PAID)
                                                    <div class="badge badge-info ml-2">PARTIALLY PAID</div>
                                                @elseif ($payment_credit->status == PaymentCreditStatus::FULLY_PAID)
                                                    <div class="badge badge-success ml-2">FULLY PAID</div>
                                                @elseif ($payment_credit->status == PaymentCreditStatus::CREDIT)
                                                    <div class="badge badge-warning ml-2">CREDIT</div>
                                                @elseif ($payment_credit->status == PaymentCreditStatus::PENDING)
                                                    <div class="badge badge-warning ml-2">PENDING</div>
                                                @elseif ($payment_credit->status == PaymentCreditStatus::UNPAID)
                                                    <div class="badge badge-dark ml-2">UNPAID</div>
                                                @elseif ($payment_credit->status == PaymentCreditStatus::OVERDUE)
                                                    <div class="badge badge-danger ml-2">OVERDUE</div>
                                                @endif
                                            @endif
                                        </td>
                                        <td id="compact-table">P{{ number_format($payments_total, 2) }}</td>
                                        <td id="compact-table">P{{ number_format($payments_vat, 2) }}</td>
                                        <td id="compact-table">P{{ number_format($payments_discount, 2) }}</td>
                                        <td id="compact-table">P{{ number_format($grand_total, 2) }}</td>
                                        <td id="compact-table">P{{ number_format($payment_credit->price, 2) }}</td>
                                        <td id="compact-table">P{{ number_format($remaining_balance, 2) }}</td>
                                        <td id="compact-table"><i class="material-icons icon-16pt text-muted mr-1">today</i> {{ $payment_credit->created_at->add($payment_credit->days_due, 'day')->format('M d Y')}}</td>
                                        <td id="compact-table"><i class="material-icons icon-16pt text-muted mr-1">today</i> {{ $payment_credit->created_at->format('M d Y') }}</td>
                                    </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if (count($payment_credits) <= 0)
                        <div style="padding: 20px">
                            <center><i class="material-icons icon-16pt mr-1 text-muted">assignment</i> No record/s found</center>
                        </div>
                    @endif
                </div>
            </div>
            {{ $payment_credits->links() }}
        </div>
    </div>
</div>


@include('layouts.auth.footer')