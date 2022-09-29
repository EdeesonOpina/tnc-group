<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reports</title>
    <link type="text/css" href="{{ url('auth/pdf/assets/css/app.css') }}" rel="stylesheet">
    <style type="text/css">
    #compact-table {
        white-space:nowrap;
    }

    #space-table {
        margin-left: 7px;
        margin-right: 7px;
    }

    .text-pdf-small {
        font-size: 11px;
    }

    .font-change {
        font-family: "Lucida Console", "Courier New", monospace;
    }

    .heading-text {
        font-size: 20px;
    }

    body {
        background: #fff;
    }
    </style>
</head>
<body>
@php
    use Carbon\Carbon;
    use App\Models\OrderStatus;
@endphp


<!-- <img src="{{ url(env('APP_LOGO_WITH_TEXT')) }}" width="150px"> -->
<img src="{{ url(env('BIG_FOUR_LOGO')) }}" width="150px">
<hr>
<p class="font-change heading-text">Receipt</p>
<p class="font-change"><strong>S.O. No.:</strong> {{ $main_payment->so_number }}</p>
@if ($main_payment->bir_number)
    <p class="font-change"><strong>O.R No.:</strong> {{ $main_payment->bir_number }}</p>
@endif
@if ($main_payment->invoice_number)
    <p class="font-change"><strong>Invoice No.:</strong> {{ $main_payment->invoice_number }}</p>
@endif
<p class="font-change">
    <strong>Mode of Payment:</strong> 
     @if ($main_payment->mop == 'cash')
        Cash
    @endif

    @if ($main_payment->mop == 'credit')
        Credit
    @endif

    @if ($main_payment->mop == 'cheque')
        Cheque
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
</p>
<p class="font-change">
    <strong>Branch:</strong><br>
    {{ $branch->name }}<br>
    {{ $branch->line_address_1 }}<br>
    {{ $branch->line_address_2 }}<br>
    @if ($branch->phone)
        {{ $branch->phone }} / 
    @endif
    @if ($branch->mobile)
        {{ $branch->mobile }}
    @endif
</p>
<p class="font-change">
    <strong>Customer:</strong><br>
    {{ $user->firstname }} {{ $user->lastname }}<br>
    {{ $user->line_address_1 }}<br>
    {{ $user->line_address_2 }}<br>
    @if ($user->phone)
        {{ $user->phone }} / 
    @endif
    @if ($user->mobile)
        {{ $user->mobile }}
    @endif
</p>
<table class="table table-bordered font-change">
    <thead>
        <tr>
            <th>Item</th>
            <th id="compact-table">Price</th>
            <th id="compact-table">Qty</th>
            <th id="compact-table">Total</th>
        </tr>
    </thead>
    <tbody class="list" id="companies">
        @foreach($data as $data)
            <tr>
                <td><b>{{ $data->item->name }}</b></td>
                <td id="compact-table">P{{ number_format($data->price, 2) }}</td>
                <td id="compact-table">{{ $data->qty }}</td>
                <td id="compact-table">P{{ number_format($data->total, 2) }}</td>
            </tr>
        @endforeach
        <tr>
            <td id="compact-table"></td>
            <td id="compact-table"></td>
            <td id="compact-table"><strong>Total</strong></td>
            <td id="compact-table"><strong>P{{ number_format($payments_total, 2) }}</strong></td>
        </tr>
        <tr>
            <td id="compact-table"></td>
            <td id="compact-table"></td>
            <td id="compact-table"><strong>Discount</strong></td>
            <td id="compact-table"><strong>P{{ number_format($payments_discount, 2) }}</strong></td>
        </tr>
        <tr>
            <td id="compact-table"></td>
            <td id="compact-table"></td>
            <td id="compact-table"><strong>Total Amount</strong></td>
            <td id="compact-table"><strong>P{{ number_format($payments_total - $payments_discount, 2) }}</strong></td>
        </tr>
        <tr>
            <td id="compact-table"></td>
            <td id="compact-table"></td>
            <td id="compact-table"><strong>VAT</strong></td>
            <td id="compact-table"><strong>P{{ number_format($payments_vat, 2) }}</strong></td>
        </tr>
        <tr>
            <td id="compact-table"></td>
            <td id="compact-table"></td>
            <td id="compact-table"><strong>Grand Total</strong></td>
            <td id="compact-table"><strong>P{{ number_format(($payments_total + $payments_vat) - $payments_discount, 2) }}</strong></td>
        </tr>
        @if (count($payment_credits) > 0)
            <tr>
                <td id="compact-table"></td>
                <td id="compact-table"></td>
                <td><strong>Paid Credit</strong></td>
                <td><strong>P{{ number_format($payment_credit_total, 2) }}</strong></td>
            </tr>
            <tr>
                <td id="compact-table"></td>
                <td id="compact-table"></td>
                <td><strong>Remaining Balance</strong></td>
                <td><strong>P{{ number_format(($payments_total + $payments_vat) - $payments_discount, 2) }}</strong></td>
            </tr>
        @endif
    </tbody>
</table>

<p class="font-change">
    <strong>Cashier:</strong><br>
    <strong>{{ $cashier->firstname }} {{ $cashier->lastname }}</strong><br>
    {{ $cashier->role }}<br>
</p>

<small class="font-change">&copy; {{ str_replace('_', ' ', env('APP_NAME')) }} {{ date('Y') }}</small>

</body>
</html>