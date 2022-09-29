@php
    use App\Models\Order;
    use App\Models\Inventory;
    use App\Models\ItemStatus;
    use App\Models\OrderStatus;
    use App\Models\PurchaseOrderStatus;
@endphp

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masterlist</title>
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
        font-size: 30px;
    }

    .no-underline {
      text-decoration: none !important;
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
<body>
<div class="container">
  <br><br>
  <a href="{{ url()->previous() }}" class="no-underline">
    <button class="btn btn-light">Go Back</button>
  </a>
  <button class="btn btn-success" onclick="printDiv('printableArea')">Print Page</button>
  <br><br>
  <!-- START OF PRINTABLE AREA -->
  <div id="printableArea">
    <img src="{{ url(env('BIG_FOUR_LOGO')) }}" width="150px">
    <hr>
    <table class="table border-bottom no-border table-borderless font-change">
        <tbody>
            <tr>
                <td class="text-left">
                    <div class="text-label"><strong>Purchase Order</strong></div>
                    <p class="mb-4">
                    <strong class="text-body">{{ $purchase_order->supplier->name }}</strong><br>
                    {{ $purchase_order->supplier->line_address_1 }}<br>
                    {{ $purchase_order->supplier->line_address_2 }}<br>
                    @if ($purchase_order->supplier->phone)
                        {{ $purchase_order->supplier->phone }} / 
                    @endif
                    @if ($purchase_order->supplier->mobile)
                        {{ $purchase_order->supplier->mobile }}
                    @endif
                    </p>
                </td>
            </tr>
        </tbody>
    </table>

    <table class="table mb-0 thead-border-top-0 table-striped">
        <thead>
            <tr>
                <th id="compact-table">Name</th>
                <th id="compact-table">Price</th>
                <th id="compact-table">Discount</th>
                <th id="compact-table">Qty</th>
                <th id="compact-table">Free Qty</th>
                <th id="compact-table">Total</th>
            </tr>
        </thead>
        <tbody class="list" id="companies">
            @foreach($orders as $order)
                <tr>
                    <td id="compact-table">
                        <b>Supplier Price:</b> P{{ number_format($order->price, 2) }}<br>
                        <b>{{ $order->item->name }}</b><br>
                        {{ $order->item->brand->name }}<br>
                        {{ $order->item->category->name }}
                    </td>
                    <td id="compact-table">P{{ number_format($order->price, 2) }}</td>
                    <td id="compact-table">P{{ number_format($order->discount, 2) }}</td>
                    <td id="compact-table">{{ $order->qty }}</td>
                    <td id="compact-table">{{ $order->free_qty }}</td>
                    <td id="compact-table">P{{ number_format($order->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <small class="font-change">&copy; {{ str_replace('_', ' ', env('APP_NAME')) }} {{ date('Y') }}</small>
  </div>
  <!-- END OF PRINTABLE AREA -->
</div>
</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script language="javascript">
  function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
</script>
</html>