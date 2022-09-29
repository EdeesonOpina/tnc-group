@include('layouts.auth.header')
@php
    use Carbon\Carbon;
    use App\Models\PaymentStatus;
    use App\Models\ItemSerialNumber;
    use App\Models\PaymentReceiptStatus;
@endphp
<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('accounting.payments') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('accounting.payments') }}">Payments</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('accounting.payments') }}">Receipt</a></li>
                    <li class="breadcrumb-item active" aria-current="page">View</li>
                </ol>
            </nav>
            <h1 class="m-0">Receipt</h1>
        </div>
        <!-- <a href="#">
            <button type="button" class="btn btn-success" id="margin-right"><i class="fa fa-plus" id="margin-right"></i>Add Item</button>
        </a> -->
        <a href="#" data-toggle="modal" data-target="#add-serial-number-{{ $payment_receipt->so_number }}" >
            <button type="button" class="btn btn-success" id="margin-right"><i class="fa fa-plus" id="margin-right"></i>Add S/N</button>
        </a>
        <!-- @if ($payment_receipt->is_completion == 0)
            <a href="{{ route('accounting.payments.completion', [$payment_receipt->so_number]) }}">
                <button type="button" class="btn btn-success" id="margin-right"><i class="fa fa-check" id="margin-right"></i>Mark As Completion</button>
            </a>
        @else
            <a href="{{ route('accounting.payments.standalone', [$payment_receipt->so_number]) }}">
                <button type="button" class="btn btn-danger" id="margin-right"><i class="fa fa-trash" id="margin-right"></i>Remove As Completion</button>
            </a>
        @endif -->
        <a href="{{ route('accounting.payments.print', [$payment_receipt->so_number]) }}">
            <button type="button" class="btn btn-light" id="margin-right"><i class="fa fa-print" id="margin-right"></i>Print</button>
        </a>
        <a href="{{ route('accounting.payments.excel', [$payment_receipt->so_number]) }}">
            <button type="button" class="btn btn-success" id="margin-right"><i class="fa fa-file-excel" id="margin-right"></i>Create Excel</button>
        </a>
        <a href="{{ route('accounting.payments.pdf', [$payment_receipt->so_number]) }}">
            <button type="button" class="btn btn-danger"><i class="fa fa-file-pdf" id="margin-right"></i>Create PDF</button>
        </a>
    </div>
</div>

<div class="container-fluid page__container">
    <div class="row">
        <div class="col-md-8">
            @include('layouts.partials.alerts')
            <div class="card">
                <div class="card-body">
                    @if ($payment_receipt->status == PaymentReceiptStatus::PENDING)
                        <div class="badge badge-warning">PROCESSING</div>
                    @elseif ($payment_receipt->status == PaymentReceiptStatus::CONFIRMED)
                        <div class="badge badge-success">CONFIRMED</div>
                    @elseif ($payment_receipt->status == PaymentReceiptStatus::FOR_DELIVERY)
                        <div class="badge badge-success">FOR DELIVERY</div>
                    @elseif ($payment_receipt->status == PaymentReceiptStatus::DELIVERED)
                        <div class="badge badge-success">DELIVERED</div>
                    @elseif ($payment_receipt->status == PaymentReceiptStatus::CANCELLED)
                        <div class="badge badge-danger">CANCELLED</div>
                    @elseif ($payment_receipt->status == PaymentReceiptStatus::INACTIVE)
                        <div class="badge badge-danger">INACTIVE</div>
                    @endif

                    @if ($main_payment->is_completion == 1)
                        <br>
                        <div class="badge badge-success">FOR COMPLETION</div>
                    @endif
                    <br><br>
                    <div class="px-3">
                        <div class="row">
                            <div class="col-md-3">
                                <!-- <img class="navbar-brand-icon mb-2" src="{{ url(env('APP_LOGO_WITH_TEXT')) }}" width="100%" alt="{{ env('APP_NAME') }}"> -->
                                <img class="navbar-brand-icon mb-2" src="{{ url(env('BIG_FOUR_LOGO')) }}" width="100%" alt="{{ env('APP_NAME') }}">
                            </div>
                        </div>
                        <br>
                        <div class="row mb-3">
                            <div class="col-lg">
                                <div class="text-label">FROM</div>
                                <p class="mb-4">
                                    <strong class="text-body">{{ $branch->name }}</strong><br>
                                    {{ $branch->line_address_1 }}<br>
                                    {{ $branch->line_address_2 }}<br>
                                    @if ($branch->phone)
                                        {{ $branch->phone }} / 
                                    @endif
                                    @if ($branch->mobile)
                                        {{ $branch->mobile }}
                                    @endif
                                </p>
                                <div class="text-label">SO NUMBER</div>
                                <p>{{ $main_payment->so_number }}</p>
                            </div>
                            <div class="col-lg text-right">
                                <div class="text-label">TO (CUSTOMER)</div>
                                <p class="mb-4">
                                    <strong class="text-body">{{ $user->firstname }} {{ $user->lastname }}</strong><br>
                                    {{ $user->line_address_1 }}<br>
                                    {{ $user->line_address_2 }}<br>
                                    @if ($user->phone)
                                        {{ $user->phone }} / 
                                    @endif
                                    @if ($user->mobile)
                                        {{ $user->mobile }}
                                    @endif
                                </p>
                                <div class="text-label">Date</div>
                                {{ $payment_receipt->created_at->format('M d Y') }}
                            </div>
                        </div>

                        @if ($payment_receipt->invoice_number || $payment_receipt->bir_number)
                            <div class="row">
                                <div class="col-lg">
                                    <div class="text-label">INVOICE NUMBER</div>
                                    <p class="mb-4">{{ $payment_receipt->invoice_number }}</p>
                                </div>
                                <div class="col-lg text-right">
                                    <div class="text-label">OR NUMBER</div>
                                    <p class="mb-4">{{ $payment_receipt->bir_number }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="row mb-3">
                            <div class="col-lg">
                                &nbsp;
                            </div>
                            <div class="col-lg text-right">
                                <div class="text-label">MODE OF PAYMENT</div>
                                <p class="mb-4"><strong class="text-body">
                                    <!-- CHECK IF IT STILL DID NOT MEET THE DEADLINE -->
                                    @if (Carbon::now() <= $payment_receipt->created_at->add(2, 'days'))
                                        <a href="#" data-toggle="modal" data-target="#assign-mop-{{ $payment_receipt->id }}" class="no-underline">
                                        @if ($payment_receipt->mop == 'cash')
                                            Cash
                                        @endif

                                        @if ($payment_receipt->mop == 'credit')
                                            Credit
                                        @endif

                                        @if ($payment_receipt->mop == 'cheque')
                                            Cheque
                                        @endif

                                        @if ($payment_receipt->mop == 'credit-card')
                                            Credit Card
                                        @endif

                                        @if ($payment_receipt->mop == 'bank-deposit')
                                            Bank Deposit
                                        @endif

                                        @if ($payment_receipt->mop == 'paypal')
                                            PayPal
                                        @endif
                                        </a>
                                    @else
                                        @if ($payment_receipt->mop == 'cash')
                                            Cash
                                        @endif

                                        @if ($payment_receipt->mop == 'credit')
                                            Credit
                                        @endif

                                        @if ($payment_receipt->mop == 'cheque')
                                            Cheque
                                        @endif

                                        @if ($payment_receipt->mop == 'credit-card')
                                            Credit Card
                                        @endif

                                        @if ($payment_receipt->mop == 'bank-deposit')
                                            Bank Deposit
                                        @endif

                                        @if ($payment_receipt->mop == 'paypal')
                                            PayPal
                                        @endif
                                    @endif
                                </strong></p>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table border-bottom mb-5">
                                <thead>
                                    <tr class="bg-light">
                                        <th>Item</th>
                                        <th class="text-right">Price</th>
                                        <th>Qty</th>
                                        <th class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($payments as $payment)
                                        <tr>
                                            <td>
                                                {{ $payment->inventory->item->name }}

                                                @if ($payment->status == PaymentStatus::CANCELLED || $payment->status == PaymentStatus::INACTIVE)

                                                @else
                                                    @if (Carbon::now() < $payment_receipt->created_at->addDays(7) || auth()->user()->role == 'Super Admin')
                                                        <a href="#" class="text-warning" data-toggle="modal" data-target="#return-{{ $payment->id }}"><i class="material-icons" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Return">assignment_return</i></a>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="text-right">P{{ number_format($payment->price, 2) }}</td>
                                            <td>{{ $payment->qty }}</td>
                                            <td class="text-right">P{{ number_format($payment->total, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td><strong>Total</strong></td>
                                        <td colspan="4" class="text-right"><strong>
                                        P{{ number_format($payments_total, 2) }}
                                        </strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Discount</strong></td>
                                        <td colspan="4" class="text-right"><strong>
                                        <a href="#" data-toggle="modal" data-target="#edit-discount-{{ $main_payment->so_number }}" class="no-underline">P{{ number_format($payments_discount, 2) }}</a>
                                        </strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total Amount</strong></td>
                                        <td colspan="4" class="text-right"><strong>P{{ number_format($payments_total - $payments_discount, 2) }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>VAT</strong></td>
                                        <td colspan="4" class="text-right"><strong>
                                        <a href="#" data-toggle="modal" data-target="#edit-vat-{{ $main_payment->so_number }}" class="no-underline">
                                        P{{ number_format($payments_vat, 2) }}
                                        </a>
                                        </strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Grand Total</strong></td>
                                        <td colspan="4" class="text-right"><strong>P{{ number_format(($payments_total + $payments_vat) - $payments_discount, 2) }}</strong></td>
                                    </tr>
                                    @if (count($payment_credits) > 0)
                                        <tr class="bg-success text-white">
                                            <td><strong>Paid Credit</strong></td>
                                            <td colspan="4" class="text-right"><strong>P{{ number_format($payment_credit_total, 2) }}</strong></td>
                                        </tr>
                                        <tr class="bg-danger text-white">
                                            <td><strong>Remaining Balance</strong></td>
                                            <td colspan="4" class="text-right"><strong>P{{ number_format(($payments_total + $payments_vat) - $payments_discount, 2) }}</strong></td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg">
                                <div class="text-label">CASHIER</div>
                                <strong class="text-body">{{ $cashier->firstname }} {{ $cashier->lastname }}</strong><br>
                                {{ $cashier->role }}
                            </div>
                            <div class="col-lg text-right">
                                {!! QrCode::size(60)->generate(url(route('qr.payments.view', [$main_payment->so_number]))) !!}<br><br>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg">
                                <div class="text-label"><br><br>PREPARED BY<br><br></div>
                            </div>
                            <div class="col-lg text-right">
                                <div class="text-label"><br><br>RECEIVED BY<br><br></div>
                            </div>
                        </div>

                        <div class="text-label">Notes</div>
                        <small class="text-muted note">One year warranty on parts. Accessories, software, virus and or consumables are NOT covered by this warranty. Warranty shall be void if warranty seal has been tampered or altered in anyway. If the items has been damaged brought about accident, misuse misapplication, abnormal causes or if the items has repaired or serviced by others. Inspect all items before signing this receipt.</small>

                    </div>
                </div>
            </div>

            @if (count($item_serial_numbers) > 0)
                <div class="card">
                    <div class="card-body">
                        <div class="px-3">
                            <br>
                            <strong>Serial Numbers</strong>
                            <br><br>
                            <div class="table-responsive">
                                <table class="table border-bottom mb-5">
                                    <thead>
                                        <tr class="bg-light">
                                            <th>Item</th>
                                            <th class="text-right">S/N</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($payments->unique('item_id') as $payment)
                                        @php
                                            $tbl_item_serial_numbers = ItemSerialNumber::where('item_id', $payment->item_id)
                                                                                    ->get();
                                        @endphp
                                            <tr>
                                                <td>{{ $payment->inventory->item->name }}</td>
                                                <td class="text-right">
                                                    @foreach($tbl_item_serial_numbers as $tbl_item_serial_number)
                                                        @if ($tbl_item_serial_number->payment)
                                                            @if ($tbl_item_serial_number->payment->so_number == $payment->so_number)
                                                                {{ $tbl_item_serial_number->code }}; 
                                                            @endif
                                                        @endif
                                                    @endforeach

                                                    <!-- IF NO S/N FOR THIS ITEM -->
                                                    @if (count($tbl_item_serial_numbers) <= 0)
                                                        N/A
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="row mb-3">
                                <div class="col-lg">
                                    {!! QrCode::size(60)->generate(url(route('qr.payments.view', [$main_payment->id]))) !!}<br><br>
                                </div>
                                <div class="col-lg text-right">
                                    <div class="text-label">CASHIER</div>
                                    <strong class="text-body">{{ $cashier->firstname }} {{ $cashier->lastname }}</strong><br>
                                    {{ $cashier->role }}
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@include('layouts.auth.footer')