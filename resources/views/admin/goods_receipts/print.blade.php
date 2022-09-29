@php
    use Carbon\Carbon;
    use App\Models\OrderStatus;
@endphp
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
  <a href="{{ route('internals.goods-receipts.view', [$goods_receipt->id]) }}" class="no-underline">
    <button class="btn btn-light">Go Back</button>
  </a>
  <button class="btn btn-success" onclick="printDiv('printableArea')">Print Page</button>
  <br><br>
  <!-- START OF PRINTABLE AREA -->
  <div id="printableArea">
    <img src="{{ url(env('BIG_FOUR_LOGO')) }}" width="150px">
    <hr>
    <h2 class="font-change">Goods Receipt Report</h2>

    <table class="table border-bottom no-border table-borderless font-change">
        <tbody>
            <tr>
                <td class="text-left">
                    <h5 class="font-change"><strong>GRPO. No.:</strong> {{ $goods_receipt->reference_number }}</h5>
                </td>
                <td class="text-right">
                    <strong class="font-change">Delivery Reference No.:</strong><br>
                    @foreach ($delivery_receipts as $delivery_receipt)
                        {{ $delivery_receipt->delivery_receipt_number }}<br>
                    @endforeach
                </td>
            </tr>
        </tbody>
    </table>

    <table class="table border-bottom no-border table-borderless font-change">
        <tbody>
            <tr>
                <td class="text-left">
                    <h5 class="font-change"><strong>P.O. No.:</strong> {{ $goods_receipt->purchase_order->reference_number }}</h5>
                </td>
                <td class="text-right">
                    &nbsp;
                </td>
            </tr>
        </tbody>
    </table>

    <table class="table border-bottom no-border table-borderless font-change">
        <tbody>
            <tr>
                <td class="text-left">
                    <p class="font-change">
                      <strong>Branch:</strong><br>
                      {{ $goods_receipt->purchase_order->branch->name }}<br>
                      {{ $goods_receipt->purchase_order->branch->line_address_1 }}<br>
                      {{ $goods_receipt->purchase_order->branch->line_address_2 }}<br>
                      {{ $goods_receipt->purchase_order->branch->mobile }} / {{ $goods_receipt->purchase_order->branch->phone }}
                    </p>
                </td>
                <td class="text-right">
                    <p class="font-change">
                      <strong>Supplier:</strong><br>
                      {{ $goods_receipt->purchase_order->supplier->name }}<br>
                      {{ $goods_receipt->purchase_order->supplier->line_address_1 }}<br>
                      {{ $goods_receipt->purchase_order->supplier->line_address_2 }}<br>
                      {{ $goods_receipt->purchase_order->supplier->mobile }} / {{ $goods_receipt->purchase_order->branch->phone }}
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
                <th id="compact-table">Received Qty</th>
                <th id="compact-table">Total</th>
            </tr>
        </thead>
        <tbody class="list" id="companies">
            @foreach($orders as $order)
                <tr>
                    <td><b>{{ $order->item->name }}</b></td>
                    <td id="compact-table">{{ $order->item->brand->name }}</td>
                    <td id="compact-table">{{ $order->item->category->name }}</td>
                    <td id="compact-table">P{{ number_format($order->price, 2) }}</td>
                    <td id="compact-table">{{ $order->qty }}</td>
                    <td id="compact-table">{{ $order->free_qty }}</td>
                    <td id="compact-table">{{ $order->received_qty }}</td>
                    <td id="compact-table">P{{ number_format($order->total, 2) }}</td>
                </tr>
            @endforeach
            <tr>
                <td id="compact-table"></td>
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

    <table class="table border-bottom no-border table-borderless font-change">
        <tbody>
            <tr>
                <td class="text-left">
                    <p class="font-change">
                      <strong>Created By:</strong><br>
                      @if ($goods_receipt->purchase_order->created_by_user->signature)
                          <img src="{{ url($goods_receipt->purchase_order->created_by_user->signature) }}" width="80px"><br>
                      @endif
                      <strong>{{ $goods_receipt->purchase_order->created_by_user->firstname }} {{ $goods_receipt->purchase_order->created_by_user->lastname }}</strong><br>
                      {{ $goods_receipt->purchase_order->created_by_user->role }}<br>
                      {{ Carbon::parse($goods_receipt->purchase_order->created_at)->format('M d Y') }}
                    </p>
                </td>
                <td class="text-right">
                    <p class="font-change">
                      <strong>Approved By:</strong><br>
                      @if ($goods_receipt->purchase_order->approved_by_user->signature)
                          <img src="{{ url($goods_receipt->purchase_order->approved_by_user->signature) }}" width="80px"><br>
                      @endif
                      <strong>{{ $goods_receipt->purchase_order->approved_by_user->firstname }} {{ $goods_receipt->purchase_order->approved_by_user->lastname }}</strong><br>
                      {{ $goods_receipt->purchase_order->approved_by_user->role }}<br>
                      {{ Carbon::parse($goods_receipt->purchase_order->approved_at)->format('M d Y') }}
                    </p>
                </td>
            </tr>
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