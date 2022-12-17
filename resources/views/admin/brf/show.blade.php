@include('layouts.auth.header')
@php
use Carbon\Carbon;
use App\Models\CheckVoucher;
use App\Models\CheckVoucherStatus;
use App\Models\BudgetRequestForm;
use App\Models\BudgetRequestFormStatus;
use App\Models\BudgetRequestFormDetail;
use App\Models\BudgetRequestFormDetailStatus;
@endphp

<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">BRF</li>
                </ol>
            </nav>
            <h1 class="m-0">BRF</h1>
        </div>
        <a href="{{ route('internals.brf.users.add') }}" class="btn btn-primary" id="margin-right"><i class="material-icons">add</i> Add User Payment</a>
        <a href="{{ route('internals.brf.suppliers.add') }}" class="btn btn-primary"><i class="material-icons">add</i> Add Supplier Payment</a>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')

    <form action="{{ route('internals.brf.search') }}" method="post">
        {{ csrf_field() }}
        <div class="card card-form d-flex flex-column flex-sm-row">
            <div class="card-form__body card-body-form-group flex">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>BRF #</label>
                            <input name="reference_number" type="text" class="form-control" placeholder="Search by reference number" value="{{ old('reference_number') }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Name</label>
                            <input name="name" type="text" class="form-control" placeholder="Search by name" value="{{ old('name') }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" data-toggle="select">
                                @if (old('status'))
                                    @if (old('status') != '*')
                                        @if (old('status') == BudgetRequestFormStatus::FOR_APPROVAL)
                                            <option value="{{ old('status') }}">Active</option>
                                        @endif

                                        @if (old('status') == BudgetRequestFormStatus::INACTIVE)
                                            <option value="{{ old('status') }}">Inactive</option>
                                        @endif
                                    @endif
                                @endif
                                <option value="*">All</option>
                                <option value="{{ BudgetRequestFormStatus::FOR_APPROVAL }}">For Approval</option>
                                <option value="{{ BudgetRequestFormStatus::FOR_FINAL_APPROVAL }}">For Final Approval</option>
                                <option value="{{ BudgetRequestFormStatus::INACTIVE }}">Inactive</option>
                                <option value="{{ BudgetRequestFormStatus::APPROVED }}">Approved</option>
                                <option value="{{ BudgetRequestFormStatus::DISAPPROVED }}">Disapproved</option>
                                <option value="{{ BudgetRequestFormStatus::FOR_RELEASE }}">For Release</option>
                                <option value="{{ BudgetRequestFormStatus::RELEASED }}">Released</option>
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Needed Date</label>
                            <input name="from_date" type="needed_date" class="form-control" data-toggle="flatpickr" max="{{ date('Y-m-d') }}" value="{{ old('needed_date') }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label><a href="{{ route('internals.brf') }}" id="no-underline">Clear Filters</a></label>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn bg-white border-left border-top border-top-sm-0 rounded-top-0 rounded-top-sm rounded-left-sm-0"><i class="material-icons text-primary icon-20pt">search</i></button>
        </div>
    </form>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h4 class="card-header__title flex m-0">BRF</h4>
                    <div>
                        @if (auth()->user()->role == 'Super Admin' || auth()->user()->role == 'Admin')
                            <a href="{{ route('internals.brf.database.update') }}">
                                <button class="btn btn-light"><i class="fa fa-database" id="margin-right"></i>Update Database</button>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table">BRF#</th>
                                <th id="compact-table">Pay To</th>
                                <th id="compact-table">Payment For</th>
                                <th id="compact-table">Project</th>
                                <th id="compact-table">Needed Date</th>
                                <th id="compact-table">Total Price</th>
                                <th id="compact-table">Status</th>
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
                                    <td>
                                        <strong>{{ $budget_request_form->reference_number }}</strong>
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
                                                        <a href="#" data-href="{{ route('internals.cv.create', [$budget_request_form->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Create CV</a>
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
                                    <td id="compact-table">{{ $budget_request_form->name }}</td>
                                    <td id="compact-table">{{ $budget_request_form->project->name }}</td>
                                    <td id="compact-table"><i class="material-icons icon-16pt text-muted mr-1">today</i> {{ Carbon::parse($budget_request_form->needed_date)->format('M d Y') }}</td>
                                    <td>P{{ number_format($total, 2) }}</td>
                                    <td>
                                        @if ($budget_request_form->status == BudgetRequestFormStatus::FOR_APPROVAL)
                                            <div class="badge badge-info ml-2">For Approval</div>
                                        @elseif ($budget_request_form->status == BudgetRequestFormStatus::FOR_FINAL_APPROVAL)
                                            <div class="badge badge-info ml-2">For Final Approval</div>
                                        @elseif ($budget_request_form->status == BudgetRequestFormStatus::FOR_RELEASE)
                                            <div class="badge badge-info ml-2">For Release</div>
                                        @elseif ($budget_request_form->status == BudgetRequestFormStatus::RELEASED)
                                            <div class="badge badge-success ml-2">Released</div>
                                        @elseif ($budget_request_form->status == BudgetRequestFormStatus::ON_PROCESS)
                                            <div class="badge badge-warning ml-2">On Process</div>
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
            {{ $budget_request_forms->links() }}
        </div>
    </div>
</div>


@include('layouts.auth.footer')