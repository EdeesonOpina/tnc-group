@php
    use Carbon\Carbon;
    use App\Models\BudgetRequestFormStatus;
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
  <a href="{{ route('internals.brf.view', [$budget_request_form->id]) }}" class="no-underline">
    <button class="btn btn-light">Go Back</button>
  </a>
  <button class="btn btn-success" onclick="printDiv('printableArea')">Print Page</button>
  <br><br>
  <!-- START OF PRINTABLE AREA -->
  <div id="printableArea">
    @if ($budget_request_form->project->company->image)
        <img src="{{ url($budget_request_form->project->company->image) }}" width="350px">
    @else
        <img src="{{ url(env('APP_ICON')) }}" width="80px">
    @endif
    <br><br><br>
    <h2 class="font-change">Cost Estimate</h2>
    <table class="table border-bottom no-border table-borderless font-change">
        <tbody>
            <tr>
                <td>
                    <div class="text-label"><strong>BRF #:</strong></div>
                </td>
                <td>
                    {{ $budget_request_form->reference_number }}<br>
                </td>
                <td>
                    <strong>Date</strong>
                </td>
                <td class="text-right">
                        {{ $budget_request_form->created_at->format('M d Y') }}
                </td>
            </tr>
            <tr>
                <td>
                    <div class="text-label"><strong>Pay To:</strong></div>
                </td>
                <td>
                    {{ $budget_request_form->payment_for_user->firstname }} {{ $budget_request_form->payment_for_user->lastname }}<br>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="text-label"><strong>In Payment For:</strong></div>
                </td>
                <td>
                    {{ $budget_request_form->name }}<br>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="text-label"><strong>Project:</strong></div>
                </td>
                <td>
                    {{ $budget_request_form->project->name }}<br>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="text-label"><strong>Get Budget From:</strong></div>
                </td>
                <td>
                    {{ $budget_request_form->project->company->name }}<br>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="text-label"><strong>Needed Date:</strong></div>
                </td>
                <td>
                    {{ $budget_request_form->needed_date }}<br>
                </td>
            </tr>
        </tbody>
    </table>

    <br>

    <table class="table table-bordered font-change">
        <thead>
            <tr>
                <th id="compact-table">#ID</th>
                <th id="compact-table">Particulars</th>
                <th id="compact-table">Quantity</th>
                <th id="compact-table">Unit Price</th>
                <th id="compact-table">Total Price</th>
            </tr>
        </thead>
        <tbody class="list" id="companies">
            @foreach ($budget_request_form_details as $budget_request_form_detail)
                <tr>
                    <td>{{ $budget_request_form_detail->id }}</td>
                    <td>
                        {{ $budget_request_form_detail->name }}
                    </td>
                    <td>{{ $budget_request_form_detail->qty }}</td>
                    <td>P{{ number_format($budget_request_form_detail->price, 2) }}</td>
                    <td>P{{ number_format($budget_request_form_detail->total, 2) }}</td>
                </tr>
            @endforeach
            <tr> 
                <td colspan="3">&nbsp;</td>
                <td id="compact-table"><strong>Total Cost</strong></th>
                <td id="compact-table">P{{ number_format($budget_request_form_details_total, 2) }}</th>
            </tr>
        </tbody>
    </table>

    <table class="table border-bottom no-border table-borderless font-change">
        <tbody>
            <tr>
                <td>
                    <div class="text-label"><strong>Remarks:</strong></div>
                </td>
                <td>
                    {{ $budget_request_form->remarks }}<br>
                </td>
            </tr>
        </tbody>
    </table>

    <table class="table border-bottom no-border table-borderless font-change">
        <tbody>
            <tr>
                <td class="text-left">
                    <p class="font-change">
                      <strong>Prepared By:</strong><br>
                    </p>
                </td>
                <td>
                    <p class="font-change">
                      <strong>Noted By:</strong><br>
                      @if ($budget_request_form->payment_for_user->signature)
                          <img src="{{ url($budget_request_form->payment_for_user->signature) }}" width="80px"><br>
                      @else
                        <br><br><br>
                      @endif
                      <strong>{{ $budget_request_form->payment_for_user->firstname }} {{ $budget_request_form->payment_for_user->lastname }}</strong><br>
                      {{ $budget_request_form->payment_for_user->role }}<br>
                      {{ $budget_request_form->payment_for_user->position }}<br>
                      {{ $budget_request_form->payment_for_user->company->name }}
                    </p>
                </td>

                <td>
                    <p class="font-change">
                      <strong>Checked By:</strong>
                    </p>
                </td>

                <td>
                    <p class="font-change">
                      <strong>Approved By:</strong>
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