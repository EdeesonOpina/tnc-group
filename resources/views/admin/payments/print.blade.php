@php
    use Carbon\Carbon;
    use App\Models\PaymentStatus;
    use App\Models\ItemSerialNumber;
    use App\Models\PaymentReceiptStatus;
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
      <a href="{{ route('accounting.payments.view', [$main_payment->so_number]) }}" class="no-underline">
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
                            <strong>SO NUMBER: </strong>{{ $main_payment->so_number }}
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
                            <strong>Date: </strong>{{ $main_payment->created_at->format('M d Y') }}
                        </td>
                    </tr>
                </tbody>
            </table>

            @if ($main_payment->invoice_number || $main_payment->bir_number)
                <table class="table border-bottom no-border table-borderless font-change">
                    <tbody>
                        <tr>
                            <td class="text-left">
                                <strong>INVOICE NUMBER: </strong>{{ $main_payment->invoice_number }}
                            </td>
                            <td class="text-right">
                                <strong>OR NUMBER: </strong>{{ $main_payment->bir_number }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            @endif

            <table class="table border-bottom no-border table-borderless font-change">
                <tbody>
                    <tr>
                        <td class="text-left">
                            <strong>STATUS: </strong>
                            @if ($payment_receipt->status == PaymentReceiptStatus::PENDING)
                                <strong>PROCESSING</strong>
                                @if ($payment_receipt->note)
                                    / {{ $payment_receipt->note }}
                                @endif
                            @elseif ($payment_receipt->status == PaymentReceiptStatus::CONFIRMED)
                                <strong>CONFIRMED</strong>
                                @if ($payment_receipt->note)
                                    / {{ $payment_receipt->note }}
                                @endif
                            @elseif ($payment_receipt->status == PaymentReceiptStatus::FOR_DELIVERY)
                                <strong>FOR DELIVERY</strong>
                                @if ($payment_receipt->note)
                                    / {{ $payment_receipt->note }}
                                @endif
                            @elseif ($payment_receipt->status == PaymentReceiptStatus::DELIVERED)
                                <strong>DELIVERED</strong>
                                @if ($payment_receipt->note)
                                    / {{ $payment_receipt->note }}
                                @endif
                            @elseif ($payment_receipt->status == PaymentReceiptStatus::CANCELLED)
                                <strong>CANCELLED</strong>
                                @if ($payment_receipt->note)
                                    / {{ $payment_receipt->note }}
                                @endif
                            @elseif ($payment_receipt->status == PaymentReceiptStatus::INACTIVE)
                                <strong>INACTIVE</strong>
                                @if ($payment_receipt->note)
                                    / {{ $payment_receipt->note }}
                                @endif
                            @endif
                        </td>
                        <td class="text-right">
                            <strong>MODE OF PAYMENT: </strong>
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
                        </td>
                    </tr>
                </tbody>
            </table>
            <br>
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
                                </td>
                                <td class="text-right">P{{ number_format($payment->price, 2) }}</td>
                                <td>{{ $payment->qty }}</td>
                                <td class="text-right">P{{ number_format($payment->total, 2) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td><strong>Total</strong></td>
                            <td colspan="4" class="text-right"><strong>
                                P{{ number_format($payments_total, 2) }}
                            </strong></td>
                        </tr>
                        <tr>
                            <td><strong>Discount</strong></td>
                            <td colspan="4" class="text-right"><strong>
                                P{{ number_format($payments_discount, 2) }}
                            </strong></td>
                        </tr>
                        <tr>
                            <td><strong>Total Amount</strong></td>
                            <td colspan="4" class="text-right"><strong>
                                P{{ number_format($payments_total - $payments_discount, 2) }}
                            </strong></td>
                        </tr>
                        <tr>
                            <td><strong>VAT</strong></td>
                            <td colspan="4" class="text-right"><strong>P{{ number_format($payments_vat, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <td><strong>Grand Total</strong></td>
                            <td colspan="4" class="text-right"><strong>P{{ number_format(($payments_total + $payments_vat) - $payments_discount, 2) }}</strong></td>
                        </tr>
                        @if (count($payment_credits) > 0)
                            <tr>
                                <td><strong>Paid Credit</strong></td>
                                <td colspan="4" class="text-right"><strong>P{{ number_format($payment_credit_total, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Remaining Balance</strong></td>
                                <td colspan="4" class="text-right"><strong>P{{ number_format(($payments_total + $payments_vat) - $payments_discount, 2) }}</strong></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <table class="table border-bottom no-border table-borderless font-change">
                <tbody>
                    <tr>
                        <td class="text-left">
                            <div class="text-label">CASHIER</div>
                            <strong class="text-body">{{ $cashier->firstname }} {{ $cashier->lastname }}</strong><br>
                            {{ $cashier->role }}
                        </td>
                        <td class="text-right">
                            {!! QrCode::size(60)->generate(url(route('qr.payments.view', [$main_payment->so_number]))) !!}<br><br>
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

        @if (count($item_serial_numbers) > 0)
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
                            @foreach ($payments->unique('item_id') as $payment)
                            @php
                                $tbl_item_serial_numbers = ItemSerialNumber::where('item_id', $payment->item_id)
                                                                        ->get();
                            @endphp
                                <tr>
                                    <td>{{ $payment->inventory->item->name }}</td>
                                    <td class="text-right">
                                        @foreach($tbl_item_serial_numbers as $tbl_item_serial_number)
                                            @if ($tbl_item_serial_number->payment)
                                                @if ($tbl_item_serial_number->payment->so_number == $payment->so_number)
                                                    {{ $tbl_item_serial_number->code }}; 
                                                @endif
                                            @endif
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

                <table class="table border-bottom no-border table-borderless">
                    <tbody>
                        <tr>
                            <td class="text-left">
                                {!! QrCode::size(60)->generate(url(route('qr.payments.view', [$main_payment->so_number]))) !!}<br><br>
                            </td>
                            <td class="text-right">
                                
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
        @endif
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