@php
    use Carbon\Carbon;
    use App\Models\ProjectStatus;
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
    
    <!-- Font Awesome FREE Icons -->
    <link type="text/css" href="{{ url('auth/admin/assets/css/vendor-fontawesome-free.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ url('auth/admin/assets/css/vendor-fontawesome-free.rtl.css') }}" rel="stylesheet">
    
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
    </style>
</head>
<body>
<div class="container">
  <br><br>
  <a href="{{ route('internals.projects.view', [$project->reference_number]) }}" class="no-underline">
    <button class="btn btn-light">Go Back</button>
  </a>
  <button class="btn btn-success" onclick="printDiv('printableArea')">Print Page</button>
  <br><br>
  <!-- START OF PRINTABLE AREA -->
  <div id="printableArea">
    @if ($project->company->image)
        <img src="{{ url($project->company->image) }}" width="200px">
    @else
        <img src="{{ url(env('APP_ICON')) }}" width="80px">
    @endif
    
    <br><br>
    <h2 class="font-change">Internal CE</h2>
    <table class="table border-bottom no-border table-borderless font-change">
        <tbody>
            <tr>
                <td class="no-space">
                    <div class="text-label"><strong>CE #:</strong></div>
                </td>
                <td class="no-space">
                    {{ $project->reference_number }}<br>
                </td>
                <td class="no-space">
                    <strong>Date</strong>
                </td>
                <td class="text-right no-space">
                        {{ $project->created_at->format('M d Y') }}
                </td>
            </tr>
            <tr>
                <td class="no-space">
                    <div class="text-label"><strong>Client Name:</strong></div>
                </td>
                <td class="no-space">
                    {{ $project->client->name }}<br>
                </td>
                <td class="no-space">
                    <strong>Company</strong>
                </td>
                <td class="text-right no-space">
                        {{ $project->company->name }}
                </td>
            </tr>
            <tr>
                <td class="no-space">
                    <div class="text-label"><strong>Project Name:</strong></div>
                </td>
                <td class="no-space">
                    {{ $project->name }}<br>
                </td>
            </tr>
            <tr>
                <td class="no-space">
                    <div class="text-label"><strong>Project Duration:</strong></div>
                </td>
                <td class="no-space">
                    <!-- {{ Carbon::parse($project->duration_date)->format('M d Y') }}<br> -->
                    {{ Carbon::parse($project->start_date)->format('M d Y') ?? null }} - {{ Carbon::parse($project->end_date)->format('M d Y') ?? null }}
                </td>
            </tr>
        </tbody>
    </table>

    <br>

    <table class="table table-bordered font-change">
        <thead>
            <tr>
                <th id="compact-table" class="table-black-border table-color-primary min-space"></th>
                <th id="compact-table" class="table-black-border table-color-primary min-space">Name</th>
                <th id="compact-table" class="table-black-border table-color-primary min-space">Description</th>
                <th id="compact-table" class="table-black-border table-color-primary min-space">Quantity</th>
                <th id="compact-table" class="table-black-border table-color-primary min-space">Internal Price</th>
                <th id="compact-table" class="table-black-border table-color-primary min-space">Total Price</th>
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
                        <td colspan="1" id="compact-table" class="no-border min-space"><strong>{{ $project_detail->category->name }}</strong></td>
                        <td colspan="6" id="compact-table" class="no-border min-space">&nbsp;</td>
                    </tr>
                @foreach ($pjds as $pjd)
                    <tr>
                        <td class="table-black-border min-space">
                            @if ($pjd->sub_category)
                                <strong>{{ $pjd->sub_category->name }}</strong>
                            @endif
                        </td>
                        <td class="table-black-border min-space">
                            <strong>{{ $pjd->name }}</strong>
                        </td>
                        <td class="table-black-border min-space">{!! $pjd->description !!}</td>
                        <td class="table-black-border min-space">{{ $pjd->qty }}</td>
                        <td class="table-black-border min-space">P{{ number_format($pjd->price, 2) }}</td>
                        <td class="table-black-border min-space">P{{ number_format($pjd->total, 2) }}</td>
                    </tr>
                @endforeach
            @endforeach
            <tr>
                <td colspan="4" class="table-black-border min-space">&nbsp;</td>
                <td id="compact-table" class="table-black-border min-space"><strong>Total Cost</strong></td>
                <td id="compact-table" class="table-black-border min-space">P{{ number_format($grand_total, 2) }}</td>
            </tr>
            
            <tr>
                <td colspan="4" class="table-black-border min-space">&nbsp;</td>
                <td id="compact-table" class="table-black-border min-space"><strong>ASF</strong></td>
                <td id="compact-table" class="table-black-border min-space">
                    P{{ number_format($project->asf, 2) }}
                </td>
            </tr>

            <tr>
                <td colspan="4" class="table-black-border min-space">&nbsp;</td>
                <td id="compact-table" class="table-black-border min-space"><strong>Internal CE Grand Total</strong></td>
                <td id="compact-table" class="table-black-border min-space">P{{ number_format($internal_grand_total, 2) }}</td>
            </tr>

            <tr>
                <td colspan="4" class="table-black-border min-space">&nbsp;</td>
                <td id="compact-table" class="table-black-border min-space"><strong>Profit</strong></td>
                <td id="compact-table" class="table-black-border min-space">P{{ number_format($grand_total - $internal_grand_total, 2) }}</td>
            </tr>
        </tbody>
    </table>
    <br><br>
    <h4 class="font-change">TERMS AND CONDITIONS</h4>
    <table class="table table-bordered font-change">
        <tbody>
            <tr>
                <td class="table-black-border min-space"><strong>Proposal Ownership.</strong></td>
                <td class="table-black-border min-space">{!! $project->proposal_ownership !!}</td>
            </tr>

            <tr>
                <td class="table-black-border min-space"><strong>Confidentiality.</strong></td>
                <td class="table-black-border min-space">{!! $project->confidentiality !!}</td>
            </tr>

            <tr>
                <td class="table-black-border min-space"><strong>Project Confirmation.</strong></td>
                <td class="table-black-border min-space">{!! $project->project_confirmation !!}</td>
            </tr>

            <tr>
                <td class="table-black-border min-space"><strong>Payment Terms</strong></td>
                <td class="table-black-border min-space">{!! $project->payment_terms !!}</td>
            </tr>

            <tr>
                <td class="table-black-border min-space"><strong>Validity.</strong></td>
                <td class="table-black-border min-space">{!! $project->validity !!}</td>
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
                        <br><br><br><br><br>
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
                      @if ($project->status == ProjectStatus::APPROVED || $project->status == ProjectStatus::DONE)
                            @if ($project->noted_by_user->signature)
                                  <br><img src="{{ url($project->noted_by_user->signature) }}" width="80px" height="60px"><br>
                            @else
                                <br><br><br><br><br>
                            @endif
                        @else
                            <br><br><br><br><br>
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
                            <br><br><br><br><br><br>
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