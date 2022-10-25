@php
    use Carbon\Carbon;
    use App\Models\LiquidationStatus;
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
        font-family: "Lucida Console", "Courier New", monospace;
    }
    </style>
</head>
<body>
<div class="container">
  <br><br>
  <a href="{{ route('internals.brf.view', [$budget_request_form->reference_number]) }}" class="no-underline">
    <button class="btn btn-light">Go Back</button>
  </a>
  <button class="btn btn-success" onclick="printDiv('printableArea')">Print Page</button>
  <br><br>
  <!-- START OF PRINTABLE AREA -->
  <div id="printableArea">
    <img src="{{ url(env('APP_LOGO_WITH_TEXT')) }}" width="160px">
    <br><br>
    <h2 class="font-change">Budget Request Form</h2>
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
                    @if ($budget_request_form->payment_for_user)
                        {{ $budget_request_form->payment_for_user->firstname }} {{ $budget_request_form->payment_for_user->lastname }}
                    @endif

                    @if ($budget_request_form->payment_for_supplier)
                        {{ $budget_request_form->payment_for_supplier->name }}
                    @endif<br>
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
                    {{ Carbon::parse($budget_request_form->needed_date)->format('M d Y') }}<br>
                </td>
            </tr>
        </tbody>
    </table>
    <br>

    <strong class="text-label">BRF Details</strong><br>
    <table class="table table-bordered font-change">
        <thead>
            <tr>
                <th id="compact-table">Particulars</th>
                <th id="compact-table">Quantity</th>
                <th id="compact-table">Unit Price</th>
                <th id="compact-table">Total Price</th>
            </tr>
        </thead>
        <tbody class="list" id="companies">
            @foreach ($budget_request_form_details as $budget_request_form_detail)
                <tr>
                    <td>
                        {{ $budget_request_form_detail->name }}
                    </td>
                    <td>{{ $budget_request_form_detail->qty }}</td>
                    <td>P{{ number_format($budget_request_form_detail->price, 2) }}</td>
                    <td>P{{ number_format($budget_request_form_detail->total, 2) }}</td>
                </tr>
            @endforeach
            <tr> 
                <td colspan="2">&nbsp;</td>
                <td id="compact-table"><strong>Total Cost</strong></th>
                <td id="compact-table">P{{ number_format($budget_request_form_details_total, 2) }}</th>
            </tr>
        </tbody>
    </table>

    <strong class="text-label">Liquidations</strong><br>
    <table class="table table-bordered font-change">
        <thead>
            <tr>
                <th id="compact-table">Particulars</th>
                <th id="compact-table">Category</th>
                <th id="compact-table">Particulars</th>
                <th id="compact-table">Description</th>
                <th id="compact-table">Cost</th>
            </tr>
        </thead>
        <tbody class="list" id="companies">
            @foreach($liquidations as $liquidation)
                <tr>
                    <td id="compact-table">
                        <strong>{{ $liquidation->budget_request_form->name }}</strong>
                    </td>
                    <td id="compact-table">
                        <b>{{ $liquidation->category->name }}</b>
                    </td>
                    <td id="compact-table">{{ $liquidation->name }}</td>
                    <td id="compact-table">{{ $liquidation->description }}</td>
                    <td id="compact-table">P{{ number_format($liquidation->cost, 2) }}</td> 
                </tr>
            @endforeach
            <tr> 
                <td colspan="3">&nbsp;</td>
                <td id="compact-table"><strong>Total Cost</strong></th>
                <td id="compact-table">P{{ number_format($liquidations_total, 2) }}</th>
            </tr>
        </tbody>
    </table>

    @if ($budget_request_form->remarks)
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
    @endif

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
                        @if ($budget_request_form->payment_for_user)
                            @if ($budget_request_form->payment_for_user->signature)
                                <img src="{{ url($budget_request_form->payment_for_user->signature) }}" width="80px"><br>
                            @else
                                <br><br><br>
                            @endif
                            
                            <strong>
                            @if ($budget_request_form->payment_for_user->role == 'Corporate')
                                {{ $budget_request_form->payment_for_user->corporate }}
                            @else
                                {{ $budget_request_form->payment_for_user->firstname }} {{ $budget_request_form->payment_for_user->lastname }}
                            @endif
                            </strong><br>

                            @if ($budget_request_form->payment_for_user->role)
                                {{ $budget_request_form->payment_for_user->role }}<br>
                            @endif

                            @if ($budget_request_form->payment_for_user->position)
                                {{ $budget_request_form->payment_for_user->position }}<br>
                            @endif

                            @if ($budget_request_form->payment_for_user->company)
                                {{ $budget_request_form->payment_for_user->company->name }}
                            @endif
                        @endif

                        @if ($budget_request_form->payment_for_supplier)
                            <br><br><br>
                            
                            <strong>
                                {{ $budget_request_form->payment_for_supplier->name }}
                            </strong><br>
                        @endif
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