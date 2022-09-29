@php
    use Carbon\Carbon;
    use App\Models\PaymentStatus;
    use App\Models\ItemSerialNumber;
    use App\Models\ReturnInventoryStatus;
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

        #margin-right {
            margin-right: 7px;
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

        .note {
            font-size: 9px;
        }
      body {
        background: #fff;
    }
</style>
</head>
<body>
    <div class="container">
      <br><br>
      <a href="{{ route('internals.rma.view', [$return_inventory->reference_number]) }}" class="no-underline">
        <button class="btn btn-light" id="margin-right">Go Back</button>
    </a>
    <button class="btn btn-success" onclick="printDiv('printableArea')">Print Page</button>
    <br><br>
    <!-- START OF PRINTABLE AREA -->
    <div id="printableArea" class="font-change">
        <div class="px-3">
            <div class="row">
                <div class="col-md-3">
                    <!-- <img class="navbar-brand-icon mb-2" src="{{ url(env('APP_LOGO_WITH_TEXT')) }}" width="100%" alt="{{ env('TNCPC_WAREHOUSE') }}"> -->
                    <img class="navbar-brand-icon mb-2" src="{{ url(env('BIG_FOUR_LOGO')) }}" width="300px" alt="{{ env('TNCPC_WAREHOUSE') }}">
                </div>
            </div>
            <br>
            <table class="table border-bottom no-border table-borderless font-change">
                <tbody>
                    <tr>
                        <td class="text-left">
                            <strong class="text-body">RMA#</strong>
                            <p class="mb-4">
                                {{ $return_inventory->reference_number }}
                            </p>

                                <strong class="text-body">Supplier</strong><br>
                                <strong class="text-body">{{ $return_inventory->goods_receipt->purchase_order->supplier->name }}</strong><br>
                                {{ $return_inventory->goods_receipt->purchase_order->supplier->line_address_1 }}<br>
                                {{ $return_inventory->goods_receipt->purchase_order->supplier->line_address_2 }}<br>
                                @if ($return_inventory->goods_receipt->purchase_order->supplier->phone)
                                    {{ $return_inventory->goods_receipt->purchase_order->supplier->phone }} / 
                                @endif
                                @if ($return_inventory->goods_receipt->purchase_order->supplier->mobile)
                                    {{ $return_inventory->goods_receipt->purchase_order->supplier->mobile }}
                                @endif
                                <br><br>
                                <strong class="text-body">Status</strong><br>
                                @if ($return_inventory->status == ReturnInventoryStatus::FOR_WARRANTY)
                                    FOR WARRANTY
                                @elseif ($return_inventory->status == ReturnInventoryStatus::WAITING)
                                    WAITING
                                @elseif ($return_inventory->status == ReturnInventoryStatus::FOR_RELEASE)
                                    DONE
                                @elseif ($return_inventory->status == ReturnInventoryStatus::CLEARED)
                                    CLEARED
                                @elseif ($return_inventory->status == ReturnInventoryStatus::CANCELLED)
                                    CANCELLED
                                @elseif ($return_inventory->status == ReturnInventoryStatus::OUT_OF_WARRANTY)
                                    OUT OF WARRANTY
                                @elseif ($return_inventory->status == ReturnInventoryStatus::INACTIVE)
                                    INACTIVE
                                @endif
                            
                        </td>
                        <td class="text-right">
                            @if ($return_inventory->delivery_receipt_number)
                                <strong class="text-body">GRPO#</strong><br>
                                {{ $return_inventory->goods_receipt->reference_number }}
                                <br><br>
                                <strong class="text-body">DR#</strong><br>
                                {{ $return_inventory->delivery_receipt_number }}
                                <br><br>
                            @endif
                            <strong class="text-body">Date</strong><br>
                            {{ $return_inventory->created_at->format('M d Y') }}
                        </td>
                    </tr>
                </tbody>
            </table>

            @if ($return_inventory->invoice_number || $return_inventory->bir_number)
                <table class="table border-bottom no-border table-borderless font-change">
                    <tbody>
                        <tr>
                            <td class="text-left">
                                <strong>INVOICE NUMBER: </strong>{{ $return_inventory->invoice_number }}
                            </td>
                            <td class="text-right">
                                <strong>OR NUMBER: </strong>{{ $return_inventory->bir_number }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            @endif
            <br>
            <div class="table-responsive">
                <table class="table border-bottom mb-5">
                    <thead>
                        <tr class="bg-light">
                            <th>Item</th>
                            <th>Type</th>
                            <th>Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($return_inventory_items as $return_inventory_item)
                            <tr>
                                <td>
                                    {{ $return_inventory_item->inventory->item->name }}
                                    @if ($return_inventory_item->remarks)
                                        <br><br>
                                        <strong>Remarks: </strong>{{ $return_inventory_item->remarks }}
                                    @endif

                                    @if ($return_inventory_item->action_taken)
                                        <br><br>
                                        <strong>Action: </strong> {{ $return_inventory_item->action_taken }}
                                    @endif
                                </td>
                                <td>{{ $return_inventory_item->type }}</td>
                                <td>{{ $return_inventory_item->qty }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <table class="table border-bottom no-border table-borderless font-change">
                <tbody>
                    <tr>
                        
                        <td class="text-left">
                            <div class="text-label">RMA</div>
                            <strong class="text-body">{{ $return_inventory->created_by_user->firstname }} {{ $return_inventory->created_by_user->lastname }}</strong><br>
                            {{ $return_inventory->created_by_user->role }}
                        </td>
                        <td class="text-right">
                            {!! QrCode::size(60)->generate(url(route('qr.rma.view', [$return_inventory->reference_number]))) !!}<br><br>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table class="table border-bottom no-border table-borderless font-change">
                <tbody>
                    <tr>
                        <td class="text-left">
                            <div class="text-label"><br><br><strong>PREPARED BY</strong></div>
                        </td>
                        <td class="text-right">
                            <div class="text-label"><br><br><strong>RECEIVED BY</strong></div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table class="table border-bottom no-border table-borderless font-change">
                <tbody>
                    <tr>
                        <td class="text-left">
                            <div class="text-label"><strong>Notes</strong></div>
                            <small class="text-muted note">One year warranty on parts. Accessories, software, virus and or consumables are NOT covered by this warranty. Warranty shall be void if warranty seal has been tampered or altered in anyway. If the items has been damaged brought about accident, misuse misapplication, abnormal causes or if the items has repaired or serviced by others. Inspect all items before signing this receipt.</small>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
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