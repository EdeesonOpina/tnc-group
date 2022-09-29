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
    use App\Models\ServiceOrderStatus;
@endphp


<!-- <img src="{{ url(env('APP_LOGO_WITH_TEXT')) }}" width="150px"> -->
<img src="{{ url(env('BIG_FOUR_LOGO')) }}" width="150px">
<hr>
<p class="font-change heading-text">Receipt</p>
<p class="font-change"><strong>J.O. No.:</strong> {{ $service_order->jo_number }}</p>
<p class="font-change">
    <strong>Mode of Payment:</strong> 
     @if ($service_order->mop == 'cash')
        Cash
    @endif

    @if ($service_order->mop == 'credit')
        Credit
    @endif

    @if ($service_order->mop == 'cheque')
        Cheque
    @endif

    @if ($service_order->mop == 'credit-card')
        Credit Card
    @endif

    @if ($service_order->mop == 'bank-deposit')
        Bank Deposit
    @endif

    @if ($service_order->mop == 'paypal')
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
            <th>Service</th>
            <th id="compact-table">Price</th>
            <th id="compact-table">Qty</th>
            <th id="compact-table">Total</th>
        </tr>
    </thead>
    <tbody class="list" id="companies">
        @foreach($data as $data)
            <tr>
                <td id="compact-table">
                    {{ $data->name }}<br>
                    @if ($data->serial_number_notes)
                        <br>
                        <strong>S/N:</strong> {{ $data->serial_number_notes }}
                        <br>
                    @endif

                    @if ($data->remarks)
                        <br>
                        <strong>Note:</strong> {{ $data->remarks }}
                        <br>
                    @endif

                    @if ($data->action_taken)
                        <br>
                        @if ($service_order->status == ServiceOrderStatus::FOR_REPAIR || $service_order->status == ServiceOrderStatus::BACK_JOB)
                            <strong>Action Taken:</strong> <strong>{{ $data->action_taken }}</strong>
                        @else
                            <strong>Action Taken:</strong> {{ $data->action_taken }}
                        @endif
                    @endif
                </td>
                <td id="compact-table">
                    @if ($service_order->status == ServiceOrderStatus::FOR_REPAIR || $service_order->status == ServiceOrderStatus::BACK_JOB)
                        <strong>P{{ number_format($data->price, 2) }}</strong>
                    @else
                        P{{ number_format($data->price, 2) }}</strong>
                    @endif
                </td>
                <td id="compact-table">{{ $data->qty }}</td>
                <td id="compact-table">P{{ number_format($data->total, 2) }}</td>
            </tr>
        @endforeach
        <tr>
            <td id="compact-table"></td>
            <td id="compact-table"></td>
            <td id="compact-table"><strong>Total</strong></td>
            <td id="compact-table"><strong>P{{ number_format($service_order_details_total, 2) }}</strong></td>
        </tr>
    </tbody>
</table>

<p class="font-change">
    <strong>Technical:</strong><br>
    <strong>{{ $technical->firstname }} {{ $technical->lastname }}</strong><br>
    {{ $technical->role }}<br>
</p>

<small class="font-change">&copy; {{ str_replace('_', ' ', env('APP_NAME')) }} {{ date('Y') }}</small>

</body>
</html>