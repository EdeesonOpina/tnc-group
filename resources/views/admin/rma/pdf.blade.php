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
    use App\Models\ReturnInventoryStatus;
@endphp


<!-- <img src="{{ url(env('APP_LOGO_WITH_TEXT')) }}" width="150px"> -->
<img src="{{ url(env('BIG_FOUR_LOGO')) }}" width="150px">
<hr>
<p class="font-change heading-text">Receipt</p>
<p class="font-change"><strong>RMA No.:</strong> {{ $return_inventory->reference_number }}</p>
<p class="font-change"><strong>S.O. No.:</strong> {{ $return_inventory->payment_receipt->so_number }}</p>
@if ($return_inventory->payment_receipt->bir_number)
    <p class="font-change"><strong>O.R No.:</strong> {{ $return_inventory->payment_receipt->bir_number }}</p>
@endif
@if ($return_inventory->payment_receipt->invoice_number)
    <p class="font-change"><strong>Invoice No.:</strong> {{ $return_inventory->payment_receipt->invoice_number }}</p>
@endif
<p class="font-change">
    <strong>Mode of Payment:</strong> 
     @if ($return_inventory->payment_receipt->mop == 'cash')
        Cash
    @endif

    @if ($return_inventory->payment_receipt->mop == 'credit')
        Credit
    @endif

    @if ($return_inventory->payment_receipt->mop == 'cheque')
        Cheque
    @endif

    @if ($return_inventory->payment_receipt->mop == 'credit-card')
        Credit Card
    @endif

    @if ($return_inventory->payment_receipt->mop == 'bank-deposit')
        Bank Deposit
    @endif

    @if ($return_inventory->payment_receipt->mop == 'paypal')
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
            <th id="compact-table">Qty</th>
            <th id="compact-table">Total</th>
        </tr>
    </thead>
    <tbody class="list" id="companies">
        @foreach($data as $data)
            <tr>
                <td id="compact-table">
                    {{ $data->inventory->item->name }}
                    @if ($data->remarks)
                        <br><br>
                        <strong>Remarks: </strong>{{ $data->remarks }}
                    @endif

                    @if ($data->action_taken)
                        <br><br>
                        <strong>Action: </strong> {{ $data->action_taken }}
                    @endif
                </td>
                <td id="compact-table">{{ $data->type }}</td>
                <td id="compact-table">{{ $data->qty }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<p class="font-change">
    <strong>RMA:</strong><br>
    <strong>{{ $rma->firstname }} {{ $rma->lastname }}</strong><br>
    {{ $rma->role }}<br>
</p>

<small class="font-change">&copy; {{ str_replace('_', ' ', env('APP_NAME')) }} {{ date('Y') }}</small>

</body>
</html>