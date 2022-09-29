@include('layouts.generic.header')
@php
use App\Models\PaymentStatus;
use App\Models\ItemSerialNumber;
@endphp
<div class="container page__container">
    <div id="empty-space"></div>
    <center>
        <a href="{{ route('site.index') }}">
            <img src="{{ url(env('BIG_FOUR_LOGO')) }}" width="40%">
        </a>
    </center>
    <div id="empty-space"></div>
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card card-body" id="spaced-card">
                <div class="px-3">
                    <div class="row">
                        <div class="col-md-3">
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
                                {{ $branch->line_address_2 }}
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
                            </p>
                            <div class="text-label">Date</div>
                            {{ date('M d Y') }}
                        </div>
                    </div>

                    @if ($main_payment->invoice_number || $main_payment->bir_number)
                    <div class="row">
                        <div class="col-lg">
                            <div class="text-label">INVOICE NUMBER</div>
                            <p class="mb-4">{{ $main_payment->invoice_number }}</p>
                        </div>
                        <div class="col-lg text-right">
                            <div class="text-label">OR NUMBER</div>
                            <p class="mb-4">{{ $main_payment->bir_number }}</p>
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
                                @if ($main_payment->mop == 'cash')
                                Cash
                                @endif

                                @if ($main_payment->mop == 'credit-card')
                                Credit Card
                                @endif

                                @if ($main_payment->mop == 'bank-deposit')
                                Bank Deposit
                                @endif

                                @if ($main_payment->mop == 'paypal')
                                PayPal
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
                                        <br>
                                        @if ($payment->status == PaymentStatus::PENDING)
                                            <div class="badge badge-warning">PROCESSING</div>
                                        @elseif ($payment->status == PaymentStatus::CONFIRMED)
                                            <div class="badge badge-success">CONFIRMED</div>
                                        @elseif ($payment->status == PaymentStatus::FOR_DELIVERY)
                                            <div class="badge badge-success">FOR DELIVERY</div>
                                        @elseif ($payment->status == PaymentStatus::DELIVERED)
                                            <div class="badge badge-success">DELIVERED</div>
                                        @elseif ($payment->status == PaymentStatus::CANCELLED)
                                            <div class="badge badge-danger">CANCELLED</div>
                                        @endif
                                    </td>
                                    <td class="text-right">P{{ number_format($payment->price, 2) }}</td>
                                    <td>{{ $payment->qty }}</td>
                                    <td class="text-right">P{{ number_format($payment->total, 2) }}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td><strong>Discount</strong></td>
                                    <td colspan="4" class="text-right"><strong>
                                        P{{ number_format($payments_discount, 2) }}
                                    </strong></td>
                                </tr>
                                <tr>
                                    <td><strong>VAT</strong></td>
                                    <td colspan="4" class="text-right"><strong>P{{ number_format($payments_vat, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Grand Total</strong></td>
                                    <td colspan="4" class="text-right"><strong>P{{ number_format($payments_total - ($payments_discount + $payments_vat), 2) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg">
                            {!! QrCode::size(60)->generate(url(route('qr.payments.view', [$main_payment->id]))) !!}<br><br>
                            <div class="text-label">Notes</div>
                            <p class="text-muted">All prices are exclusive of taxes</p>
                        </div>
                        <div class="col-lg text-right">
                            <div class="text-label">CASHIER</div>
                            <strong class="text-body">{{ $cashier->firstname }} {{ $cashier->lastname }}</strong><br>
                            {{ $cashier->role }}
                        </div>
                    </div>
                </div>
            </div>

            <br>

            @if (count($item_serial_numbers) > 0)
            <div class="card card-body" id="spaced-card">
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
                                @foreach ($payments as $payment)
                                @php
                                $tbl_item_serial_numbers = ItemSerialNumber::where('payment_id', $main_payment->id)
                                ->get();
                                @endphp
                                <tr>
                                    <td>{{ $payment->inventory->item->name }}</td>
                                    <td class="text-right">
                                        @foreach($tbl_item_serial_numbers as $tbl_item_serial_number)
                                        {{ $tbl_item_serial_number->code }}; 
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
                            <div class="text-label">Notes</div>
                            <p class="text-muted">All prices are exclusive of taxes</p>
                        </div>
                        <div class="col-lg text-right">
                            <div class="text-label">CASHIER</div>
                            <strong class="text-body">{{ $cashier->firstname }} {{ $cashier->lastname }}</strong><br>
                            {{ $cashier->role }}
                        </div>
                    </div>
                </div>
            </div>

            @endif
        </div>
    </div>
</div>
</div>
<br>
<div class="text-center">
    <small>&copy; {{ str_replace('_', ' ', env('APP_NAME')) }} {{ date('Y') }}</small>
</div>
<div id="empty-space"></div>
</div>
@include('layouts.generic.footer')