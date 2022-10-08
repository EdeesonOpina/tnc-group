@include('layouts.auth.header')
@php
    use Carbon\Carbon;
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
        <a href="{{ route('internals.exports.projects.print.ce', [$project->id]) }}">
            <button type="button" class="btn btn-light" id="margin-right"><i class="fa fa-print" id="margin-right"></i>Print CE</button>
        </a>

        <!-- <a href="{{ route('internals.projects.done', [$project->id]) }}">
            <button type="button" class="btn btn-light" id="margin-right"><i class="fa fa-print" id="margin-right"></i>Mark As Done</button>
        </a> -->
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')

    <div class="row">
        <div class="col-md-12">
            <div id="spaced-card" class="card card-body">
                <div class="row">
                    <div class="col">
                        <div class="row">
                            <div class="col">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <h6>Client Name</h6>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            {{ $project->client->name }}
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <h6>Date</h6>
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
                                            <h6>Project Name</h6>
                                        </div>
                                    </div>
                                    <div class="col">
                                        {{ $project->name }}
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <h6>Project Duration</h6>
                                        </div>
                                    </div>
                                    <div class="col">
                                        {{ Carbon::parse($project->end_date)->format('M d Y') }}
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
                        <a href="#" data-toggle="modal" data-target="#add-project-details-{{ $project->id }}">
                            <button type="button" class="btn btn-success form-control" id="table-letter-margin"><i class="material-icons icon-16pt mr-1 text-white">add</i> Add Details</button>
                        </a>
                    </div>
                </div>
                <br>

                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table">#ID</th>
                                <th id="compact-table">Particulars</th>
                                <th id="compact-table">Quantity</th>
                                <th id="compact-table">Description</th>
                                <th id="compact-table">Internal Price</th>
                                <th id="compact-table">Unit Price</th>
                                <th id="compact-table">Total Price</th>
                                <th id="compact-table">Internal Total Price</th>
                                <th id="compact-table">Status</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                            @foreach ($project_details as $project_detail)
                                <tr>
                                    <td>{{ $project_detail->id }}</td>
                                    <td>
                                        <strong>{{ $project_detail->name }}</strong>
                                        <div class="d-flex">
                                            @if ($project_detail->status == BudgetRequestFormStatus::FOR_APPROVAL)
                                                <a href="#" data-href="{{ route('internals.projects.details.approve', [$project_detail->id]) }}" data-toggle="modal" data-target="#confirm-action" id="margin-right">Approve</a> | 

                                                <a href="#" data-href="{{ route('internals.projects.details.disapprove', [$project_detail->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Disapprove</a>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $project_detail->qty }}</td>
                                    <td>{!! $project_detail->description !!}</td>
                                    <td>P{{ number_format($project_detail->price, 2) }}</td>
                                    <td>P{{ number_format($project_detail->internal_price, 2) }}</td>
                                    <td>P{{ number_format($project_detail->total, 2) }}</td>
                                    <td>P{{ number_format($project_detail->internal_total, 2) }}</td>
                                    <td>
                                        @if ($project_detail->status == BudgetRequestFormStatus::FOR_APPROVAL)
                                            <div class="badge badge-warning ml-2">For Approval</div>
                                        @elseif ($project_detail->status == BudgetRequestFormStatus::APPROVED)
                                            <div class="badge badge-success ml-2">Approved</div>
                                        @elseif ($project_detail->status == BudgetRequestFormStatus::DISAPPROVED)
                                            <div class="badge badge-danger ml-2">Disapproved</div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="4">&nbsp;</td>
                                <td id="compact-table"><strong>Total Cost</strong></td>
                                <td id="compact-table">P{{ number_format($project->total, 2) }}</td>
                                <td>&nbsp;</td>
                            </tr>

                            <tr>
                                <td colspan="4">&nbsp;</td>
                                <td id="compact-table"><strong>ASF</strong></td>
                                <td id="compact-table">
                                    <a href="#" data-toggle="modal" data-target="#asf-{{ $project->id }}">
                                        P{{ number_format($project->asf, 2) }}
                                    </a>
                                </td>
                                <td>&nbsp;</td>
                            </tr>

                            <tr>
                                <td colspan="4">&nbsp;</td>
                                <td id="compact-table"><strong>VAT</strong></td>
                                <td id="compact-table">
                                    <a href="#" data-toggle="modal" data-target="#vat-{{ $project->id }}">
                                        P{{ number_format($project->vat, 2) }}
                                    </a>
                                </td>
                                <td>&nbsp;</td>
                            </tr>

                            <tr>
                                <td colspan="4">&nbsp;</td>
                                <td id="compact-table"><strong>Grand Total</strong></td>
                                <td id="compact-table">P{{ number_format($project->total + $project->vat + $project->asf, 2) }}</td>
                                <td>&nbsp;</td>
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

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <strong>Prepared By</strong>
                            <br><br><br><br>
                            {{ $project->prepared_by_user->firstname }} {{ $project->prepared_by_user->lastname }}<br>
                            {{ $project->prepared_by_user->position }}<br>
                            {{ $project->prepared_by_user->company->name }}<br>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <strong>Noted By</strong>
                            <br><br><br><br>
                            {{ $project->noted_by_user->firstname }} {{ $project->noted_by_user->lastname }}<br>
                            {{ $project->noted_by_user->position }}<br>
                            {{ $project->noted_by_user->company->name }}<br>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <strong>Conforme</strong>
                            <br><br><br><br>
                            {{ $project->conforme_by_user->person }}<br>
                            {{ $project->conforme_by_user->position }}<br>
                            {{ $project->conforme_by_user->name }}<br>
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
                                                <a href="{{ route('internals.brf.view', [$budget_request_form->id]) }}" id="margin-right">View</a> | 

                                                <a href="{{ route('internals.brf.manage', [$budget_request_form->id]) }}" id="space-table">Manage</a> | 

                                                <a href="#" data-href="{{ route('internals.brf.approve', [$budget_request_form->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Approve</a> | 

                                                <a href="#" data-href="{{ route('internals.brf.disapprove', [$budget_request_form->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Disapprove</a>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        {{ $budget_request_form->payment_for_user->firstname }} {{ $budget_request_form->payment_for_user->lastname }}
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