@php
  use Carbon\Carbon;
  use App\Models\Expense;
  use App\Models\ExpenseStatus;
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
    <a href="{{ route('admin.reports.expenses') }}" style="text-decoration: none">
      <button class="btn btn-default" id="margin-right">Go Back</button>
    </a>
    <button type="button" id="btnExport" class="btn btn-success" onclick="fnExcelReport();">Create Excel File</button>
    <button class="btn btn-success" onClick="printdiv('div_print');" id="margin-left">Print page</button>
    <br><br>

    <div id="div_print" class="font-change">
      <img src="{{ url(env('BIG_FOUR_LOGO')) }}" width="180px">
      <hr>
      <h4><b>Expenses Report</b></h4>
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
            <th id="compact-table">Company</th>
            <th id="compact-table">Category</th>
            <th id="compact-table">Description</th>
            <th id="compact-table">Note</th>
            <th id="compact-table">Date</th>
            <th id="compact-table">Price</th>
            
            </tr>
          </thead>
          <tbody>
            @foreach($expenses as $expense)
                <tr>
                    <td id="compact-table">{{ $expense->company->name ?? null }}</td>
                    <td id="compact-table">{{ $expense->category->name }}</td>
                    <td id="compact-table">{{ $expense->description }}</td>
                    <td id="compact-table">{{ $expense->note }}</td>
                    <td id="compact-table">{{ Carbon::parse($expense->date)->format('M d Y') }}</td>
                    <td id="compact-table">{{ number_format($expense->price, 2) }}</td>                 
                </tr>
            @endforeach

            <tr>
              <td colspan="4"></td>
              <td id="compact-table"><b>Grand Total</b></td>
              <td style="width:1%;white-space:nowrap !important; font-weight: 500 !important;"><b>{{ number_format($grand_total, 2) }}</b></td>
            </tr>
          </tbody>
        </table>
        <br>

        <table class="table table-responsive-sm table-bordered table-striped table-sm" id="summaryTable">
          <thead>
            <tr>
              <th>Expense Count Report</th>
              <th>&nbsp;</th>
              <th>Expense Total Report</th>
              <th>&nbsp;</th>
            </tr>
          </thead>

          <tbody>
            @foreach ($expense_categories as $expense_category)
            @php
                $expense_category_count = Expense::whereBetween('date', [
                                                $from_date, $to_date
                                            ])
                                            ->where('category_id', $expense_category->id)
                                            ->where('status', '!=', ExpenseStatus::INACTIVE)
                                            ->count();

                $expense_category_total = Expense::whereBetween('date', [
                                                $from_date, $to_date
                                            ])
                                            ->where('category_id', $expense_category->id)
                                            ->where('status', '!=', ExpenseStatus::INACTIVE)
                                            ->sum('price');
            @endphp
            <tr>
              <td>Total {{ $expense_category->name }} Count</td>
              <td style="font-weight: 500 !important;">{{ $expense_category_count }}</td>

              <td>Total {{ $expense_category->name }} Amount</td>
              <td style="font-weight: 500 !important;">{{ number_format($expense_category_total, 2) }}</td>
            </tr>
            @endforeach
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