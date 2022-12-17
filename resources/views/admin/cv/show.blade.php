@include('layouts.auth.header')
@php
use Carbon\Carbon;
use App\Models\CheckVoucherStatus;
use App\Models\BudgetRequestFormStatus;
@endphp

<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Check Vouchers</li>
                </ol>
            </nav>
            <h1 class="m-0">Check Vouchers</h1>
        </div>
        <a href="{{ route('internals.cv.custom.add') }}" class="btn btn-primary" id="margin-right"><i class="material-icons">add</i> Add Custom CV</a>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')

    <form action="{{ route('internals.cv.search') }}" method="post">
        {{ csrf_field() }}
        <div class="card card-form d-flex flex-column flex-sm-row">
            <div class="card-form__body card-body-form-group flex">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>CV #</label>
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
                                        @if (old('status') == CheckVoucherStatus::DONE)
                                            <option value="{{ old('status') }}">Done</option>
                                        @endif

                                        @if (old('status') == CheckVoucherStatus::INACTIVE)
                                            <option value="{{ old('status') }}">Inactive</option>
                                        @endif
                                    @endif
                                @endif
                                <option value="*">All</option>
                                <option value="{{ CheckVoucherStatus::DONE }}">Done</option>
                                <option value="{{ CheckVoucherStatus::INACTIVE }}">Inactive</option>
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
                        <label><a href="{{ route('internals.cv') }}" id="no-underline">Clear Filters</a></label>
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
                    <h4 class="card-header__title flex m-0">Check Vouchers</h4>
                    <div data-toggle="flatpickr" data-flatpickr-wrap="true" data-flatpickr-static="true" data-flatpickr-mode="range" data-flatpickr-alt-format="d/m/Y" data-flatpickr-date-format="d/m/Y">
                        
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table">CV#</th>
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
                            @foreach ($cvs as $cv)
                                <tr>
                                    <td id="compact-table">
                                        <strong>{{ $cv->reference_number }}</strong>
                                        <div class="d-flex">
                                            @if ($cv->status == CheckVoucherStatus::ON_PROCESS)
                                                @if ($cv->is_custom == 1)
                                                    <a href="{{ route('internals.cv.custom.manage', [$cv->id]) }}" id="margin-right">Manage</a> | 
                                                @endif
                                            @endif

                                            @if ($cv->status == CheckVoucherStatus::DONE || $cv->status == CheckVoucherStatus::ON_PROCESS)
                                                @if ($cv->is_custom == 1)
                                                    <a href="{{ route('internals.exports.cv.print.custom', [$cv->reference_number]) }}" id="space-table">Print</a>
                                                @else
                                                    <a href="{{ route('internals.exports.cv.print', [$cv->reference_number]) }}" id="space-table">Print</a>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                    <td id="compact-table">
                                        @if ($cv->budget_request_form)
                                            <strong>
                                                <a href="{{ route('internals.brf.view', [$cv->budget_request_form->reference_number]) }}">
                                                    {{ $cv->budget_request_form->reference_number }}
                                                </a>
                                            </strong>
                                            <div class="d-flex">
                                                @if ($cv->budget_request_form->status == BudgetRequestFormStatus::FOR_RELEASE)
                                                    @if (auth()->user()->role == 'Super Admin' || auth()->user()->role == 'Admin' || auth()->user()->role == 'Accountant')
                                                        <a href="#" data-href="{{ route('internals.brf.released', [$cv->budget_request_form->id]) }}" data-toggle="modal" data-target="#confirm-action">Mark As Released</a>
                                                    @endif
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td id="compact-table">
                                        @if ($cv->is_custom == 0)
                                            @if ($cv->budget_request_form->payment_for_user)
                                                {{ $cv->budget_request_form->payment_for_user->firstname }} {{ $cv->budget_request_form->payment_for_user->lastname }}
                                            @endif

                                            @if ($cv->budget_request_form->payment_for_supplier)
                                                {{ $cv->budget_request_form->payment_for_supplier->name }}
                                            @endif
                                        @else
                                            {{ $cv->pay_to }}
                                        @endif
                                    </td>
                                    <td id="compact-table">
                                        @if ($cv->is_custom == 0)
                                            {{ $cv->budget_request_form->name }}
                                        @else
                                            {{ $cv->name }}
                                        @endif
                                    </td>
                                    <td id="compact-table">
                                        @if ($cv->is_custom == 0)
                                            {{ $cv->budget_request_form->project->name }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td id="compact-table">
                                        <i class="material-icons icon-16pt text-muted mr-1">today</i> 
                                        @if ($cv->is_custom == 0)
                                            {{ Carbon::parse($cv->budget_request_form->needed_date)->format('M d Y') }}
                                        @else
                                            {{ Carbon::parse($cv->needed_date)->format('M d Y') }}
                                        @endif
                                    </td>
                                    <td>P{{ number_format($cv->total, 2) }}</td>
                                    <td>
                                        @if ($cv->status == CheckVoucherStatus::DONE)
                                            <div class="badge badge-success ml-2">Done</div>
                                        @elseif ($cv->status == CheckVoucherStatus::INACTIVE)
                                            <div class="badge badge-danger ml-2">Inactive</div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if (count($cvs) <= 0)
                        <div style="padding: 20px">
                            <center><i class="material-icons icon-16pt mr-1 text-muted">assignment</i> No record/s found</center>
                        </div>
                    @endif
                </div>
            </div>
            {{ $cvs->links() }}
        </div>
    </div>
</div>


@include('layouts.auth.footer')