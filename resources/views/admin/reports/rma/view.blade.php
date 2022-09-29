@php
  use Carbon\Carbon;
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
    <a href="{{ route('admin.reports.rma') }}" style="text-decoration: none">
      <button class="btn btn-default" id="margin-right">Go Back</button>
    </a>
    <button type="button" id="btnExport" class="btn btn-success" onclick="fnExcelReport();">Create Excel File</button>
    <button class="btn btn-success" onClick="printdiv('div_print');" id="margin-left">Print page</button>
    <br><br>

    <div id="div_print" class="font-change">
      <img src="{{ url(env('APP_LOGO_WITH_TEXT')) }}" width="180px">
      <hr>
      <h4><b>RMA Report</b></h4>
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
          <th id="compact-table">RMA#</th>
          <th id="compact-table">SO#</th>
          <th id="compact-table">Supplier</th>
          <th id="compact-table">Customer</th>
          <th id="compact-table">Status</th>
          <th id="compact-table">Created At</th>
        </tr>
      </thead>
      <tbody>
        @foreach($return_inventories as $return_inventory)
            <tr>
                <td id="compact-table">
                  <a href="{{ route('internals.rma.view', [$return_inventory->reference_number]) }}" class="no-underline"><strong>{{ $return_inventory->reference_number }}</strong></a>
                </td>
                <td id="compact-table">
                  <a href="{{ route('accounting.payments.view', [$return_inventory->payment_receipt->so_number]) }}" class="no-underline"><strong>{{ $return_inventory->payment_receipt->so_number }}</strong></a>
                </td>
                <td id="compact-table">
                    @if ($return_inventory->goods_receipt)
                        {{ $return_inventory->goods_receipt->purchase_order->supplier->name ?? null }}
                    @endif

                    @if ($return_inventory->supplier)
                        {{ $return_inventory->supplier->name ?? null }}
                    @endif
                </td>
                <td id="compact-table">{{ $return_inventory->payment_receipt->user->firstname }} {{ $return_inventory->payment_receipt->user->lastname }}</td>
                <td id="compact-table">
                    @if ($return_inventory->status == ReturnInventoryStatus::WAITING)
                        WAITING
                    @elseif ($return_inventory->status == ReturnInventoryStatus::ON_PROCESS)
                        ON PROCESS
                    @elseif ($return_inventory->status == ReturnInventoryStatus::FOR_WARRANTY)
                        FOR WARRANTY
                    @elseif ($return_inventory->status == ReturnInventoryStatus::FOR_RELEASE)
                        FOR RELEASE
                    @elseif ($return_inventory->status == ReturnInventoryStatus::CLEARED)
                        CLEARED
                    @elseif ($return_inventory->status == ReturnInventoryStatus::OUT_OF_WARRANTY)
                        OUT OF WARRANTY
                    @elseif ($return_inventory->status == ReturnInventoryStatus::CANCELLED)
                        CANCELLED
                    @elseif ($return_inventory->status == ReturnInventoryStatus::INACTIVE)
                        INACTIVE
                    @endif
                </td>
                <td id="compact-table">{{ $return_inventory->created_at->format('M d Y') }}</td>
            </tr>
        @endforeach
      </tbody>
    </table>

    <div class="row">
      <div class="col">
        <table class="table table-responsive-sm table-bordered table-striped table-sm" id="summaryTable">
          <thead>
            <tr>
              <th>Summary Report</th>
              <th>&nbsp;</th>
            </tr>
          </thead>

          <tbody>
            <tr>
              <td>Total Count</td>
              <td style="font-weight: 500 !important;">{{ $rma_count }}</td>
            </tr>

            <tr>
              <td>For Warranty Count</td>
              <td style="font-weight: 500 !important;">{{ $for_warranty_count }}</td>
            </tr>

            <tr>
              <td>Waiting Count</td>
              <td style="font-weight: 500 !important;">{{ $waiting_count }}</td>
            </tr>

            <tr>
              <td>For Release Count</td>
              <td style="font-weight: 500 !important;">{{ $for_release_count }}</td>
            </tr>

            <tr>
              <td>Cleared Count</td>
              <td style="font-weight: 500 !important;">{{ $cleared_count }}</td>
            </tr>

            <tr>
              <td>Cancelled Count</td>
              <td style="font-weight: 500 !important;">{{ $cancelled_count }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>

  <script type="text/javascript">
    function fnExcelReport()
    {
      TableToExcel.convert(document.getElementById("mainTable"), {
        name: "RMA Report {{ date('m-d-y') }}.xlsx",
        sheet: {
          name: "Main",
        }
      });

      TableToExcel.convert(document.getElementById("summaryTable"), {
        name: "Summary RMA Report {{ date('m-d-y') }}.xlsx",
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