@include('layouts.auth.header')
@php
use Carbon\Carbon;
use App\Models\CheckVoucherStatus;
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
                                            <a href="{{ route('internals.exports.cv.print', [$cv->reference_number]) }}" id="margin-right">Print</a>
                                        </div>
                                    </td>
                                    <td id="compact-table">
                                        @if ($cv->budget_request_form->payment_for_user)
                                            {{ $cv->budget_request_form->payment_for_user->firstname }} {{ $cv->budget_request_form->payment_for_user->lastname }}
                                        @endif

                                        @if ($cv->budget_request_form->payment_for_supplier)
                                            {{ $cv->budget_request_form->payment_for_supplier->name }}
                                        @endif
                                    </td>
                                    <td id="compact-table">{{ $cv->budget_request_form->name }}</td>
                                    <td id="compact-table">{{ $cv->budget_request_form->project->name }}</td>
                                    <td id="compact-table"><i class="material-icons icon-16pt text-muted mr-1">today</i> {{ Carbon::parse($cv->budget_request_form->needed_date)->format('M d Y') }}</td>
                                    <td>P{{ number_format($cv->budget_request_form->total, 2) }}</td>
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