@php
    use Carbon\Carbon;
    use App\Models\ProjectDetail;
    use App\Models\ProjectDetailStatus;
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

    .table-color-primary {
        color: #E74414 !important;
    }

    .no-space {
        padding: 0px !important;
    }

    #compact-table {
        width:1%;
        white-space:nowrap;
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
  <!-- START OF PRINTABLE AREA -->
  <div id="printableArea">
    <!-- @if ($project->company->image)
        <img src="{{ url($project->company->image) }}" width="350px">
    @else
        <img src="{{ url(env('APP_LOGO_WITH_TEXT')) }}" width="80px">
    @endif
    <br><br><br> -->
    <br><br>
    <img src="{{ url(env('APP_LOGO_WITH_TEXT')) }}" width="160px">
    <br><br>
    <strong class="font-change table-color-primary">Cost Estimate</strong>
    <table class="table border-bottom no-border table-borderless font-change">
        <tbody>
            <tr>
                <td>
                    <div class="text-label font-change"><strong>CE #:</strong></div>
                </td>
                <td>
                    {{ $project->reference_number }}<br>
                </td>
                <td>
                    <strong>Date</strong>
                </td>
                <td class="text-right">
                        {{ $project->created_at->format('M d Y') }}
                </td>
            </tr>
            <tr>
                <td>
                    <div class="text-label"><strong>Client Name:</strong></div>
                </td>
                <td>
                    {{ $project->client->name }}<br>
                </td>
                <td>
                    <strong>Company</strong>
                </td>
                <td class="text-right">
                        {{ $project->company->name }}
                </td>
            </tr>
            <tr>
                <td>
                    <div class="text-label"><strong>Project Name:</strong></div>
                </td>
                <td>
                    {{ $project->name }}<br>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="text-label"><strong>Project Duration:</strong></div>
                </td>
                <td>
                    {{ Carbon::parse($project->duration_date)->format('M d Y') }}<br>
                </td>
            </tr>
        </tbody>
    </table>

    <br>

    <table class="table table-bordered font-change">
        <thead>
            <tr>
                <th class="table-black-border table-color-primary"></th>
                <th class="table-black-border table-color-primary">Particulars</th>
                <th class="table-black-border table-color-primary">Qty</th>
                <th class="table-black-border table-color-primary">Desc.</th>
                @if ($project->has_usd == 1)
                    <th id="compact-table" class="table-black-border table-color-primary">Price (USD)</th>
                @endif
                <th id="compact-table" class="table-black-border table-color-primary">Price</th>
                <th id="compact-table" class="table-black-border table-color-primary">Total</th>
            </tr>
        </thead>
        <tbody class="list" id="companies">
            @foreach ($project_details->unique('category_id') as $project_detail)
                @php
                    $pjds = ProjectDetail::where('project_id', $project->id)
                                    ->where('category_id', $project_detail->category_id)
                                    ->where('status', '!=', ProjectDetailStatus::INACTIVE)
                                    ->get();
                @endphp
                    <tr>
                        <td colspan="1"><strong>{{ $project_detail->category->name }}</strong></td>
                        @if ($project->has_usd == 1)
                            <td colspan="6" id="compact-table">&nbsp;</td>
                        @else
                            <td colspan="5" id="compact-table">&nbsp;</td>
                        @endif

                    </tr>  
                @foreach ($pjds as $pjd)
                    <tr>
                        <td class="table-black-border">
                            @if ($pjd->sub_category)
                                <strong>{{ $pjd->sub_category->name }}</strong>
                            @endif
                        </td>
                        <td class="table-black-border">
                            <strong>{{ $pjd->name }}</strong>
                        </td>
                        <td class="table-black-border">{{ $pjd->qty }}</td>
                        <td class="table-black-border">{!! $pjd->description !!}</td>
                        @if ($project->has_usd == 1)
                            <td class="table-black-border">${{ number_format($pjd->usd_price, 2) }}</td>
                        @endif
                        <td class="table-black-border">P{{ number_format($pjd->price, 2) }}</td>
                        <td class="table-black-border">P{{ number_format($pjd->total, 2) }}</td>
                    </tr>
                @endforeach
            @endforeach

            @if ($project->has_usd == 1)
                <tr>
                    <td colspan="4" class="table-black-border">&nbsp;</td>
                    <td id="compact-table" class="table-black-border"><strong>Total Cost</strong></td>
                    <td id="compact-table" class="table-black-border">${{ number_format($project->usd_total, 2) }}</td>
                    <td id="compact-table" class="table-black-border">P{{ number_format($project->total, 2) }}</td>
                </tr>
                
                <tr>
                    <td colspan="4" class="table-black-border">&nbsp;</td>
                    <td id="compact-table" class="table-black-border"><strong>ASF</strong></td>
                    <td id="compact-table" class="table-black-border">
                        ${{ number_format($project->usd_asf, 2) }}
                    </td>
                    <td id="compact-table" class="table-black-border">
                        P{{ number_format($project->asf, 2) }}
                    </td>
                </tr>

                <tr>
                    <td colspan="4" class="table-black-border">&nbsp;</td>
                    <td id="compact-table" class="table-black-border"><strong>VAT</strong></td>
                    <td id="compact-table" class="table-black-border">
                        ${{ number_format($project->usd_vat, 2) }}
                    </td>
                    <td id="compact-table" class="table-black-border">
                        P{{ number_format($project->vat, 2) }}
                    </td>
                </tr>

                <tr>
                    <td colspan="4" class="table-black-border">&nbsp;</td>
                    <td id="compact-table" class="table-black-border"><strong>Grand Total</strong></td>
                    <td id="compact-table" class="table-black-border">${{ number_format($project->usd_total + $project->usd_vat + $project->usd_asf, 2) }}</td>
                    <td id="compact-table" class="table-black-border">P{{ number_format($project->total + $project->vat + $project->asf, 2) }}</td>
                </tr>
            @else
                <tr>
                    <td colspan="4" class="table-black-border">&nbsp;</td>
                    <td id="compact-table" class="table-black-border"><strong>Total Cost</strong></td>
                    <td id="compact-table" class="table-black-border">P{{ number_format($project->total, 2) }}</td>
                </tr>
                
                <tr>
                    <td colspan="4" class="table-black-border">&nbsp;</td>
                    <td id="compact-table" class="table-black-border"><strong>ASF</strong></td>
                    <td id="compact-table" class="table-black-border">
                        P{{ number_format($project->asf, 2) }}
                    </td>
                </tr>

                <tr>
                    <td colspan="4" class="table-black-border">&nbsp;</td>
                    <td id="compact-table" class="table-black-border"><strong>VAT</strong></td>
                    <td id="compact-table" class="table-black-border">
                        P{{ number_format($project->vat, 2) }}
                    </td>
                </tr>

                <tr>
                    <td colspan="4" class="table-black-border">&nbsp;</td>
                    <td id="compact-table" class="table-black-border"><strong>Grand Total</strong></td>
                    <td id="compact-table" class="table-black-border">P{{ number_format($project->total + $project->vat + $project->asf, 2) }}</td>
                </tr>
            @endif
        </tbody>
    </table>
    <br>
    <strong class="font-change">TERMS AND CONDITIONS</strong>
    <br><br>
    <table class="table table-bordered font-change">
        <tbody>
            <tr>
                <td class="table-black-border"><strong>Proposal Ownership.</strong></td>
                <td class="table-black-border">{!! $project->proposal_ownership !!}</td>
            </tr>

            <tr>
                <td class="table-black-border"><strong>Confidentiality.</strong></td>
                <td class="table-black-border">{!! $project->confidentiality !!}</td>
            </tr>

            <tr>
                <td class="table-black-border"><strong>Project Confirmation.</strong></td>
                <td class="table-black-border">{!! $project->project_confirmation !!}</td>
            </tr>

            <tr>
                <td class="table-black-border"><strong>Payment Terms</strong></td>
                <td class="table-black-border">{!! $project->payment_terms !!}</td>
            </tr>

            <tr>
                <td class="table-black-border"><strong>Validity.</strong></td>
                <td class="table-black-border">{!! $project->validity !!}</td>
            </tr>
        </tbody>
    </table>

    <table class="table border-bottom no-border table-borderless font-change">
        <tbody>
            <tr>
                <td class="text-left">
                    <p class="font-change">
                      <strong>Prepared By:</strong><br>
                      @if ($project->prepared_by_user->signature)
                          <br><img src="{{ url($project->prepared_by_user->signature) }}" width="80px" height="60px"><br>
                      @else
                        <br><br><br>
                      @endif
                      <strong>{{ $project->prepared_by_user->firstname }} {{ $project->prepared_by_user->lastname }}</strong><br>
                      {{ $project->prepared_by_user->role }}<br>
                      {{ $project->prepared_by_user->position }}<br>
                      {{ $project->prepared_by_user->company->name }}
                    </p>
                </td>
                <td>
                    <p class="font-change">
                      <strong>Noted By:</strong><br>
                      @if ($project->noted_by_user->signature)
                          <br><img src="{{ url($project->noted_by_user->signature) }}" width="80px" height="60px"><br>
                      @else
                        <br><br><br>
                      @endif
                      <strong>{{ $project->noted_by_user->firstname }} {{ $project->noted_by_user->lastname }}</strong><br>
                      {{ $project->noted_by_user->role }}<br>
                      {{ $project->noted_by_user->position }}<br>
                      {{ $project->noted_by_user->company->name }}
                    </p>
                </td>

                <td>
                    <p class="font-change">
                      <strong>Conforme</strong>
                        @if ($project->conforme_signature)
                          <br><img src="{{ url($project->conforme_signature) }}" width="80px" height="60px"><br>
                        @else
                            <br><br><br><br>
                        @endif
                        <strong>{{ $project->client_contact->name }}</strong><br>
                        {{ $project->client_contact->position }}<br>
                        {{ $project->client_contact->client->name }}<br>
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