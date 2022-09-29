@php
  use Carbon\Carbon;
  use App\Models\ServiceOrder;
  use App\Models\ServiceOrderStatus;
  use App\Models\ServiceOrderDetail;
  use App\Models\ServiceOrderDetailStatus;

  $grand_total = 0;
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
    <a href="{{ route('admin.reports.service-orders') }}" style="text-decoration: none">
      <button class="btn btn-default" id="margin-right">Go Back</button>
    </a>
    <button type="button" id="btnExport" class="btn btn-success" onclick="fnExcelReport();">Create Excel File</button>
    <button class="btn btn-success" onClick="printdiv('div_print');" id="margin-left">Print page</button>
    <br><br>

    <div id="div_print" class="font-change">
      <img src="{{ url(env('APP_LOGO_WITH_TEXT')) }}" width="180px">
      <hr>
      <h4><b>Job Orders Report</b></h4>
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
          <th>JO #</th>
          <th>Customer</th>
          <th>Technical</th>
          <th id="compact-table">No. of Service/s</th>
          <th id="compact-table">MOP</th>
          <th id="compact-table">Date</th>
          <th id="compact-table">Time</th>
          <th id="compact-table">Grand Total</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        @foreach($service_orders as $service_order)
        @php
          $service_order_details_count = ServiceOrderDetail::where('service_order_id', $service_order->id)
                                          ->where('status', '!=', ServiceOrderDetailStatus::INACTIVE)
                                          ->sum('qty');

          $service_order_details_total = ServiceOrderDetail::where('service_order_id', $service_order->id)
                                          ->where('status', '!=', ServiceOrderDetailStatus::INACTIVE)
                                          ->sum('total');

          $grand_total += $service_order_details_total;
        @endphp
        <tr>
          <td><strong><a href="{{ route('operations.service-orders.view', [$service_order->jo_number]) }}" class="no-underline">{{ $service_order->jo_number }}</a></strong></td>
          <td>{{ $service_order->user->firstname }} {{ $service_order->user->lastname }}</td>
          <td>{{ $service_order->authorized_user->firstname }} {{ $service_order->authorized_user->lastname }}</td>
          <td>{{ $service_order_details_count }}</td>
          <td id="compact-table">{{ $service_order->mop }}</td>
          <td id="compact-table">{{ Carbon::parse($service_order->date_out)->format('M-d-Y') }}</td>
          <td id="compact-table">{{-- $service_order->date_out->format('g:iA') --}}</td>
          <td>{{ number_format($service_order_details_total, 2) }}</td>
          <td>
            @if ($service_order->status == ServiceOrderStatus::PENDING)
                pending
            @elseif ($service_order->status == ServiceOrderStatus::FOR_REPAIR)
                for repair
            @elseif ($service_order->status == ServiceOrderStatus::FOR_RELEASE)
                for release
            @elseif ($service_order->status == ServiceOrderStatus::COMPLETED)
                completed
            @elseif ($service_order->status == ServiceOrderStatus::CANCELLED)
                <strong style="color: red">cancelled</strong>
            @endif
          </td>
        </tr>
        @endforeach
        <tr>
          <td colspan="6"></td>
          <td id="compact-table"><b>Grand Total</b></td>
          <td style="width:1%;white-space:nowrap !important; font-weight: 500 !important;"><b>{{ number_format($grand_total, 2) }}</b></td>
          <td colspan="3"></td>
        </tr>
      </tbody>
    </table>

    @if(auth()->user()->role == 'Cashier')
      <b>All Transactions By:</b><br>
        {{ auth()->user()->firstname }} {{ auth()->user()->lastname }}<br>
      <b>{{ auth()->user()->role }}</b>
    @endif

  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>

  <script type="text/javascript">
    function fnExcelReport()
    {
      TableToExcel.convert(document.getElementById("mainTable"), {
        name: "Job Orders Report {{ date('m-d-y') }}.xlsx",
        sheet: {
          name: "Main",
        }
      });

      TableToExcel.convert(document.getElementById("summaryTable"), {
        name: "Summary Job Orders Report {{ date('m-d-y') }}.xlsx",
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