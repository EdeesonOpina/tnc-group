@include('layouts.auth.header')
@php
    use Carbon\Carbon;
    use App\Models\ProjectStatus;
    use App\Models\ProjectDetail;
    use App\Models\ProjectDetailStatus;
    use App\Models\BudgetRequestFormStatus;
@endphp

<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('internals.projects') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item">Projects</li>
                    <li class="breadcrumb-item active" aria-current="page">View Project</li>
                </ol>
            </nav>
            <h1 class="m-0">Project</h1>
        </div>
        @if ($project->status == ProjectStatus::APPROVED)
            <a href="#" data-toggle="modal" data-target="#share-project-{{ $project->id }}">
                <button type="button" class="btn btn-success" id="margin-right"><i class="fa fa-link" id="margin-right"></i>Share CE Link</button>
            </a>

            <a href="{{ route('internals.exports.projects.excel', [$project->id]) }}">
                <button type="button" class="btn btn-success" id="margin-right"><i class="fa fa-file-excel" id="margin-right"></i>Create Excel</button>
            </a>

            <a href="{{ route('internals.exports.projects.pdf.ce', [$project->id]) }}">
                <button type="button" class="btn btn-danger" id="margin-right"><i class="fa fa-file-pdf" id="margin-right"></i>Create PDF</button>
            </a>

            <a href="{{ route('internals.exports.projects.print.ce', [$project->reference_number]) }}">
                <button type="button" class="btn btn-light" id="margin-right"><i class="fa fa-print" id="margin-right"></i>Print CE</button>
            </a>

            <a href="{{ route('internals.exports.projects.print.internal-ce', [$project->reference_number]) }}">
                <button type="button" class="btn btn-light" id="margin-right"><i class="fa fa-print" id="margin-right"></i>Print Internal CE</button>
            </a>
        @endif
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')

    <div class="row">
        <div class="col-md-12">
            <div id="spaced-card" class="card card-body">
                <h4>Cost Estimate</h4>
                <br>
                <div class="row">
                    <div class="col">
                        <div class="row">
                            <div class="col">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <strong>CE#</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        {{ $project->reference_number }}
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <strong>Date</strong>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            {{ $project->created_at->format('M d Y') }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <strong>Client Name</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            {{ $project->client->name }}
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <strong>Has USD</strong>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            @if ($project->has_usd == 1)
                                                Yes
                                            @else
                                                No
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <strong>Company</strong>
                                        </div>
                                    </div>
                                    <div class="col">
                                        {{ $project->company->name }}
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <strong>Project Name</strong>
                                        </div>
                                    </div>
                                    <div class="col">
                                        {{ $project->name }}
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <strong>Project Duration</strong>
                                        </div>
                                    </div>
                                    <div class="col">
                                        {{-- Carbon::parse($project->end_date)->format('M d Y') --}}
                                        {{ Carbon::parse($project->start_date)->format('M d Y') }} - {{ Carbon::parse($project->end_date)->format('M d Y') }}
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
                <br>

                <div class="row">
                    <div class="col">
                        <h4 class="card-header__title flex m-0">Project Details</h4>
                    </div>
                </div>
                <br>

                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table"></th>
                                <th id="compact-table">Particulars</th>
                                <th id="compact-table">Quantity</th>
                                <th>Description</th>
                                @if ($project->has_usd == 1)
                                    <th id="compact-table">USD Price</th>
                                @endif
                                <th id="compact-table">Unit Price</th>
                                <th id="compact-table">Total Price</th>
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
                                    <td colspan="1" id="compact-table"><strong>{{ $project_detail->category->name }}</strong></td>
                                    <td colspan="9">&nbsp;</td>
                                </tr>  
                                @foreach ($pjds as $pjd)
                                    <tr>
                                        <td id="compact-table">
                                            @if ($pjd->sub_category)
                                                <strong>{{ $pjd->sub_category->name }}</strong>
                                            @endif
                                        </td>
                                        <td id="compact-table">
                                            <strong>{{ $pjd->name }}</strong>
                                        </td>
                                        <td id="compact-table">{{ $pjd->qty }}</td>
                                        <td id="compact-table">{!! $pjd->description !!}</td>
                                        @if ($project->has_usd == 1)
                                            <td id="compact-table">${{ number_format($pjd->usd_price, 2) }}</td>
                                        @endif
                                        <td id="compact-table">P{{ number_format($pjd->price, 2) }}</td>
                                        <td id="compact-table">P{{ number_format($pjd->total, 2) }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                            <tr>
                                <td colspan="3">&nbsp;</td>
                                @if ($project->has_usd == 0)
                                    <td>&nbsp;</td>
                                @endif
                                @if ($project->has_usd == 1)
                                    <td id="compact-table"><strong>Total Cost (USD)</strong></td>
                                    <td id="compact-table">${{ number_format($project->usd_total, 2) }}</td>
                                @endif
                                <td id="compact-table"><strong>Total Cost</strong></td>
                                <td id="compact-table">P{{ number_format($project->total, 2) }}</td>
                            </tr>

                            <tr>
                                <td colspan="">&nbsp;</td>
                                @if ($project->has_usd == 0)
                                    <td>&nbsp;</td>
                                @endif
                                <td id="compact-table"><strong>ASF Rate (%)</strong></td>
                                <td id="compact-table">
                                    {{ $project->margin }}%
                                </td>
                                @if ($project->has_usd == 1)
                                    <td id="compact-table"><strong>ASF (USD)</strong></td>
                                    <td id="compact-table">${{ number_format($project->usd_asf, 2) }}</td>
                                @endif
                                <td id="compact-table"><strong>ASF</strong></td>
                                <td id="compact-table">
                                    P{{ number_format($project->asf, 2) }}
                                </td>
                            </tr>

                            <tr>
                                <td colspan="">&nbsp;</td>
                                @if ($project->has_usd == 0)
                                    <td colspan="">&nbsp;</td>
                                @endif

                                <td id="compact-table"><strong>VAT Rate (%)</strong></td>
                                <td id="compact-table">
                                    {{ $project->vat_rate }}%
                                </td>

                                @if ($project->has_usd == 1)
                                    <td id="compact-table"><strong>VAT (USD)</strong></td>
                                    <td id="compact-table">${{ number_format($project->usd_vat, 2) }}</td>
                                @endif
                                <td id="compact-table"><strong>VAT</strong></td>
                                <td id="compact-table">
                                    P{{ number_format($project->vat, 2) }}
                                </td>
                            </tr>

                            <tr>
                                @if ($project->has_usd == 1)
                                    <td colspan="1">&nbsp;</td>
                                @else
                                    <td colspan="3">&nbsp;</td>
                                @endif

                                @if ($project->has_usd == 1)
                                    <td id="compact-table"><strong>USD Rate to PHP</strong></td>
                                    <td id="compact-table">
                                        P{{ number_format($project->usd_rate, 2) }}
                                    </td>
                                @endif

                                @if ($project->has_usd == 0)
                                    <td>&nbsp;</td>
                                @endif

                                @if ($project->has_usd == 1)
                                    <td id="compact-table"><strong>CE Grand Total (USD)</strong></td>
                                    <td id="compact-table">${{ number_format($usd_grand_total, 2) }}</td>
                                @endif
                                <td id="compact-table"><strong>CE Grand Total</strong></td>
                                <td id="compact-table">P{{ number_format($grand_total, 2) }}</td>
                            </tr>

                            <tr>
                                @if ($project->has_usd == 1)
                                    <td colspan="5">&nbsp;</td>
                                @else
                                    <td colspan="4">&nbsp;</td>
                                @endif
                                <td id="compact-table"><strong>Internal CE Grand Total</strong></td>
                                <td id="compact-table">P{{ number_format($internal_grand_total, 2) }}</td>
                            </tr>

                            <tr>
                                @if ($project->has_usd == 1)
                                    <td colspan="5">&nbsp;</td>
                                @else
                                    <td colspan="4">&nbsp;</td>
                                @endif
                                <td id="compact-table"><strong>Project Margin</strong></td>
                                <td id="compact-table">P{{ number_format(($project->total - $internal_grand_total) + $project->asf, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>

                    @if (count($project_details) <= 0)
                        <div style="padding: 20px">
                            <center><i class="material-icons icon-16pt mr-1 text-muted">assignment</i> No record/s found</center>
                        </div>
                    @endif
                </div>

                <br><br>

                <strong>Terms and Conditions</strong>
                <br><br>

                <div class="row">
                    <div class="col-md-4">
                        <strong>Proposal Ownership.</strong>
                    </div>
                    <div class="col">
                        {!! $project->proposal_ownership !!}
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Confidentiality.</strong>
                    </div>
                    <div class="col">
                        {!! $project->confidentiality !!}
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Project Confirmation.</strong>
                    </div>
                    <div class="col">
                        {!! $project->project_confirmation !!}
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Payment Terms</strong>
                    </div>
                    <div class="col">
                        {!! $project->payment_terms !!}
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Validity.</strong>
                    </div>
                    <div class="col">
                        {!! $project->validity !!}
                    </div>
                </div>
                <br><br>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <strong>Prepared By</strong>
                            @if ($project->prepared_by_user->signature)
                                  <br><img src="{{ url($project->prepared_by_user->signature) }}" width="120px" height="60px"><br>
                            @else
                                <br><br><br><br>
                            @endif
                            <strong>{{ $project->prepared_by_user->firstname }} {{ $project->prepared_by_user->lastname }}</strong><br>
                            {{ $project->prepared_by_user->position }}<br>
                            {{ $project->prepared_by_user->company->name }}<br>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <strong>Noted By</strong>
                            @if ($project->status == ProjectStatus::APPROVED || $project->status == ProjectStatus::DONE)
                                @if ($project->noted_by_user->signature)
                                      <br><img src="{{ url($project->noted_by_user->signature) }}" width="80px" height="60px"><br>
                                @else
                                    <br><br><br><br>
                                @endif
                            @else
                                <br><br><br><br>
                            @endif
                            <strong>{{ $project->noted_by_user->firstname }} {{ $project->noted_by_user->lastname }}</strong><br>
                            {{ $project->noted_by_user->position }}<br>
                            {{ $project->noted_by_user->company->name }}<br>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <strong>Conforme</strong>
                            @if ($project->conforme_signature)
                              <br><img src="{{ url($project->conforme_signature) }}" width="80px" height="60px"><br>
                            @else
                                <br><br><br><br>
                            @endif
                            <strong>{{ $project->client_contact->name }}</strong><br>
                            {{ $project->client_contact->position }}<br>
                            {{ $project->client_contact->client->name }}<br>
                        </div>
                    </div>
                </div>
            </div>

            <br>

            <div id="spaced-card" class="card card-body">
                <div class="row">
                    <div class="col">
                        <h4 class="card-header__title flex m-0">Budget Request Forms</h4>
                    </div>

                    <div class="col-md-2">
                        
                    </div>
                </div>
                <br>

                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table">#BRF</th>
                                <th id="compact-table">Payment For</th>
                                <th id="compact-table">Project</th>
                                <th id="compact-table">Needed Date</th>
                                <th id="compact-table">Total Price</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                            @foreach ($budget_request_forms as $budget_request_form)
                                <tr>
                                    <td>
                                        <strong>{{ $budget_request_form->reference_number }}</strong>
                                    </td>
                                    <td>
                                        @if ($budget_request_form->payment_for_user)
                                            {{ $budget_request_form->payment_for_user->firstname }} {{ $budget_request_form->payment_for_user->lastname }}
                                        @endif

                                        @if ($budget_request_form->payment_for_supplier)
                                            {{ $budget_request_form->payment_for_supplier->name }}
                                        @endif
                                    </td>
                                    <td id="compact-table">{{ $budget_request_form->project->name }}</td>
                                    <td id="compact-table"><i class="material-icons icon-16pt text-muted mr-1">today</i> {{ Carbon::parse($budget_request_form->needed_date)->format('M d Y') }}</td>
                                    <td>P{{ number_format($budget_request_form->total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if (count($budget_request_forms) <= 0)
                        <div style="padding: 20px">
                            <center><i class="material-icons icon-16pt mr-1 text-muted">assignment</i> No record/s found</center>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col">
            <!-- <div id="semi-spaced-card" class="card card-body">
                <h3>Items</h3>
                <br>
                <table></table>
            </div> -->
        </div>
    </div>
</div>

@include('layouts.auth.footer')