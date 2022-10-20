@php
    use Carbon\Carbon;
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

    body {
        background: #fff;
    }
    </style>
</head>
<body>
<div class="container">
  <br><br>
  <a href="{{ route('accounting.cash-advances.view', [$cash_advance->reference_number]) }}" class="no-underline">
    <button class="btn btn-light">Go Back</button>
  </a>
  <button class="btn btn-success" onclick="printDiv('printableArea')">Print Page</button>
  <br><br>
  <!-- START OF PRINTABLE AREA -->
  <div id="printableArea">
    <img src="{{ url(env('BIG_FOUR_LOGO')) }}" width="150px">
    <hr>
    <h2 class="font-change">Cash Advance</h2>
    <h5 class="font-change"><strong>C.A. No.:</strong> {{ $cash_advance->reference_number }}</h5>
    <table class="table border-bottom no-border table-borderless font-change">
        <tbody>
            <tr>
                <td class="text-left">
                    <div class="text-label"><strong>Employee</strong></div>
                    <p class="mb-4">
                    <strong class="text-body">{{ $cash_advance->user->firstname }} {{ $cash_advance->user->lastname }}</strong><br>
                    {{ $cash_advance->user->line_address_1 }}<br>
                    {{ $cash_advance->user->line_address_2 }}<br>
                    @if ($cash_advance->user->phone)
                        {{ $cash_advance->user->phone }} / 
                    @endif
                    @if ($cash_advance->user->mobile)
                        {{ $cash_advance->user->mobile }}
                    @endif
                    </p>
                    <strong>Date: </strong>{{ date('M d Y') }}
                </td>
                <td class="text-right">
                    <div class="text-label"><strong>Branch</strong></div>
                    <p class="mb-4">
                    <strong class="text-body">{{ $cash_advance->user->branch->name }}</strong><br>
                    {{ $cash_advance->user->branch->line_address_1 }}<br>
                    {{ $cash_advance->user->branch->line_address_2 }}<br>
                    @if ($cash_advance->user->branch->phone)
                        {{ $cash_advance->user->branch->phone }} / 
                    @endif
                    @if ($cash_advance->user->branch->mobile)
                        {{ $cash_advance->user->branch->mobile }}
                    @endif
                    </p>
                </td>
            </tr>
        </tbody>
    </table>

    <table class="table table-bordered font-change">
        <thead>
            <tr>
                <th id="compact-table">Amount for Cash Advance</th>
                <th id="compact-table">P{{ number_format($cash_advance->price, 2) }}</th>
            </tr>
            <tr>
                <th id="compact-table">Date Borrowed</th>
                <th id="compact-table">{{ Carbon::parse($cash_advance->date_borrowed)->format('M d Y') }}</th>
            </tr>
            <tr>
                <th id="compact-table">Remarks</th>
                <th id="compact-table">{{ $cash_advance->reason }}</th>
            </tr>
        </thead>
    </table>

    <table class="table border-bottom no-border table-borderless font-change">
        <tbody>
            <tr>
                <td class="text-left">
                    <p class="font-change">
                      <strong>Created By:</strong><br>
                      @if ($cash_advance->user->signature)
                          <img src="{{ url($cash_advance->user->signature) }}" width="80px"><br>
                      @else
                        <br><br><br><br>
                      @endif
                      <strong>{{ $cash_advance->user->firstname }} {{ $cash_advance->user->lastname }}</strong><br>
                      {{ $cash_advance->user->role }}<br>
                      {{ Carbon::parse($cash_advance->created_at)->format('M d Y') }}
                    </p>
                </td>
                <td class="text-right">
                    <p class="font-change">
                      <strong>Approved By:</strong><br>
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