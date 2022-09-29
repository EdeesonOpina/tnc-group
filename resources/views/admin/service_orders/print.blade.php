@php
    use Carbon\Carbon;
    use App\Models\PaymentStatus;
    use App\Models\ItemSerialNumber;
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
      <a href="{{ route('operations.service-orders.view', [$service_order->jo_number]) }}" class="no-underline">
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
                            <div class="text-label"><strong>FROM</strong></div>
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
                            <strong>Date In: </strong>{{ $service_order->date_in }}
                        </td>
                        <td class="text-right">
                            <div class="text-label"><strong>TO (CUSTOMER)</strong></div>
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
                            <strong>Date Out: </strong>
                            @if ($service_order->date_out)
                                {{ date("M d Y", strtotime($service_order->date_out)) }}
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>

            <table class="table border-bottom no-border table-borderless font-change">
                <tbody>
                    <tr>
                        <td class="text-left">
                            <strong>JO NUMBER: </strong>{{ $service_order->jo_number }}
                        </td>
                        <td class="text-right">
                            <strong>MODE OF PAYMENT: </strong>
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
                        </td>
                    </tr>
                </tbody>
            </table>
            <br>
            <div class="table-responsive">
                <table class="table border-bottom mb-5">
                    <thead>
                        <tr class="bg-light">
                            <th>Service</th>
                            <th class="text-right">Price</th>
                            <th>Qty</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($service_order_details as $service_order_detail)
                            <tr>
                                <td>
                                    {{ $service_order_detail->name }}<br>
                                    @if ($service_order_detail->serial_number_notes)
                                        <br>
                                        <strong>S/N:</strong> {{ $service_order_detail->serial_number_notes }}
                                        <br>
                                    @endif

                                    @if ($service_order_detail->remarks)
                                        <br>
                                        <strong>Note:</strong> {{ $service_order_detail->remarks }}
                                        <br>
                                    @endif

                                    @if ($service_order_detail->action_taken)
                                        <br>
                                        <strong>Action Taken:</strong> {{ $service_order_detail->action_taken }}
                                    @endif
                                </td>
                                <td class="text-right">P{{ number_format($service_order_detail->price, 2) }}</td>
                                <td>{{ $service_order_detail->qty }}</td>
                                <td class="text-right">P{{ number_format($service_order_detail->total, 2) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td><strong>Grand Total</strong></td>
                            <td colspan="4" class="text-right"><strong>P{{ number_format($service_order_details_total, 2) }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <table class="table border-bottom no-border table-borderless font-change">
                <tbody>
                    <tr>
                        <td class="text-left">
                            <div class="text-label">TECHNICAL</div>
                            <strong class="text-body">{{ $cashier->firstname }} {{ $cashier->lastname }}</strong><br>
                            {{ $cashier->role }}
                        </td>
                        <td class="text-right">
                            {!! QrCode::size(60)->generate(url(route('qr.payments.view', [$service_order->jo_number]))) !!}<br><br>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table class="table border-bottom no-border table-borderless font-change">
                <tbody>
                    <tr>
                        <td class="text-left">
                            <div class="text-label"><br><br><strong>ACKNOWLEDGED BY</strong></div>
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