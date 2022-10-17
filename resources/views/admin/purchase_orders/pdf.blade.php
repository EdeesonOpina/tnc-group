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

    .no-border {
      border: none !important;
      border-top: none !important;
      border-bottom: none !important;
      margin-bottom: 0px;
    }

    #compact-table {
        width:1%;
        white-space:nowrap;
    }

    body {
        background: #fff;
    }
    </style>
</head>
<body class="font-change">
@php
    use Carbon\Carbon;
    use App\Models\OrderStatus;
@endphp


<img src="{{ url(env('APP_LOGO_WITH_TEXT')) }}" width="150px">
<hr>
<p class="font-change heading-text">Purchase Order Report</p>
<table class="table border-bottom no-border table-borderless ">
    <tbody>
        <tr>
            <td class="text-left ">
                <p class="font-change"><strong>P.O. No.:</strong> {{ $purchase_order->reference_number }}</p>
            </td>
            <td class="text-right">
                &nbsp;
            </td>
        </tr>
    </tbody>
</table>

<table class="table border-bottom no-border table-borderless ">
    <tbody>
        <tr>
            <td class="text-left ">
                <p class="font-change">
                    <strong>Branch:</strong><br>
                    {{ $purchase_order->branch->name }}<br>
                    {{ $purchase_order->branch->line_address_1 }}<br>
                    {{ $purchase_order->branch->line_address_2 }}<br>
                    {{ $purchase_order->branch->mobile }} / {{ $purchase_order->branch->phone }}
                </p>
            </td>
            <td class="text-right">
                <p class="font-change">
                    <strong>Supplier:</strong><br>
                    {{ $purchase_order->supplier->name }}<br>
                    {{ $purchase_order->supplier->line_address_1 }}<br>
                    {{ $purchase_order->supplier->line_address_2 }}<br>
                    {{ $purchase_order->supplier->mobile }} / {{ $purchase_order->branch->phone }}
                </p>
            </td>
        </tr>
    </tbody>
</table>

<table class="table table-bordered font-change">
    <thead>
        <tr>
            <th>Name</th>
            <th id="compact-table">Brand</th>
            <th id="compact-table">Category</th>
            <th id="compact-table">Price</th>
            <th id="compact-table">Qty</th>
            <th id="compact-table">Free Qty</th>
            <th id="compact-table">Total</th>
        </tr>
    </thead>
    <tbody class="list" id="companies">
        @foreach($data as $data)
            <tr>
                <td><b>{{ $data->item->name }}</b></td>
                <td id="compact-table">{{ $data->item->brand->name }}</td>
                <td id="compact-table">{{ $data->item->category->name }}</td>
                <td id="compact-table">P{{ number_format($data->price, 2) }}</td>
                <td id="compact-table">{{ $data->qty }}</td>
                <td id="compact-table">{{ $data->free_qty }}</td>
                <td id="compact-table">P{{ number_format($data->total, 2) }}</td>
            </tr>
        @endforeach
        <tr>
            <td id="compact-table"></td>
            <td id="compact-table"></td>
            <td id="compact-table"></td>
            <td id="compact-table"></td>
            <td id="compact-table"></td>
            <td id="compact-table"><strong>Grand Total</strong></td>
            <td id="compact-table"><strong>P{{ number_format($orders_total, 2) }}</strong></td>
        </tr>
    </tbody>
</table>

<table class="table border-bottom no-border table-borderless ">
    <tbody>
        <tr>
            <td class="text-left ">
                <p class="font-change">
                    <strong>Created By:</strong><br>
                    @if ($purchase_order->created_by_user->signature)
                        <img src="{{ url($purchase_order->created_by_user->signature) }}" width="80px"><br>
                    @endif
                    <strong>{{ $purchase_order->created_by_user->firstname }} {{ $purchase_order->created_by_user->lastname }}</strong><br>
                    {{ $purchase_order->created_by_user->role }}<br>
                    {{ Carbon::parse($purchase_order->created_at)->format('M d Y') }}
                </p>
            </td>
            <td class="text-right">
                <p class="font-change">
                    @if ($purchase_order->approved_by_user->signature)
                        <img src="{{ url($purchase_order->approved_by_user->signature) }}" width="80px"><br>
                    @endif
                    <strong>Approved By:</strong><br>
                    <strong>{{ $purchase_order->approved_by_user->firstname }} {{ $purchase_order->approved_by_user->lastname }}</strong><br>
                    {{ $purchase_order->approved_by_user->role }}<br>
                    {{ Carbon::parse($purchase_order->approved_at)->format('M d Y') }}
                </p>
            </td>
        </tr>
    </tbody>
</table>

<small class="font-change">&copy; {{ str_replace('_', ' ', env('APP_NAME')) }} {{ date('Y') }}</small>

</body>
</html>