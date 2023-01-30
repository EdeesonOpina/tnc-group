@php
    use App\Models\Order;
    use App\Models\OrderStatus;
    use App\Models\ItemStatus;
    use App\Models\ItemSerialNumber;
    use App\Models\ItemSerialNumberStatus;

    $price_grand_total = 0;
    $landing_price_grand_total = 0;
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

    .table th, .table td {
        font-size: 12px;
        border-spacing: 0px;
        padding: 0px;
    }
    </style>
</head>
<body>
<div class="container">
  <br><br>
  <a href="{{ route('internals.inventories.manage', [$company->id]) }}" class="no-underline">
    <button class="btn btn-light">Go Back</button>
  </a>
  <button class="btn btn-success" onclick="printDiv('printableArea')">Print Page</button>
  <br><br>
  <!-- START OF PRINTABLE AREA -->
  <div id="printableArea">
    @if ($company->image)
        <img src="{{ url($company->image) }}" width="150px">
    @else
        <img src="{{ url(env('BIG_FOUR_ICON')) }}" width="40px" style="margin-right: 7px;">
    @endif
    <hr>
    <h2 class="font-change">Inventory Report</h2>

    <table class="table border-bottom no-border table-borderless font-change">
        <tbody>
            <tr>
                <td class="text-left">
                    <p class="font-change">
                      <strong>Company:</strong><br>
                      {{ $company->name }}<br>
                      {{ $company->line_address_1 }}<br>
                      {{ $company->line_address_2 }}<br>
                      {{ $company->mobile }} / {{ $company->phone }}
                    </p>
                </td>
            </tr>
        </tbody>
    </table>

    <table class="table table-bordered font-change">
        <thead>
            <tr>
                <th id="compact-table">Name</th>
                <th id="compact-table">Qty</th>
                <th id="compact-table">S/N</th>
                <th id="compact-table">LP</th>
                <th id="compact-table">Landing</th>
                <th id="compact-table">Price</th>
                <th id="compact-table">SRP</th>
            </tr>
        </thead>
        <tbody class="list" id="companies">
            @foreach($inventories as $inventory)
            @php
                $item_serial_numbers_count = ItemSerialNumber::where('item_id', $inventory->item->id)
                                                        ->where('status', ItemSerialNumberStatus::AVAILABLE)
                                                        ->count();

                $landing_price = Order::where('item_id', $inventory->item->id)
                          ->where('status', OrderStatus::ACTIVE)
                          ->latest()
                          ->first()
                          ->price ?? 0;

                $price_grand_total += $inventory->qty * $inventory->price;
                $landing_price_grand_total += $inventory->qty * $landing_price;
            @endphp
                <tr>
                    <td id="compact-table"><strong>{{ $inventory->item->name }}</strong><br></td>
                    <td id="compact-table">{{ $inventory->qty }}</td>
                    <td id="compact-table">{{ $item_serial_numbers_count }}</td>
                    <td id="compact-table">{{ number_format($landing_price, 2) }}</td>
                    <td id="compact-table">{{ number_format($inventory->qty * $landing_price, 2) }}</td>
                    <td id="compact-table">{{ number_format($inventory->price, 2) }}</td>
                    <td id="compact-table">{{ number_format($inventory->qty * $inventory->price, 2) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="5"></td>
                <td id="compact-table"><strong>SRP Grand Total</strong></td>
                <td id="compact-table"><strong>{{ number_format($price_grand_total, 2) }}</strong></td>   
            </tr>
            <tr>
                <td colspan="5"></td>
                <td id="compact-table"><strong>LP Grand Total</strong></td>
                <td id="compact-table"><strong>{{ number_format($landing_price_grand_total, 2) }}</strong></td>   
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