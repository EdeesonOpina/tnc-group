@php
  use Carbon\Carbon;
  use App\Models\Order;
  use App\Models\OrderStatus;
  use App\Models\Payment;
  use App\Models\PaymentCredit;
  use App\Models\PaymentCreditRecord;
  use App\Models\PaymentCreditStatus;
  use App\Models\PaymentCreditRecordStatus;
  use App\Models\PaymentStatus;
  use App\Models\POSDiscount;
  use App\Models\POSDiscountStatus;
  use App\Models\POSVat;
  use App\Models\POSVatStatus;
  use App\Models\PaymentReceipt;
  use App\Models\PaymentReceiptStatus;

  $total_cost = 0;
  $profit = 0;
  $grand_total = 0;
  $paid_balance = 0;
  $overall_payments_total = 0;
  $overall_profit_total = 0;
  $overall_cost_total = 0;
  $overall_remaining_balance = 0;
  $pos_grand_total_sales = 0;
  $overall_paid_balance = 0;
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
  </style>
</head>
<body>
  <br><br>
  <div class="container">
    <a href="{{ route('accounting.payment-credits') }}" style="text-decoration: none">
      <button class="btn btn-default" id="margin-right">Go Back</button>
    </a>
    <button type="button" id="btnExport" class="btn btn-success" onclick="fnExcelReport();">Create Excel File</button>
    <button class="btn btn-success" onClick="printdiv('div_print');" id="margin-left">Print page</button>
    <br><br>

    <div id="div_print" class="font-change">
      <img src="{{ url(env('APP_LOGO_WITH_TEXT')) }}" width="180px">
      <hr>
      <h4><b>Credits Report</b></h4>
      <h6>
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
    </h6>

    <br>

    <table class="table table-responsive-sm table-bordered table-striped table-sm" id="mainTable">
      <thead>
        <tr>
          <th>SO #</th>
          <th>Customer</th>
          <th>Salesperson</th>
          <th>POS</th>
          <th id="compact-table">MOP</th>
          <th id="compact-table">Due Date</th>
          <th id="compact-table">Date</th>
          <th id="compact-table">Time</th>
          <th id="compact-table">Total</th>
          <th id="compact-table">VAT</th>
          <th id="compact-table">Discount</th>
          <th id="compact-table">Grand Total</th>
          <th id="compact-table">Paid Balance</th>
          <th id="compact-table">Remaining Balance</th>
          <!-- <th id="compact-table">Cost</th>
          <th id="compact-table">Profit</th> -->
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        @foreach($payment_credits as $payment_credit)
        @php
          $payment_receipt = PaymentReceipt::where('so_number', $payment_credit->so_number)->first();

          $paid_balance = PaymentCreditRecord::where('so_number', $payment_receipt->so_number)
                                          ->where('status', PaymentCreditRecordStatus::APPROVED)
                                          ->sum('price');

          $payments_total = Payment::where('so_number', $payment_receipt->so_number)
                                ->where('status', PaymentStatus::DELIVERED)
                                ->sum('total');

          $cost_total = Payment::where('so_number', $payment_receipt->so_number)
                                ->where('status', PaymentStatus::DELIVERED)
                                ->sum('cost');

          $pos_cash_discount = POSDiscount::where('so_number', $payment_receipt->so_number)
                                        ->where('status', POSDiscountStatus::ACTIVE)
                                        ->first()
                                        ->price ?? 0;

          $pos_cash_vat = POSVat::where('so_number', $payment_receipt->so_number)
                              ->where('status', POSDiscountStatus::ACTIVE)
                              ->first()
                              ->price ?? 0;

          if ($payment_receipt->status == PaymentReceiptStatus::DELIVERED) {
            $total_with_deductions = ($payments_total + $pos_cash_vat) - $pos_cash_discount;
            $profit_total = $total_with_deductions - $cost_total;

            $overall_payments_total += $total_with_deductions;
            $overall_cost_total += $cost_total;
            $overall_profit_total += $profit_total;
          }

          $overall_remaining_balance += $total_with_deductions - $paid_balance;
          $overall_paid_balance += $paid_balance;

          if ($payment_receipt->status == PaymentReceiptStatus::DELIVERED) {
        @endphp
        <tr>
          <td>
            <strong><a href="{{ route('accounting.payment-credits.view', [$payment_credit->so_number]) }}" class="no-underline">{{ $payment_credit->so_number }}</a></strong>
          </td>
          <td>{{ $payment_receipt->user->firstname }} {{ $payment_receipt->user->lastname }}</td>
          <td>
              @if ($payment_receipt->salesperson)
                {{ $payment_receipt->salesperson->firstname }} {{ $payment_receipt->salesperson->lastname }}
              @endif
          </td>
          <td>
            @if ($payment_receipt->is_pos_transaction == 1)
              yes
            @else
              no
            @endif
          </td>
          <td id="compact-table">{{ $payment_receipt->mop }}</td>
          <td id="compact-table">{{ $payment_credit->created_at->addDays($payment_credit->days_due)->format('M-d-Y') }}</td>
          <td id="compact-table">{{ $payment_receipt->created_at->format('M-d-Y') }}</td>
          <td id="compact-table">{{ $payment_receipt->created_at->format('g:iA') }}</td>
          <td>{{ number_format($payments_total, 2) }}</td>
          <td>{{ number_format($pos_cash_vat, 2) }}</td>
          <td>{{ number_format($pos_cash_discount, 2) }}</td>
          <td>{{ number_format($total_with_deductions, 2) }}</td>
          <td>{{ number_format($paid_balance, 2) }}</td>
          <td>{{ number_format($total_with_deductions - $paid_balance, 2) }}</td>
          <!-- <td>{{ number_format($cost_total, 2) }}</td>
          <td>{{ number_format($profit_total, 2) }}</td> -->
          <td>
            @if ($payment_credit->status == PaymentCreditStatus::PENDING)
                PENDING
            @elseif ($payment_credit->status == PaymentCreditStatus::PARTIALLY_PAID)
                PARTIALLY PAID
            @elseif ($payment_credit->status == PaymentCreditStatus::FULLY_PAID)
                FULLY PAID
            @elseif ($payment_credit->status == PaymentCreditStatus::CREDIT)
                CREDIT
            @elseif ($payment_credit->status == PaymentCreditStatus::UNPAID)
                UNPAID
            @elseif ($payment_credit->status == PaymentCreditStatus::OVERDUE)
                OVERDUE
            @endif

            @if ($payment_receipt->status == PaymentReceiptStatus::PENDING)
                PENDING
            @elseif ($payment_receipt->status == PaymentReceiptStatus::CONFIRMED)
                CONFIRMED
            @elseif ($payment_receipt->status == PaymentReceiptStatus::FOR_DELIVERY)
                FOR DELIVERY
            @elseif ($payment_receipt->status == PaymentReceiptStatus::DELIVERED)
                DELIVERED
            @elseif ($payment_receipt->status == PaymentReceiptStatus::CANCELLED)
                CANCELLED
            @elseif ($payment_receipt->status == PaymentReceiptStatus::INACTIVE)
                INACTIVE
            @endif
          </td>
        </tr>
        @php
          }
        @endphp
        @endforeach

        @if ($status == PaymentCreditStatus::PARTIALLY_PAID)
          <tr>
            <td colspan="11"></td>
            <td id="compact-table"><b>Remaining Balance Total</b></td>
            <td style="width:1%;white-space:nowrap !important; font-weight: 500 !important;"><b>{{ number_format($overall_remaining_balance, 2) }}</b></td>
            <td colspan="4"></td>
          </tr>

          <tr>
            <td colspan="11"></td>
            <td id="compact-table"><b>Paid Amount Total</b></td>
            <td style="width:1%;white-space:nowrap !important; font-weight: 500 !important;"><b>{{ number_format($overall_paid_balance, 2) }}</b></td>
            <td colspan="4"></td>
          </tr>

          <tr>
            <td colspan="11"></td>
            <td id="compact-table"><b>Credits Grand Total</b></td>
            <td style="width:1%;white-space:nowrap !important; font-weight: 500 !important;"><b>{{ number_format($overall_payments_total, 2) }}</b></td>
            <td colspan="4"></td>
          </tr>
        @else
          <tr>
            <td colspan="11"></td>
            <td id="compact-table"><b>Grand Total</b></td>
            <td style="width:1%;white-space:nowrap !important; font-weight: 500 !important;"><b>{{ number_format($overall_payments_total, 2) }}</b></td>
            <td colspan="4"></td>
          </tr>
        @endif

        <!-- <tr class="bg-danger text-white">
          <td colspan="11"></td>
          <td id="compact-table"><b>Total Cost</b></td>
          <td style="width:1%;white-space:nowrap !important; font-weight: 500 !important;"><b>{{ number_format($overall_cost_total, 2) }}</b></td>
          <td colspan="4"></td>
        </tr>

        <tr class="bg-success text-white">
          <td colspan="11"></td>
          <td id="compact-table"><b>Total Profit</b></td>
          <td style="width:1%;white-space:nowrap !important; font-weight: 500 !important;"><b>{{ number_format($overall_profit_total, 2) }}</b></td>
          <td colspan="4"></td>
        </tr> -->
      </tbody>
    </table>

  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>

  <script type="text/javascript">
    function fnExcelReport()
    {
      TableToExcel.convert(document.getElementById("mainTable"), {
        name: "Credits Report {{ date('m-d-y') }}.xlsx",
        sheet: {
          name: "Main",
        }
      });

      TableToExcel.convert(document.getElementById("summaryTable"), {
        name: "Summary Credits Report {{ date('m-d-y') }}.xlsx",
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