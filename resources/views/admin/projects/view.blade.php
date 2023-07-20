@include('layouts.auth.header')
@php
    use Carbon\Carbon;
    use App\Models\ProjectStatus;
    use App\Models\ProjectBudgetStatus;
    use App\Models\ProjectDetail;
    use App\Models\ProjectDetailStatus;
    use App\Models\BudgetRequestFormStatus;
    use App\Models\BudgetRequestFormDetail;
    use App\Models\BudgetRequestFormDetailStatus;
    use App\Models\CheckVoucher;
    use App\Models\CheckVoucherStatus;
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

        <a href="{{ route('internals.exports.projects.excel', [$project->id]) }}">
            <button type="button" class="btn btn-success" id="margin-right"><i class="fa fa-file-excel" id="margin-right"></i>Create Excel</button>
        </a>
        
        @if ($project->status == ProjectStatus::APPROVED)
            <a href="#" data-toggle="modal" data-target="#share-project-{{ $project->id }}">
                <button type="button" class="btn btn-success" id="margin-right"><i class="fa fa-link" id="margin-right"></i>Share CE Link</button>
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
                                    <div class="col-md-7">
                                        {{ $project->company->name }}
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <strong>Status</strong>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            @if ($project->status == ProjectStatus::FOR_APPROVAL)
                                                <div class="badge badge-info">For Approval</div>
                                            @elseif ($project->status == ProjectStatus::APPROVED)
                                                <div class="badge badge-success">Approved</div>
                                            @elseif ($project->status == ProjectStatus::DONE)
                                                <div class="badge badge-success">Done</div>
                                            @elseif ($project->status == ProjectStatus::OPEN_FOR_EDITING)
                                            <div class="badge badge-info">Open For Editing</div>
                                            @elseif ($project->status == ProjectStatus::ON_PROCESS)
                                                <div class="badge badge-warning">On Process</div>
                                            @elseif ($project->status == ProjectStatus::DISAPPROVED)
                                                <div class="badge badge-danger">Disapproved</div>
                                            @elseif ($project->status == ProjectStatus::INACTIVE)
                                                <div class="badge badge-danger">Inactive</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <strong>Project Name</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        {{ $project->name }}
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <strong>Signed CE File <a href="#" data-toggle="modal" data-target="#signed-ce-{{ $project->id }}"><i class="material-icons icon-16pt text-success">edit</i></a></strong>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            @if ($project->signed_ce)
                                                <a href="{{ url($project->signed_ce) }}" download>
                                                    <button class="btn btn-sm btn-primary">Download File</button>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <strong>Project Duration</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        {{-- Carbon::parse($project->end_date)->format('M d Y') --}}
                                        {{ Carbon::parse($project->start_date)->format('M d Y') ?? null }} - {{ Carbon::parse($project->end_date)->format('M d Y') ?? null }}
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <strong>Used Cost</strong>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            P{{ number_format($project->used_cost(), 2) }}
                                        </div>
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
                                <th>Description</th>
                                <th id="compact-table">Quantity</th>
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
                                        <td id="compact-table">{!! $pjd->description !!}</td>
                                        <td id="compact-table">{{ $pjd->qty }}</td>
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
                                <td id="compact-table">P{{ number_format($project_margin, 2) }}</td>
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
                            @php
                                $total = BudgetRequestFormDetail::where('budget_request_form_id', $budget_request_form->id)
                                                        ->where('status', '!=', BudgetRequestFormDetailStatus::INACTIVE)
                                                        ->sum('total');
                            @endphp
                                <tr>
                                    <td id="compact-table">
                                        <strong><a href="{{ route('internals.brf.view', [$budget_request_form->reference_number]) }}" id="margin-right">{{ $budget_request_form->reference_number }}</a></strong>
                                        <div class="d-flex">
                                            <a href="{{ route('internals.brf.view', [$budget_request_form->reference_number]) }}" id="margin-right">View</a> | 

                                            @if ($budget_request_form->status == BudgetRequestFormStatus::ON_PROCESS || $budget_request_form->status == BudgetRequestFormStatus::FOR_APPROVAL || $budget_request_form->status == BudgetRequestFormStatus::DISAPPROVED || $budget_request_form->status == BudgetRequestFormStatus::FOR_FINAL_APPROVAL)

                                                @if ($budget_request_form->payment_for_user)
                                                    <a href="{{ route('internals.brf.users.edit', [$budget_request_form->reference_number]) }}" id="space-table">Edit</a> | 
                                                @else
                                                    <a href="{{ route('internals.brf.suppliers.edit', [$budget_request_form->reference_number]) }}" id="space-table">Edit</a> | 
                                                @endif

                                            @endif


                                            @if ($budget_request_form->requested_by_user_id == auth()->user()->id || auth()->user()->role == 'Super Admin' || auth()->user()->role == 'Admin')

                                                @if ($budget_request_form->status == BudgetRequestFormStatus::ON_PROCESS || $budget_request_form->status == BudgetRequestFormStatus::FOR_APPROVAL || $budget_request_form->status == BudgetRequestFormStatus::FOR_FINAL_APPROVAL || $budget_request_form->status == BudgetRequestFormStatus::DISAPPROVED)
                                                    <a href="{{ route('internals.brf.manage', [$budget_request_form->id]) }}" id="space-table">Manage</a> | 
                                                

                                                    @if (BudgetRequestFormStatus::FOR_FINAL_APPROVAL)
                                                        <a href="#" data-href="{{ route('internals.brf.approve', [$budget_request_form->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Approve</a> | 

                                                        <a href="#" data-toggle="modal" data-target="#disapprove-{{ $budget_request_form->id }}" id="space-table">Disapprove</a>
                                                    @else
                                                        <a href="#" data-href="{{ route('internals.brf.for-final-approval', [$budget_request_form->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Approve</a> | 

                                                        <a href="#" data-toggle="modal" data-target="#disapprove-{{ $budget_request_form->id }}" id="space-table">Disapprove</a>
                                                    @endif
                                                @endif
                                                
                                            @endif

                                            @if ($budget_request_form->status == BudgetRequestFormStatus::APPROVED)
                                                @if (auth()->user()->role == 'Super Admin' || auth()->user()->role == 'Admin' || auth()->user()->role == 'Accountant')
                                                    <a href="#" data-href="{{ route('internals.brf.for-release', [$budget_request_form->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">For Release</a> | 

                                                @endif
                                            @endif

                                            @if ($budget_request_form->status == BudgetRequestFormStatus::FOR_RELEASE)
                                                @if (! CheckVoucher::where('budget_request_form_id', $budget_request_form->id)->where('status', CheckVoucherStatus::DONE)->exists())
                                                    @if (auth()->user()->role == 'Super Admin' || auth()->user()->role == 'Admin' || auth()->user()->role == 'Accountant')
                                                        <a href="#" data-toggle="modal" data-target="#create-cv-{{ $budget_request_form->id }}" id="space-table">Create CV</a>
                                                    @endif
                                                @endif

                                                @if (CheckVoucher::where('budget_request_form_id', $budget_request_form->id)->where('status', CheckVoucherStatus::DONE)->exists())

                                                    @if (auth()->user()->role == 'Super Admin' || auth()->user()->role == 'Admin' || auth()->user()->role == 'Accountant')
                                                        <a href="#" data-href="{{ route('internals.brf.released', [$budget_request_form->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Mark As Released</a>
                                                    @endif

                                                @endif
                                            @endif

                                            @if ($budget_request_form->status == BudgetRequestFormStatus::RELEASED)

                                            @endif
                                        </div>
                                    </td>
                                    <td id="compact-table">
                                        @if ($budget_request_form->payment_for_user)
                                            {{ $budget_request_form->payment_for_user->firstname }} {{ $budget_request_form->payment_for_user->lastname }}
                                        @endif

                                        @if ($budget_request_form->payment_for_supplier)
                                            {{ $budget_request_form->payment_for_supplier->name }}
                                        @endif
                                    </td>
                                    <td id="compact-table">{{ $budget_request_form->project->name }}</td>
                                    <td id="compact-table"><i class="material-icons icon-16pt text-muted mr-1">today</i> {{ Carbon::parse($budget_request_form->needed_date)->format('M d Y') }}</td>
                                    <td>P{{ number_format($total, 2) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="3">&nbsp;</td>
                                <td><strong>Grand Total</strong></td>
                                <td><strong>P{{ number_format($project->used_cost(), 2) }}</strong></td>
                            </tr>
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