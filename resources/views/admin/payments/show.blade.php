@include('layouts.auth.header')
@php
    use App\Models\POSVat;
    use App\Models\Payment;
    use App\Models\Inventory;
    use App\Models\POSDiscount;
    use App\Models\PaymentProof;
    use App\Models\POSVatStatus;
    use App\Models\PaymentCredit;
    use App\Models\PaymentStatus;
    use App\Models\InventoryStatus;
    use App\Models\POSDiscountStatus;
    use App\Models\PaymentProofStatus;
    use App\Models\PaymentCreditStatus;
    use App\Models\PaymentReceiptStatus;
@endphp

<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('accounting.payments') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Daily Sales Transactions</li>
                </ol>
            </nav>
            <h1 class="m-0">Daily Sales Transactions</h1>
        </div>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')
    @include('layouts.partials.payments.top-tabs')

    <form action="{{ route('accounting.payments.search') }}" method="post">
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
                                        @if (old('status') == PaymentStatus::PENDING)
                                            <option value="{{ old('status') }}">Pending</option>
                                        @endif

                                        @if (old('status') == PaymentStatus::CONFIRMED)
                                            <option value="{{ old('status') }}">Confirmed</option>
                                        @endif

                                        @if (old('status') == PaymentStatus::FOR_DELIVERY)
                                            <option value="{{ old('status') }}">For Delivery</option>
                                        @endif

                                        @if (old('status') == PaymentStatus::DELIVERED)
                                            <option value="{{ old('status') }}">Delivered</option>
                                        @endif

                                        @if (old('status') == PaymentStatus::CANCELLED)
                                            <option value="{{ old('status') }}">Cancelled</option>
                                        @endif
                                    @endif
                                @endif
                                <option value="*">All</option>
                                <option value="{{ PaymentStatus::PENDING }}">Pending</option>
                                <option value="{{ PaymentStatus::CONFIRMED }}">Confirmed</option>
                                <option value="{{ PaymentStatus::FOR_DELIVERY }}">For Delivery</option>
                                <option value="{{ PaymentStatus::DELIVERED }}">Delivered</option>
                                <option value="{{ PaymentStatus::CANCELLED }}">Cancelled</option>
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
                        <label><a href="{{ route('accounting.payments') }}" id="no-underline">Clear Filters</a></label>
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
                    <h4 class="card-header__title flex m-0">Payments</h4>
                    <div data-toggle="flatpickr" data-flatpickr-wrap="true" data-flatpickr-static="true" data-flatpickr-mode="range" data-flatpickr-alt-format="d/m/Y" data-flatpickr-date-format="d/m/Y">
                        
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table">SO#</th>
                                <th id="compact-table">Customer</th>
                                <th id="compact-table">Cashier</th>
                                <th id="compact-table">Salesperson</th>
                                <th id="compact-table">Status</th>
                                <th id="compact-table">No. of Items</th>
                                <th id="compact-table">Total</th>
                                <th id="compact-table">VAT</th>
                                <th id="compact-table">Discount</th>
                                <th id="compact-table">Grand Total</th>
                                <th id="compact-table">Note</th>
                                <th id="compact-table">Created At</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                            @foreach($payment_receipts as $payment_receipt)
                                @php
                                    $payments = Payment::where('payment_receipt_id', $payment_receipt->id)->get();
                                @endphp
                                @foreach($payments->unique('so_number') as $payment)
                                @php
                                    $payments_count = Payment::where('so_number', $payment->so_number)
                                                            ->where('status', '!=', PaymentReceiptStatus::INACTIVE)
                                                            ->sum('qty');

                                    $payments_total = Payment::where('so_number', $payment->so_number)
                                                            ->where('status', '!=', PaymentReceiptStatus::INACTIVE)
                                                            ->sum('total');

                                    $payments_discount = POSDiscount::where('so_number', $payment->so_number)
                                                                ->where('status', POSDiscountStatus::ACTIVE)
                                                                ->first()
                                                                ->price ?? 0;

                                    $payments_vat = POSVat::where('so_number', $payment->so_number)
                                                                ->where('status', POSVatStatus::ACTIVE)
                                                                ->first()
                                                                ->price ?? 0;

                                    $payment_credit = PaymentCredit::where('so_number', $payment->so_number)
                                                                ->where('status', '!=', PaymentCreditStatus::INACTIVE)
                                                                ->first();

                                    $payment_proofs = PaymentProof::where('so_number', $payment->so_number)
                                                                ->where('status', '!=', PaymentProofStatus::INACTIVE)
                                                                ->get();
                                @endphp
                                <tr>
                                    <td id="compact-table">
                                        <strong># {{ $payment_receipt->so_number }}</strong>
                                        <div class="d-flex">
                                            <a href="{{ route('accounting.payments.view', [$payment_receipt->so_number]) }}" id="table-letter-margin">View</a> | 

                                            @if (! PaymentCredit::where('so_number', $payment_receipt->so_number)->exists())
                                                <a href="#" data-toggle="modal" data-target="#credit-{{ $payment_receipt->id }}" id="space-table">Credit</a> | 
                                            @endif

                                            <!-- IF PENDING -->
                                            @if ($payment_receipt->status == PaymentReceiptStatus::PENDING)
                                                <a href="#" data-href="{{ route('accounting.payments.confirm', [$payment_receipt->so_number]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Confirm</a> | 

                                                <a href="#" data-href="{{ route('accounting.payments.delivery', [$payment_receipt->so_number]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">For Delivery</a> | 

                                                <a href="#" data-href="{{ route('accounting.payments.delivered', [$payment_receipt->so_number]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Delivered</a> |

                                                <a href="#" data-href="{{ route('accounting.payments.cancel', [$payment_receipt->so_number]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Cancel</a>
                                            @endif

                                            <!-- IF CONFIRMED -->
                                            @if ($payment_receipt->status == PaymentReceiptStatus::CONFIRMED)
                                                <a href="#" data-href="{{ route('accounting.payments.delivery', [$payment_receipt->so_number]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">For Delivery</a> | 

                                                <a href="#" data-href="{{ route('accounting.payments.delivered', [$payment_receipt->so_number]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Delivered</a> | 

                                                <a href="#" data-href="{{ route('accounting.payments.cancel', [$payment_receipt->so_number]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Cancel</a>
                                            @endif

                                            <!-- IF FOR DELIVERY -->
                                            @if ($payment_receipt->status == PaymentReceiptStatus::FOR_DELIVERY)
                                                <a href="#" data-href="{{ route('accounting.payments.delivered', [$payment_receipt->so_number]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Delivered</a> | 

                                                <a href="#" data-href="{{ route('accounting.payments.cancel', [$payment_receipt->so_number]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Cancel</a>
                                            @endif

                                            <!-- IF DELIVERED -->
                                            @if ($payment_receipt->status == PaymentReceiptStatus::DELIVERED)
                                                <a href="#" data-href="{{ route('accounting.payments.cancel', [$payment_receipt->so_number]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Cancel</a>
                                            @endif

                                            <!-- IF CANCELLED -->
                                            @if ($payment_receipt->status == PaymentReceiptStatus::CANCELLED)
                                                <!-- <a href="#" data-href="{{ route('accounting.payments.recover', [$payment_receipt->so_number]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Recover</a> -->
                                            @endif
                                        </div>
                                    </td>
                                    <td id="compact-table"><i class="material-icons icon-16pt mr-1 text-muted">face</i> {{ $payment_receipt->user->firstname }} {{ $payment_receipt->user->lastname }}</td>
                                    <td id="compact-table">
                                        @if ($payment_receipt->authorized_user)
                                            <i class="material-icons icon-16pt mr-1 text-muted">face</i> {{ $payment_receipt->authorized_user->firstname }} {{ $payment_receipt->authorized_user->lastname }}
                                        @endif
                                    </td>
                                    <td id="compact-table">
                                        @if ($payment_receipt->salesperson)
                                            <i class="material-icons icon-16pt mr-1 text-muted">face</i> {{ $payment_receipt->salesperson->firstname }} {{ $payment_receipt->salesperson->lastname }} <a href="{{ route('accounting.payments.salesperson', [$payment_receipt->so_number]) }}"><i class="material-icons icon-16pt text-success" data-toggle="tooltip" data-placement="top" title="Assign Salesperson">edit</i></a>
                                        @endif

                                        @if (!$payment_receipt->salesperson)
                                            <a href="{{ route('accounting.payments.salesperson', [$payment_receipt->so_number]) }}"><button type="button" class="btn btn-sm btn-success"><i class="material-icons icon-16pt mr-1 text-white">edit</i> Assign</button></a>
                                        @endif
                                    </td>
                                    <td>
                                        @if (PaymentCredit::where('so_number', $payment->so_number)->exists())
                                        @php
                                            $payment_credit = PaymentCredit::where('so_number', $payment->so_number)
                                                                        ->first();
                                        @endphp
                                            @if ($payment_credit->status == PaymentCreditStatus::PARTIALLY_PAID)
                                                <div class="badge badge-warning ml-2">PARTIALLY PAID</div>
                                            @elseif ($payment_credit->status == PaymentCreditStatus::FULLY_PAID)
                                                <div class="badge badge-success ml-2">FULLY PAID</div>
                                            @elseif ($payment_credit->status == PaymentCreditStatus::CREDIT)
                                                <div class="badge badge-warning ml-2">CREDIT</div>
                                            @endif
                                        @endif

                                        @if ($payment_receipt->status == PaymentReceiptStatus::PENDING)
                                            <div class="badge badge-warning ml-2">PENDING</div>
                                        @elseif ($payment_receipt->status == PaymentReceiptStatus::CONFIRMED)
                                            <div class="badge badge-success ml-2">CONFIRMED</div>
                                        @elseif ($payment_receipt->status == PaymentReceiptStatus::FOR_DELIVERY)
                                            <div class="badge badge-success ml-2">FOR DELIVERY</div>
                                        @elseif ($payment_receipt->status == PaymentReceiptStatus::DELIVERED)
                                            <div class="badge badge-success ml-2">DELIVERED</div>
                                        @elseif ($payment_receipt->status == PaymentReceiptStatus::CANCELLED)
                                            <div class="badge badge-danger ml-2">CANCELLED</div>
                                        @elseif ($payment_receipt->status == PaymentReceiptStatus::INACTIVE)
                                            <div class="badge badge-danger ml-2">INACTIVE</div>
                                        @endif
                                    </td>
                                    <td id="compact-table">{{ $payments_count }}</td>
                                    <td id="compact-table">P{{ number_format($payments_total, 2) }}</td>
                                    <td id="compact-table">P{{ number_format($payments_vat, 2) }}</td>
                                    <td id="compact-table">P{{ number_format($payments_discount, 2) }}</td>
                                    <td id="compact-table">P{{ number_format(($payments_total + $payments_vat) - $payments_discount, 2) }}</td>
                                    <td id="compact-table">{{ $payment_receipt->note }}</td>
                                    <td id="compact-table"><i class="material-icons icon-16pt text-muted mr-1">today</i> {{ $payment->created_at->format('M d Y') }}</td>
                                </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>

                    @if (count($payment_receipts) <= 0)
                        <div style="padding: 20px">
                            <center><i class="material-icons icon-16pt mr-1 text-muted">assignment</i> No record/s found</center>
                        </div>
                    @endif
                </div>
            </div>
            {{ $payment_receipts->links() }}
        </div>
    </div>
</div>


@include('layouts.auth.footer')