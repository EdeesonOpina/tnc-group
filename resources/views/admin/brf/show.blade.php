@include('layouts.auth.header')
@php
use Carbon\Carbon;
use App\Models\BudgetRequestFormStatus;
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
        <a href="#" data-toggle="modal" data-target="#add-brf" class="btn btn-primary"><i class="material-icons">add</i> Add</a>
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
                                <option value="{{ BudgetRequestFormStatus::FOR_APPROVAL }}">Active</option>
                                <option value="{{ BudgetRequestFormStatus::INACTIVE }}">Inactive</option>
                                <option value="{{ BudgetRequestFormStatus::APPROVED }}">Approved</option>
                                <option value="{{ BudgetRequestFormStatus::DISAPPROVED }}">Disapproved</option>
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>From</label>
                            <input name="from_date" type="date" class="form-control" data-toggle="flatpickr" max="{{ date('Y-m-d') }}" value="{{ old('from_date') }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>To</label>
                            <input name="to_date" type="date" class="form-control" data-toggle="flatpickr" max="{{ date('Y-m-d') }}" value="{{ old('to_date') }}">
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
                    <div data-toggle="flatpickr" data-flatpickr-wrap="true" data-flatpickr-static="true" data-flatpickr-mode="range" data-flatpickr-alt-format="d/m/Y" data-flatpickr-date-format="d/m/Y">
                        
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table">#ID</th>
                                <th id="compact-table"></th>
                                <th id="compact-table">Project</th>
                                <th id="compact-table">Quantity</th>
                                <th id="compact-table">Description</th>
                                <th id="compact-table">Unit Price</th>
                                <th id="compact-table">Total Price</th>
                                <th id="compact-table">Status</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                            @foreach ($budget_request_forms as $budget_request_form)
                                <tr>
                                    <td>{{ $budget_request_form->id }}</td>
                                    <td>
                                        {{ $budget_request_form->name }}
                                        <div class="d-flex">
                                            @if ($budget_request_form->status == BudgetRequestFormStatus::FOR_APPROVAL)
                                                <a href="#" data-href="{{ route('internals.brf.approve', [$budget_request_form->id]) }}" data-toggle="modal" data-target="#confirm-action" id="margin-right">Approve</a> | 

                                                <a href="#" data-href="{{ route('internals.brf.disapprove', [$budget_request_form->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Disapprove</a>
                                            @endif
                                        </div>
                                    </td>
                                    <td id="compact-table">{{ $budget_request_form->project->name }}</td>
                                    <td>{{ $budget_request_form->qty }}</td>
                                    <td>{{ $budget_request_form->description }}</td>
                                    <td>P{{ number_format($budget_request_form->price, 2) }}</td>
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
            {{ $budget_request_forms->links() }}
        </div>
    </div>
</div>


@include('layouts.auth.footer')