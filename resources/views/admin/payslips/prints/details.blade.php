@php
    use Carbon\Carbon;
    use App\Models\PayslipAttendanceStatus;
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
        font-family: "Lucida Console", "Courier New", monospace !important;
    }

    .heading-text {
        font-size: 30px;
    }

    .table-black-border {
/*        border: 2px solid #333 !important;*/
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

    .no-border-right {
      border-right: none !important;
    }

    .table-color-primary {
        color: #E74414 !important;
    }

    .no-space {
        padding: 0px !important;
    }

    .min-space {
        padding: 5px !important;
    }

    #compact-table {
        width:1%;
        white-space:nowrap;
    }

    .page-break {
        page-break-after: always;
    }

    table {
        font-size: 10px !important;
    }

    body {
        background: #fff;
        font-family: "Lucida Console", "Courier New", monospace !important;
    }

    @page { margin: 0px; }
    </style>
</head>
<body>
<div class="container">
  <br><br>
  <a href="{{ route('hr.payslips.details', [$payslip->id]) }}" class="no-underline">
    <button class="btn btn-light">Go Back</button>
  </a>
  <button class="btn btn-success" onclick="printDiv('printableArea')">Print Page</button>
  <!-- START OF PRINTABLE AREA -->
  <div id="printableArea">
    <br><br>
    <img src="{{ url(env('APP_LOGO_WITH_TEXT')) }}" width="160px">
    <br><br>
    <h2 class="font-change">Payslip</h2>
    <table class="table border-bottom no-border table-borderless font-change">
        <tbody>
            <tr>
                <td class="no-space">
                    <div class="text-label"><strong>From Date:</strong></div>
                </td>
                <td class="no-space">
                    {{ Carbon::parse($from_date)->format('M d Y') }}<br>
                </td>
                <td class="no-space">
                    <strong>To Date:</strong>
                </td>
                <td class="no-space">
                    {{ Carbon::parse($to_date)->format('M d Y') }}
                </td>
            </tr>
            <tr>
                <td class="no-space">
                    <div class="text-label"><strong>Name:</strong></div>
                </td>
                <td class="no-space">
                    {{ $user->firstname }} {{ $user->lastname }}<br>
                </td>
                <td class="no-space">
                    <div class="text-label"><strong>Position:</strong></div>
                </td>
                <td class="no-space">
                    {{ $user->position }}<br>
                </td>
            </tr>
            <tr>
                <td class="no-space">
                    <div class="text-label"><strong>Salary:</strong></div>
                </td>
                <td class="no-space">
                    P{{ number_format($user->salary, 2) }}<br>
                </td>
                <td class="no-space">
                    <div class="text-label"><strong>Per Hour:</strong></div>
                </td>
                <td class="no-space">
                    P{{ number_format(($user->salary / 40) / 8, 2) }}<br>
                </td>
            </tr>
        </tbody>
    </table>
    <br>

    <strong class="text-label">Payslip Details</strong><br>
    <table class="table mb-0 thead-border-top-0 table-striped">
        <thead>
            <tr>
                <th id="compact-table" class="table-black-border table-color-primary min-space">Date</th>
                <th id="compact-table" class="table-black-border table-color-primary min-space">Type</th>
                <th id="compact-table" class="table-black-border table-color-primary min-space">Time In</th>
                <th id="compact-table" class="table-black-border table-color-primary min-space">Time Out</th>
                <th id="compact-table" class="table-black-border table-color-primary min-space">Hours</th>
                <th id="compact-table" class="table-black-border table-color-primary min-space">Per Hour</th>
                <th id="compact-table" class="table-black-border table-color-primary min-space">Total</th>
            </tr>
        </thead>
        <tbody class="list" id="companies">
            @foreach($attendances as $attendance)
                <tr>
                    <td id="compact-table" class="table-black-border min-space">{{ Carbon::parse($attendance->from_date)->format('M d Y') }}
                    </td>
                    <td id="compact-table" class="table-black-border min-space">{{ $attendance->type }}</td>
                    <td id="compact-table" class="table-black-border min-space">{{ $attendance->time_in }}</td>
                    <td id="compact-table" class="table-black-border min-space">{{ $attendance->time_out }}</td>
                    <td id="compact-table" class="table-black-border min-space">{{ $attendance->hours_rendered }}</td>
                    <td id="compact-table" class="table-black-border min-space">P{{ number_format($attendance->salary_per_hour, 2) }}</td>
                    <td id="compact-table" class="table-black-border min-space">P{{ number_format($attendance->total, 2) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3" class="table-black-border min-space"></td>
                <td class="table-black-border min-space"><strong>Hours</strong></td>
                <td class="table-black-border min-space"><strong>{{ $payslip->hours }}</strong></td>
                <td class="table-black-border min-space"><strong>Income</strong></td>
                <td class="table-black-border min-space"><strong>P{{ number_format($payslip->total, 2) }}</strong></td>
            </tr>
            <tr>
                <td colspan="5" class="table-black-border min-space"></td>
                <td class="table-black-border min-space"><strong>W-Tax</strong></td>
                <td class="table-black-border min-space"><strong>P{{ number_format($payslip->w_tax, 2) }}</strong></td>
            </tr>
            <tr>
                <td colspan="5" class="table-black-border min-space"></td>
                <td class="table-black-border min-space"><strong>SSS</strong></td>
                <td class="table-black-border min-space"><strong>P{{ number_format($payslip->sss, 2) }}</strong></td>
            </tr>
            <tr>
                <td colspan="5" class="table-black-border min-space"></td>
                <td class="table-black-border min-space"><strong>PHILHEALTH</strong></td>
                <td class="table-black-border min-space"><strong>P{{ number_format($payslip->philhealth, 2) }}</strong></td>
            </tr>
            <tr>
                <td colspan="5" class="table-black-border min-space"></td>
                <td class="table-black-border min-space"><strong>PAGIBIG</strong></td>
                <td class="table-black-border min-space"><strong>P{{ number_format($payslip->pagibig, 2) }}</strong></td>
            </tr>
            <!-- <tr>
                <td colspan="5" class="table-black-border min-space"></td>
                <td class="table-black-border min-space"><strong>GSIS</strong></td>
                <td class="table-black-border min-space"><strong>P{{ number_format($payslip->gsis, 2) }}</strong></td>
            </tr> -->
            <tr>
                <td colspan="5" class="table-black-border min-space"></td>
                <td class="table-black-border min-space"><strong>Total Income</strong></td>
                <td class="table-black-border min-space"><strong>P{{ number_format(($payslip->total - $total_deductions), 2) }}</strong></td>
            </tr>
        </tbody>
    </table>

    <table class="table border-bottom no-border table-borderless font-change">
        <tbody>
            <tr>
                <td class="table-black-border ">
                    <p class="font-change">
                    <br><br><br><br><br><br>
                    <strong>Employee Signature:</strong>
                    </p>
                </td>
            </tr>
        </tbody>
    </table>

    <small class="font-change">&copy; {{ str_replace('_', ' ', env('APP_NAME')) }} {{ date('Y') }}</small>
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