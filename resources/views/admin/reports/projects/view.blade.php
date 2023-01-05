@php
  use Carbon\Carbon;
  use App\Models\ProjectStatus;
  use App\Models\ProjectBudgetStatus;
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
    <a href="{{ route('admin.reports.projects') }}" style="text-decoration: none">
      <button class="btn btn-default" id="margin-right">Go Back</button>
    </a>
    <button type="button" id="btnExport" class="btn btn-success" onclick="fnExcelReport();">Create Excel File</button>
    <button class="btn btn-success" onClick="printdiv('div_print');" id="margin-left">Print page</button>
    <br><br>

    <div id="div_print" class="font-change">
      <img src="{{ url(env('APP_LOGO_WITH_TEXT')) }}" width="180px">
      <hr>
      <h4><b>Projects Report</b></h4>
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
            <th id="compact-table">CE#</th>
            <th id="compact-table">Name</th>
            <th id="compact-table">Created By</th>
            <th id="compact-table">Company</th>
            <th id="compact-table">Client</th>
            <th id="compact-table">Status</th>
            <th id="compact-table">Cost</th>
            <th id="compact-table">Used Cost</th>
            <th id="compact-table">ASF</th>
            <th id="compact-table">VAT</th>
            <th id="compact-table">Grand Total</th>
            <th id="compact-table">Duration Date</th>
        </tr>
      </thead>
      <tbody>
        @foreach($projects as $project)
          <tr>
              <td id="compact-table"><strong>{{ $project->reference_number }}</strong></td>
              <td id="compact-table">
                  @if ($project->budget_status == ProjectBudgetStatus::WITHIN_BUDGET)
                      <strong style="color:green">WITHIN BUDGET</strong>
                  @elseif ($project->budget_status == ProjectBudgetStatus::OVERBUDGET)
                      <strong style="color:red">OVERBUDGET</strong>
                  @endif
                  <br>
                  <b>{{ $project->name }}</b>
              </td>
              <td id="compact-table">{{ $project->prepared_by_user->firstname }} {{ $project->prepared_by_user->lastname }}</td>
              <td id="compact-table">{{ $project->company->name }}</td>
              <td id="compact-table">{{ $project->client->name }}</td>
              <td>
                  @if ($project->status == ProjectStatus::FOR_APPROVAL)
                      <strong style="color:orange">For Approval</strong>
                  @elseif ($project->status == ProjectStatus::APPROVED)
                      <strong style="color:green">Approved</strong>
                  @elseif ($project->status == ProjectStatus::OPEN_FOR_EDITING)
                      <strong style="color:orange">Open For Editing</strong>
                  @elseif ($project->status == ProjectStatus::DONE)
                      <strong style="color:green">Done</strong>
                  @elseif ($project->status == ProjectStatus::ON_PROCESS)
                      <strong style="color:orange">On Process</strong>
                  @elseif ($project->status == ProjectStatus::DISAPPROVED)
                      <strong style="color:red">Disapproved</strong>
                  @elseif ($project->status == ProjectStatus::INACTIVE)
                      <strong style="color:red">Inactive</strong>
                  @endif
              </td>
              <td id="compact-table">{{ number_format($project->total, 2) }}</td>
              <td id="compact-table">{{ number_format($project->used_cost(), 2) }}</td>
              <td id="compact-table">{{ number_format($project->asf, 2) }}</td>
              <td id="compact-table">{{ number_format($project->vat, 2) }}</td>
              <td id="compact-table">{{ number_format($project->total + $project->asf + $project->vat, 2) }}</td>
              <td id="compact-table">{{ Carbon::parse($project->duration_date)->format('M d Y') ?? null }}</td>
          </tr>
      @endforeach


        <tr>
          <td colspan="9"></td>
          <td id="compact-table"><b>Grand Total</b></td>
          <td style="width:1%;white-space:nowrap !important; font-weight: 500 !important;"><b>{{ number_format($grand_total, 2) }}</b></td>
          <td colspan="3"></td>
        </tr>

        <tr class="">
          <td colspan="9"></td>
          <td id="compact-table"><b>Total VAT</b></td>
          <td style="width:1%;white-space:nowrap !important; font-weight: 500 !important;"><b>{{ number_format($vat_total, 2) }}</b></td>
          <td colspan="3"></td>
        </tr>

        <tr class="">
          <td colspan="9"></td>
          <td id="compact-table"><b>Total ASF</b></td>
          <td style="width:1%;white-space:nowrap !important; font-weight: 500 !important;"><b>{{ number_format($asf_total, 2) }}</b></td>
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
        name: "Sales Report {{ date('m-d-y') }}.xlsx",
        sheet: {
          name: "Main",
        }
      });

      TableToExcel.convert(document.getElementById("summaryTable"), {
        name: "Summary Sales Report {{ date('m-d-y') }}.xlsx",
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