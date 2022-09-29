@php
  use Carbon\Carbon;
  use App\Models\Order;
  use App\Models\POSVat;
  use App\Models\Payment;
  use App\Models\POSDiscount;
  use App\Models\OrderStatus;
  use App\Models\POSVatStatus;
  use App\Models\PaymentStatus;
  use App\Models\PaymentCredit;
  use App\Models\POSDiscountStatus;
  use App\Models\PaymentCreditStatus;

  $total_cost = 0;
  $profit = 0;
  $overall_profit_total = 0;
  $overall_costing_total = 0;
  $sub_overall_total_with_deductions= 0;
  $overall_grand_total = 0;
  $total_with_deductions = 0;

  $pos_cash_total_with_deductions = 0;
  $pos_credit_total_with_deductions = 0;
  $pos_credit_card_total_with_deductions = 0;
  $pos_cheque_total_with_deductions = 0;
  $pos_bank_deposit_total_with_deductions = 0;
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

    #margin-left {
        margin-left: 7px;
    }

    #margin-right {
        margin-right: 7px;
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

    body {
      background: #fff;
    }

    @media print {
      body {-webkit-print-color-adjust: exact;}
    }
  </style>
</head>
<body>
  <br><br>
  <div class="container">
    <a href="{{ route('admin.reports.customers') }}" style="text-decoration: none">
      <button class="btn btn-default" id="margin-right">Go Back</button>
    </a>
    <button type="button" id="btnExport" class="btn btn-success" onclick="fnExcelReport();">Create Excel File</button>
    <button class="btn btn-success" onClick="printdiv('div_print');" id="margin-left">Print page</button>
    <br><br>

    <div id="div_print" class="font-change">
      <img src="{{ url(env('APP_LOGO_WITH_TEXT')) }}" width="180px">
      <hr>
      <h4><b>Customer Sales Report</b></h4>
        <div class="row">
            <div class="col-md-2">
             <strong>Customer:</strong>
            </div>

            <div class="col-md-4">
              <strong>{{ $user->firstname }} {{ $user->lastname }}</strong><br>
              {{ $user->line_address_1 }} {{ $user->line_address_2 }}<br>
              {{ $user->mobile }} / {{ $user->phone }}
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-2">
             <strong>From Date:</strong>
            </div>

            <div class="col-md-4">
              @if ($from_date != '*')
                {{ date('m-d-Y',strtotime($from_date)) }}
              @endif
            </div>

            <div class="col-md-2">
              <strong>To Date:</strong>
            </div>

            <div class="col-md-4">
              @if ($to_date != '*')
                {{ date('m-d-Y',strtotime($to_date)) }}
              @endif
            </div>
        </div>

    <br>

    <table class="table table-responsive-sm table-bordered table-striped table-sm" id="mainTable">
      <thead>
        
      </thead>
      <tbody>
        @foreach ($payment_receipts as $payment_receipt)
        @php
          $payments = Payment::where('so_number', $payment_receipt->so_number)
                          ->where('status', '!=', PaymentStatus::INACTIVE)
                          ->get();
          $sub_overall_total_with_deductions = 0;
        @endphp
        <thead class="thead-dark">
          <tr>
            <th>SO #</th>
            <th>Customer</th>
            <th id="compact-table">MOP</th>
            <th>POS</th>
            <th>Status</th>
            <th id="compact-table">Date</th>
            <th id="compact-table">Time</th>
          </tr>
        </thead>
        <tr>
          <td><strong><a href="{{ route('accounting.payments.view', [$payment_receipt->so_number]) }}" class="no-underline">{{ $payment_receipt->so_number }}</a></strong></td>
          <td>{{ $payment_receipt->user->firstname }} {{ $payment_receipt->user->lastname }}</td>
          <td>
            {{ $payment_receipt->mop }}
              @if (PaymentCredit::where('so_number', $payment_receipt->so_number)->exists())
                @php
                  $payment_credit = PaymentCredit::where('so_number', $payment_receipt->so_number)->first();
                @endphp
                <br>
                @if ($payment_receipt->status == PaymentStatus::CANCELLED)
                    <strong style="color: red">CANCELLED</strong>
                @else
                    @if ($payment_credit->status == PaymentCreditStatus::PARTIALLY_PAID)
                        <strong style="color: orange">PARTIALLY PAID</strong>
                    @elseif ($payment_credit->status == PaymentCreditStatus::FULLY_PAID)
                        <strong style="color: green">FULLY PAID</strong>
                    @elseif ($payment_credit->status == PaymentCreditStatus::CREDIT)
                        <strong>CREDIT</strong>
                    @elseif ($payment_credit->status == PaymentCreditStatus::PENDING)
                        <strong>PENDING</strong>
                    @elseif ($payment_credit->status == PaymentCreditStatus::OVERDUE)
                        <strong>OVERDUE</strong>
                    @endif
                @endif
              @endif
          </td>
          <td>
              @if ($payment_receipt->is_pos_transaction == 1)
                yes
              @else
                no
              @endif
            </td>
          <td>
              @if ($payment_receipt->status == PaymentStatus::PENDING)
                  pending
              @elseif ($payment_receipt->status == PaymentStatus::CONFIRMED)
                  confirmed
              @elseif ($payment_receipt->status == PaymentStatus::FOR_DELIVERY)
                  for delivery
              @elseif ($payment_receipt->status == PaymentStatus::DELIVERED)
                  <strong style="color: green">delivered</strong>
              @elseif ($payment_receipt->status == PaymentStatus::CANCELLED)
                  <strong style="color: red">cancelled</strong>
              @endif
          </td>
          <td id="compact-table">{{ $payment_receipt->created_at->format('M-d-Y') }}</td>
          <td id="compact-table">{{ $payment_receipt->created_at->format('g:iA') }}</td>
        </tr>
        <tr>
          <th colspan="4">Item</th>
          <th id="compact-table">Qty</th>
          <th id="compact-table">Price</th>
          <th id="compact-table">Total</th>
        </tr>
          @foreach($payments as $payment)
          @php
            $overall_costing_total += $payment->cost;
          @endphp
          <tr>
            <td colspan="4">{{ $payment->item->name }}</td>
            <td>{{ $payment->qty }}</td>
            <td>{{ number_format($payment->price, 2) }}</td>
            <td>
              @if ($total_with_deductions < 0)
                <strong class="text-danger">{{ number_format($total_with_deductions, 2) }}</strong>
              @else
                {{ number_format($payment->total, 2) }}
              @endif
            </td>
          </tr>
          @php
            $sub_overall_total_with_deductions += $payment->total;
          @endphp
          @endforeach
          <tr>
            <td colspan="5">&nbsp;</td>
            <td><strong>Discount</strong></td>
            <td>{{ number_format($payment_receipt->discount, 2) }}</td>
          </tr>
          <tr>
            <td colspan="5">&nbsp;</td>
            <td><strong>VAT</strong></td>
            <td>{{ number_format($payment_receipt->vat, 2) }}</td>
          </tr>
          <tr>
            <td colspan="5">&nbsp;</td>
            <td><strong>Total Amount</strong></td>
            <td>{{ number_format(($sub_overall_total_with_deductions + $payment_receipt->vat) - $payment_receipt->discount, 2) }}</td>
          </tr>
        @endforeach
        <thead class="thead-dark">
          <tr>
            <th colspan="5"></th>
            <th colspan="2">&nbsp;</th>
          </tr>
        </thead>
        <tr>
          <td colspan="5"></td>
          <td id="compact-table"><b>Grand Total Amount</b></td>
          <td style="width:1%;white-space:nowrap !important; font-weight: 500 !important;"><b>{{ number_format($grand_total_amount, 2) }}</b></td>
        </tr>
        <tr>
          <td colspan="5"></td>
          <td id="compact-table"><b>Grand Cancelled Total Amount</b></td>
          <td style="width:1%;white-space:nowrap !important; font-weight: 500 !important;"><b>{{ number_format($grand_cancelled_total_amount, 2) }}</b></td>
        </tr>
        <tr>
          <td colspan="5"></td>
          <td id="compact-table"><b>Grand Discount Total</b></td>
          <td style="width:1%;white-space:nowrap !important; font-weight: 500 !important;"><b>{{ number_format($grand_discount_total, 2) }}</b></td>
        </tr>
        <tr>
          <td colspan="5"></td>
          <td id="compact-table"><b>Grand VAT Total</b></td>
          <td style="width:1%;white-space:nowrap !important; font-weight: 500 !important;"><b>{{ number_format($grand_vat_total, 2) }}</b></td>
        </tr>
        <tr>
          <td colspan="5"></td>
          <td id="compact-table"><b>Grand Total</b></td>
          <td style="width:1%;white-space:nowrap !important; font-weight: 500 !important;"><b>{{ number_format($grand_total, 2) }}</b></td>
        </tr>
        </tr>
      </tbody>
    </table>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>

  <script type="text/javascript">
    function fnExcelReport()
    {
      TableToExcel.convert(document.getElementById("mainTable"), {
        name: "Customer Sales Report {{ date('m-d-y') }}.xlsx",
        sheet: {
          name: "Main",
        }
      });

      TableToExcel.convert(document.getElementById("summaryTable"), {
        name: "Summary Customer Sales Report {{ date('m-d-y') }}.xlsx",
        sheet: {
          name: "Main",
        }
      });
    }
  </script>

  <script language="javascript">
    function printdiv(printpage)
    {
      var headstr = "<html><head><title></title></head><body>";
      var footstr = "</body>";
      var newstr = document.all.item(printpage).innerHTML;
      var oldstr = document.body.innerHTML;
      document.body.innerHTML = headstr+newstr+footstr;
      window.print();
      document.body.innerHTML = oldstr;
      return false;
    }
  </script>

</body>
</html>