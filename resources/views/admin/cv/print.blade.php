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
        font-family: "Lucida Console", "Courier New", monospace !important;
    }

    .heading-text {
        font-size: 30px;
    }

    .table-black-border {
        /*border: 2px solid #000 !important;*/
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
  <a href="{{ route('internals.cv') }}" class="no-underline">
    <button class="btn btn-light">Go Back</button>
  </a>
  <button class="btn btn-success" onclick="printDiv('printableArea')">Print Page</button>
  <!-- START OF PRINTABLE AREA -->
  <div id="printableArea">
    <br><br>
    <img src="{{ url(env('APP_LOGO_WITH_TEXT')) }}" width="160px">
    <br><br>
    <h2 class="font-change">Check Voucher Form</h2>
    <table class="table border-bottom no-border table-borderless font-change">
        <tbody>
            <tr>
                <td class="no-space">
                    <div class="text-label"><strong>CV #:</strong></div>
                </td>
                <td class="no-space">
                    {{ $cv->reference_number }}<br>
                </td>
                <td class="no-space">
                    <strong>Date</strong>
                </td>
                <td class="text-right no-space">
                    {{ $budget_request_form->created_at->format('M d Y') }}
                </td>
            </tr>
            <tr>
                <td class="no-space">
                    <div class="text-label"><strong>BRF #:</strong></div>
                </td>
                <td class="no-space">
                    {{ $cv->budget_request_form->reference_number }}<br>
                </td>
                <td class="no-space">
                    <div class="text-label"><strong>Needed Date:</strong></div>
                </td>
                <td class="text-right no-space">
                    {{ Carbon::parse($budget_request_form->needed_date)->format('M d Y') }}<br>
                </td>
            </tr>
            <tr>
                <td class="no-space">
                    <div class="text-label"><strong>CE #:</strong></div>
                </td>
                <td class="no-space">
                    {{ $cv->budget_request_form->project->reference_number }}<br>
                </td>
            </tr>
            <tr>
                <td class="no-space">
                    <div class="text-label"><strong>Pay To:</strong></div>
                </td>
                <td class="no-space">
                    @if ($budget_request_form->payment_for_user)
                        {{ $budget_request_form->payment_for_user->firstname }} {{ $budget_request_form->payment_for_user->lastname }}
                    @endif

                    @if ($budget_request_form->payment_for_supplier)
                        {{ $budget_request_form->payment_for_supplier->name }}
                    @endif
                    <br>
                </td>
                <td class="no-space">
                    <div class="text-label"><strong>In Payment For:</strong></div>
                </td>
                <td class="no-space text-right">
                    {{ $budget_request_form->name }}<br>
                </td>
            </tr>
            <tr>
                <td class="no-space">
                    <div class="text-label"><strong>Project:</strong></div>
                </td>
                <td class="no-space">
                    {{ $budget_request_form->project->name }}<br>
                </td>
            </tr>
            <tr>
                <td class="no-space">
                    <div class="text-label"><strong>Get Budget From:</strong></div>
                </td>
                <td class="no-space">
                    {{ $budget_request_form->project->company->name }}<br>
                </td>
            </tr>
        </tbody>
    </table>

    <br>

    <table class="table table-bordered font-change">
        <thead>
            <tr>
                <th id="compact-table" class="table-black-border table-color-primary min-space">Particulars</th>
                <th id="compact-table" class="table-black-border table-color-primary min-space">Description</th>
                <th id="compact-table" class="table-black-border table-color-primary min-space">Quantity</th>
                <th id="compact-table" class="table-black-border table-color-primary min-space">Unit Price</th>
                <th id="compact-table" class="table-black-border table-color-primary min-space">Total Price</th>
            </tr>
        </thead>
        <tbody class="list" id="companies">
            @foreach ($budget_request_form_details as $budget_request_form_detail)
                <tr>
                    <td class="no-border min-space">{{ $budget_request_form_detail->name }}</td>
                    <td class="no-border min-space">{{ $budget_request_form_detail->description }}</td>
                    <td class="no-border min-space">{{ $budget_request_form_detail->qty }}</td>
                    <td class="no-border min-space">P{{ number_format($budget_request_form_detail->price, 2) }}</td>
                    <td class="no-border min-space">P{{ number_format($budget_request_form_detail->total, 2) }}</td>
                </tr>
            @endforeach
            <tr> 
                <td colspan="3" class="table-black-border min-space">&nbsp;</td>
                <td id="compact-table" class="table-black-border min-space"><strong>Total Cost</strong></th>
                <td id="compact-table" class="table-black-border min-space">P{{ number_format($budget_request_form_details_total, 2) }}</th>
            </tr>
        </tbody>
    </table>

    <table class="table table-bordered font-change">
        <tbody>
            @foreach ($remarks as $remark)
                <tr>
                    <td colspan="6">
                        <div class="text-label"><strong>Remarks:</strong></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Bank</strong><br>
                        {{ $remark->account->bank }}<br>
                    </td>
                    <td>
                        <strong>ACCT NAME</strong><br>
                        {{ $remark->account->name }}<br>
                    </td>
                    <td>
                        <strong>ACCT #</strong><br>
                        {{ $remark->account->number }}<br>
                    </td>
                    <td>
                        <strong>CHECK #</strong><br>
                        {{ $remark->cheque_number }}<br>
                    </td>
                    <td>
                        <strong>CURRENCY</strong><br>
                        {{ $remark->currency }}<br>
                    </td>
                    <td>
                        <strong>AMOUNT</strong><br>
                        {{ number_format($remark->amount, 2) }}<br>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="table table-bordered font-change">
        <tbody>
            <tr>
                <td class="text-left">
                    <p class="font-change">
                      <strong>Prepared By:</strong><br>
                      @if ($budget_request_form->payment_for_user)
                          @if ($budget_request_form->payment_for_user->signature)
                              <img src="{{ url($budget_request_form->payment_for_user->signature) }}" width="80px"><br>
                          @else
                            <br><br><br><br><br>
                          @endif
                      @else
                        <br><br><br><br><br>
                      @endif
                      <strong>
                        @if ($budget_request_form->payment_for_user)
                            {{ $budget_request_form->payment_for_user->firstname }} {{ $budget_request_form->payment_for_user->lastname }}
                        @endif

                        @if ($budget_request_form->payment_for_supplier)
                            {{ $budget_request_form->payment_for_supplier->name }}
                        @endif
                      </strong><br>
                      @if ($budget_request_form->payment_for_user)
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
                    </p>
                </td>
                <td>
                    <p class="font-change">
                        <strong>Approved By:</strong>
                    </p>
                </td>

                <td>
                    <p class="font-change">
                      <strong>Received By:</strong>
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