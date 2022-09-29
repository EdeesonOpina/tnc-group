@php
  use Carbon\Carbon;
  use App\Models\UserStatus;
  use App\Models\CashAdvance;
  use App\Models\CashAdvanceStatus;
  use App\Models\CashAdvancePayment;
  use App\Models\CashAdvancePaymentStatus;

  $grand_total = 0;
  $overall_remaining_balance_total = 0;
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
    <a href="{{ route('admin.reports.cash-advances') }}" style="text-decoration: none">
      <button class="btn btn-default" id="margin-right">Go Back</button>
    </a>
    <button type="button" id="btnExport" class="btn btn-success" onclick="fnExcelReport();">Create Excel File</button>
    <button class="btn btn-success" onClick="printdiv('div_print');" id="margin-left">Print page</button>
    <br><br>

    <div id="div_print" class="font-change">
      <img src="{{ url(env('APP_LOGO_WITH_TEXT')) }}" width="180px">
      <hr>
      <h4><b>Employee Cash Advance Report</b></h4>
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
      <tbody>
        @foreach ($cash_advances as $cash_advance)
        @php
          $grand_total += $cash_advance->price;
          $overall_paid_balance_total = CashAdvancePayment::where('status', CashAdvancePaymentStatus::APPROVED)
                                                      ->sum('price');

          $paid_balance = CashAdvancePayment::where('cash_advance_id', $cash_advance->id)
                                        ->where('status', CashAdvancePaymentStatus::APPROVED)
                                        ->sum('price');

          $remaining_balance = $cash_advance->price - $paid_balance;
          $overall_remaining_balance_total += $remaining_balance;

          $cash_advance_payments = CashAdvancePayment::where('cash_advance_id', $cash_advance->id)
                                        ->where('status', CashAdvancePaymentStatus::APPROVED)
                                        ->get();
        @endphp
        <thead class="thead-dark">
          <tr>
            <th>CA #</th>
            <th>Employee</th>
            <th id="compact-table">Grand Total</th>
            <th id="compact-table">Reason</th>
            <th>Status</th>
            <th id="compact-table">Borrowed Date</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>{{ $cash_advance->reference_number }}</td>
            <td>{{ $cash_advance->user->firstname }} {{ $cash_advance->user->lastname }}</td>
            <td>{{ number_format($cash_advance->price, 2) }}</td>
            <td>{{ $cash_advance->reason }}</td>
            <td>
              @if ($cash_advance->status == CashAdvanceStatus::PARTIALLY_PAID)
                PARTIALLY PAID
              @elseif ($cash_advance->status == CashAdvanceStatus::UNPAID)
                UNPAID
              @elseif ($cash_advance->status == CashAdvanceStatus::FULLY_PAID)
                FULLY PAID
              @endif

              @if ($cash_advance->status == CashAdvanceStatus::CANCELLED)
                CANCELLED
              @endif
            </td>
            <td>{{ Carbon::parse($cash_advance->date_borrowed)->format('M d Y') }}</td>
          </tr>
        </tbody>
        <thead>
          <tr>
            <th colspan="3">Price</th>
            <th colspan="2">Status</th>
            <th colspan="2">Paid At</th>
          </tr>
        </thead>
        <tbody>
          
          @foreach ($cash_advance_payments as $cash_advance_payment)
          <tr>
            <td colspan="3">{{ number_format($cash_advance_payment->price, 2) }}</td>
            <td colspan="2">
              @if ($cash_advance_payment->status == CashAdvancePaymentStatus::PENDING)
                  PENDING
              @elseif ($cash_advance_payment->status == CashAdvancePaymentStatus::APPROVED)
                  APPROVED
              @elseif ($cash_advance_payment->status == CashAdvancePaymentStatus::DISAPPROVED)
                  DISAPPROVED
              @endif
            </td>
            <td colspan="2">{{ $cash_advance_payment->created_at->format('M d Y') }}</td>
          </tr>
          @endforeach
        </tbody>
        @endforeach
      </tbody>
      <thead class="thead-dark">
        <tr>
          <th colspan="6">&nbsp;</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td colspan="3"></td>
          <td colspan="2"><strong>Grand Total</strong></td>
          <td colspan="1">{{ number_format($grand_total, 2) }}</td>
        </tr>
        <tr>
          <td colspan="3"></td>
          <td colspan="2"><strong>Paid Balance Total</strong></td>
          <td colspan="1">{{ number_format($overall_paid_balance_total, 2) }}</td>
        </tr>
        <tr>
          <td colspan="3"></td>
          <td colspan="2"><strong>Remaining Balance Total</strong></td>
          <td colspan="1">{{ number_format($overall_remaining_balance_total, 2) }}</td>
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
        name: "Employees CA Report {{ date('m-d-y') }}.xlsx",
        sheet: {
          name: "Main",
        }
      });

      TableToExcel.convert(document.getElementById("summaryTable"), {
        name: "Summary Employees CA Report {{ date('m-d-y') }}.xlsx",
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