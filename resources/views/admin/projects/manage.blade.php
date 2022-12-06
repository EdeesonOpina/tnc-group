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
                    <li class="breadcrumb-item active" aria-current="page">Manage Project</li>
                </ol>
            </nav>
            <h1 class="m-0">Manage Project</h1>
        </div>
        <!-- <a href="{{ route('internals.exports.projects.print.ce', [$project->reference_number]) }}">
            <button type="button" class="btn btn-light" id="margin-right"><i class="fa fa-print" id="margin-right"></i>Print CE</button>
        </a>

        <a href="{{ route('internals.exports.projects.print.internal-ce', [$project->reference_number]) }}">
            <button type="button" class="btn btn-light" id="margin-right"><i class="fa fa-print" id="margin-right"></i>Print Internal CE</button>
        </a> -->

        @if ($project->status == ProjectStatus::ON_PROCESS || $project->status == ProjectStatus::DISAPPROVED)
            <a href="{{ route('internals.projects.for-approval', [$project->id]) }}">
                <button type="button" class="btn btn-success" id="margin-right"><i class="fa fa-check" id="margin-right"></i>Submit For Approval</button>
            </a>
        @endif

        @if (auth()->user()->id == $project->noted_by_user->id)
            @if ($project->status == ProjectStatus::FOR_APPROVAL)
                <a href="#" data-href="{{ route('internals.projects.approve', [$project->id]) }}" data-toggle="modal" data-target="#confirm-action">
                    <button type="button" class="btn btn-success" id="margin-right"><i class="fa fa-check" id="margin-right"></i>Approve</button>
                </a>

                <a href="#" data-toggle="modal" data-target="#disapprove-{{ $project->id }}">
                    <button type="button" class="btn btn-danger"><i class="fa fa-times" id="margin-right"></i>Disapprove</button>
                </a>
            @endif
        @endif
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')

    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('internals.projects.manage', [$project->id]) }}" class="no-underline">
                <button class="btn btn-primary">Project</button>
            </a>

            <a href="{{ route('internals.projects.tasks', [$project->id]) }}" class="no-underline">
                <button class="btn btn-light">Tasks</button>
            </a>
            <br><br>

            <div id="spaced-card" class="card card-body">
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
                                            <strong>Client Name <a href="{{ route('internals.projects.edit', [$project->id]) }}"><i class="material-icons icon-16pt text-success">edit</i></a></strong>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            {{ $project->client->name }}
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <strong>Has USD <a href="#" data-toggle="modal" data-target="#has-usd-{{ $project->id }}"><i class="material-icons icon-16pt text-success">edit</i></a></strong>
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
                                            <strong>Company <a href="{{ route('internals.projects.edit', [$project->id]) }}"><i class="material-icons icon-16pt text-success">edit</i></a></strong>
                                        </div>
                                    </div>
                                    <div class="col">
                                        {{ $project->company->name }}
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <strong>Project Name <a href="{{ route('internals.projects.edit', [$project->id]) }}"><i class="material-icons icon-16pt text-success">edit</i></a></strong>
                                        </div>
                                    </div>
                                    <div class="col">
                                        {{ $project->name }}
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <strong>Start Date <a href="#" data-toggle="modal" data-target="#start-date-{{ $project->id }}"><i class="material-icons icon-16pt text-success">edit</i></a></strong>
                                        </div>
                                    </div>
                                    <div class="col">
                                        @if ($project->start_date)
                                            {{ Carbon::parse($project->start_date)->format('M d Y') }}
                                        @endif
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <strong>End Date <a href="#" data-toggle="modal" data-target="#end-date-{{ $project->id }}"><i class="material-icons icon-16pt text-success">edit</i></a></strong>
                                        </div>
                                    </div>
                                    <div class="col">
                                        @if ($project->end_date)
                                            {{ Carbon::parse($project->end_date)->format('M d Y') }}
                                        @endif
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <strong>Project Duration <a href="#" data-toggle="modal" data-target="#duration-date-{{ $project->id }}"><i class="material-icons icon-16pt text-success">edit</i></a></strong>
                                        </div>
                                    </div>
                                    <div class="col">
                                        @if ($project->duration_date)
                                            {{-- Carbon::parse($project->duration_date)->format('M d Y') --}}
                                            {{ Carbon::parse($project->start_date)->format('M d Y') }} - {{ Carbon::parse($project->end_date)->format('M d Y') }}
                                        @endif
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

                    <div class="col-md-2">
                        <!-- <a href="#" data-toggle="modal" data-target="#add-project-details-{{ $project->id }}">
                            <button type="button" class="btn btn-success form-control" id="table-letter-margin"><i class="material-icons">add</i> Add Details</button>
                        </a> -->
                        
                        <a href="{{ route('internals.projects.details.add', [$project->id]) }}">
                            <button type="button" class="btn btn-success form-control" id="table-letter-margin"><i class="material-icons">add</i> Add Details</button>
                        </a>
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
                                <th id="compact-table">Description</th>
                                <th id="compact-table">Internal Price</th>
                                @if ($project->has_usd == 1)
                                    <th id="compact-table">Unit Price (USD)</th>
                                @endif
                                <th id="compact-table">Unit Price</th>
                                <th id="compact-table">Internal Total Price</th>
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
                                        <td>
                                            @if ($pjd->sub_category)
                                                <strong>{{ $pjd->sub_category->name }}</strong>
                                            @endif
                                        </td>
                                        <td id="compact-table">
                                            <strong>
                                                <!-- @if ($pjd->status == ProjectDetailStatus::FOR_APPROVAL)
                                                    <div class="badge badge-warning">For Approval</div>
                                                @elseif ($pjd->status == ProjectDetailStatus::APPROVED)
                                                    <div class="badge badge-success">Approved</div>
                                                @elseif ($pjd->status == ProjectDetailStatus::DISAPPROVED)
                                                    <div class="badge badge-danger">Disapproved</div>
                                                @endif
                                                <br> -->

                                                {{ $pjd->name }} 

                                                @if ($pjd->status == ProjectDetailStatus::FOR_APPROVAL || $pjd->status == ProjectDetailStatus::APPROVED)
                                                    <a href="{{ route('internals.projects.details.edit', [$pjd->id]) }}"><i class="material-icons icon-16pt text-success">edit</i></a>
                                                @endif
                                            </strong>
                                            <div class="d-flex">
                                                @if ($pjd->status == ProjectDetailStatus::FOR_APPROVAL)
                                                    <a href="#" data-href="{{ route('internals.projects.details.approve', [$pjd->id]) }}" data-toggle="modal" data-target="#confirm-action" id="margin-right">Approve</a> | 

                                                    <a href="#" data-href="{{ route('internals.projects.details.disapprove', [$pjd->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Disapprove</a>
                                                @endif

                                                @if ($pjd->status == ProjectDetailStatus::APPROVED)
                                                    @if (auth()->user()->id == $pjd->created_by_user_id || auth()->user()->role == 'Super Admin' || auth()->user()->role == 'Admin')
                                                        <a href="#" data-href="{{ route('internals.projects.details.delete', [$pjd->id]) }}" data-toggle="modal" data-target="#confirm-action">Delete</a>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $pjd->qty }}</td>
                                        <td id="compact-table">{!! $pjd->description !!}</td>
                                        <td>P{{ number_format($pjd->internal_price, 2) }}</td>
                                        @if ($project->has_usd == 1)
                                            <td>${{ number_format($pjd->usd_price, 2) }}</td>
                                        @endif
                                        <td>P{{ number_format($pjd->price, 2) }}</td>
                                        <td>P{{ number_format($pjd->internal_total, 2) }}</td>
                                        <td>P{{ number_format($pjd->total, 2) }}</td>
                                    </tr>
                                @endforeach
                            @endforeach

                            <tr>
                                <td colspan="5">&nbsp;</td>
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
                                <td colspan="3">&nbsp;</td>
                                @if ($project->has_usd == 0)
                                    <td>&nbsp;</td>
                                @endif
                                <td id="compact-table"><strong>ASF Rate (%)</strong></td>
                                <td id="compact-table">
                                    <a href="#" data-toggle="modal" data-target="#margin-{{ $project->id }}">
                                        {{ $project->margin }}%
                                    </a>
                                </td>
                                @if ($project->has_usd == 1)
                                    <td id="compact-table"><strong>ASF (USD)</strong></td>
                                    <td id="compact-table">${{ number_format($project->usd_asf, 2) }}</td>
                                @endif
                                <td id="compact-table"><strong>ASF</strong></td>
                                <td id="compact-table">
                                    <a href="#" data-toggle="modal" data-target="#asf-{{ $project->id }}">
                                        P{{ number_format($project->asf, 2) }}
                                    </a>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="3">&nbsp;</td>
                                @if ($project->has_usd == 0)
                                    <td colspan="">&nbsp;</td>
                                @endif

                                <td id="compact-table"><strong>VAT Rate (%)</strong></td>
                                <td id="compact-table">
                                    <a href="#" data-toggle="modal" data-target="#vat-rate-{{ $project->id }}">
                                        {{ $project->vat_rate }}%
                                    </a>
                                </td>

                                @if ($project->has_usd == 1)
                                    <td id="compact-table"><strong>VAT (USD)</strong></td>
                                    <td id="compact-table">${{ number_format($project->usd_vat, 2) }}</td>
                                @endif
                                <td id="compact-table"><strong>VAT</strong></td>
                                <td id="compact-table">
                                    <a href="#" data-toggle="modal" data-target="#vat-{{ $project->id }}">
                                        P{{ number_format($project->vat, 2) }}
                                    </a>
                                </td>
                            </tr>

                            <tr>
                                @if ($project->has_usd == 1)
                                    <td colspan="3">&nbsp;</td>
                                @else
                                    <td colspan="5">&nbsp;</td>
                                @endif

                                @if ($project->has_usd == 1)
                                    <td id="compact-table"><strong>USD Rate to PHP</strong></td>
                                    <td id="compact-table">
                                        <a href="#" data-toggle="modal" data-target="#usd-rate-{{ $project->id }}">
                                            P{{ number_format($project->usd_rate, 2) }}
                                        </a>
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
                                    <td colspan="7">&nbsp;</td>
                                @else
                                    <td colspan="6">&nbsp;</td>
                                @endif
                                <td id="compact-table"><strong>Internal CE Grand Total</strong></td>
                                <td id="compact-table">P{{ number_format($internal_grand_total, 2) }}</td>
                            </tr>

                            <tr>
                                @if ($project->has_usd == 1)
                                    <td colspan="7">&nbsp;</td>
                                @else
                                    <td colspan="6">&nbsp;</td>
                                @endif
                                <td id="compact-table"><strong>Project Margin</strong></td>
                                <td id="compact-table">P{{ number_format($project_margin, 2) }}</td>
                            </tr>

                            <!-- <tr>
                                <td colspan="7">&nbsp;</td>
                                <td id="compact-table"><strong>Profit</strong></td>
                                <td id="compact-table">P{{ number_format($grand_total - $internal_grand_total, 2) }}</td>
                            </tr> -->
                        </tbody>
                    </table>

                    @if (count($project_details) <= 0)
                        <div style="padding: 20px">
                            <center><i class="material-icons icon-16pt mr-1 text-muted">assignment</i> No record/s found</center>
                        </div>
                    @endif
                </div>
                <br><br>
                <strong>Terms and Conditions 
                    <a href="#" data-toggle="modal" data-target="#terms-{{ $project->id }}">
                        <i class="material-icons icon-16pt text-success">edit</i>
                    </a>
                </strong>
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
                                  <br><img src="{{ url($project->prepared_by_user->signature) }}" width="80px" height="60px"><br>
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



            @if (count($budget_request_forms) > 0)
                <br>
                <div id="spaced-card" class="card card-body">
                    <div class="row">
                        <div class="col">
                            <h4 class="card-header__title flex m-0">Budget Request Forms</h4>
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
                                    <th id="compact-table">Status</th>
                                </tr>
                            </thead>
                            <tbody class="list" id="companies">
                                @foreach ($budget_request_forms as $budget_request_form)
                                    <tr>
                                        <td>
                                            {{ $budget_request_form->reference_number }}
                                            <div class="d-flex">
                                                @if ($budget_request_form->status == BudgetRequestFormStatus::FOR_APPROVAL)
                                                    <a href="{{ route('internals.brf.view', [$budget_request_form->reference_number]) }}" id="margin-right">View</a> | 

                                                    <a href="{{ route('internals.brf.manage', [$budget_request_form->id]) }}" id="space-table">Manage</a> | 

                                                    <a href="#" data-href="{{ route('internals.brf.approve', [$budget_request_form->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Approve</a> | 

                                                    <a href="#" data-href="{{ route('internals.brf.disapprove', [$budget_request_form->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Disapprove</a>
                                                @endif
                                            </div>
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
                                        <td>
                                            @if ($budget_request_form->status == BudgetRequestFormStatus::FOR_APPROVAL)
                                                <div class="badge badge-warning ml-2">For Approval</div>
                                            @elseif ($budget_request_form->status == BudgetRequestFormStatus::APPROVED)
                                                <div class="badge badge-success ml-2">Approved</div>
                                            @elseif ($budget_request_form->status == BudgetRequestFormStatus::DISAPPROVED)
                                                <div class="badge badge-danger ml-2">Disapproved</div>
                                            @endif
                                        </td>
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
            @endif
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
