@include('layouts.auth.header')
@php
use Carbon\Carbon;
use App\Models\CheckVoucher;
use App\Models\CheckVoucherStatus;
use App\Models\BudgetRequestForm;
use App\Models\BudgetRequestFormStatus;
use App\Models\BudgetRequestFormDetail;
use App\Models\BudgetRequestFormDetailStatus;
use App\Models\BudgetRequestFormFile;
use App\Models\BudgetRequestFormFileStatus;
@endphp

<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Liquidations</li>
                </ol>
            </nav>
            <h1 class="m-0">Liquidations</h1>
        </div>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')

    <form action="{{ route('accounting.liquidations.search') }}" method="post">
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
                                <!-- <option value="{{ BudgetRequestFormStatus::FOR_OFFICIAL_RECEIPT }}">For Official Receipt</option> -->
                                <option value="{{ BudgetRequestFormStatus::FOR_LIQUIDATION }}">For Liquidation</option>
                                <!-- <option value="{{ BudgetRequestFormStatus::FOR_BANK_DEPOSIT_SLIP }}">For Bank Deposit Slip</option>
                                <option value="{{ BudgetRequestFormStatus::FOR_LIQUIDATION_BANK_DEPOSIT_SLIP }}">For Liquidation Bank Deposit Slip</option> -->
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
                        <label><a href="{{ route('accounting.liquidations') }}" id="no-underline">Clear Filters</a></label>
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
                    <h4 class="card-header__title flex m-0">Liquidations</h4>
                    <div>
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
                                <th id="compact-table">File</th>
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
                                    <td id="compact-table">
                                        @if (auth()->user()->role == 'Super Admin' || auth()->user()->role == 'Admin' || auth()->user()->role == 'Accountant' || auth()->user()->id == $budget_request_form->requested_by_user->id || auth()->user()->id == $budget_request_form->checked_by_user->id || auth()->user()->id == $budget_request_form->noted_by_user->id)

                                            @if ($budget_request_form->status == BudgetRequestFormStatus::FOR_LIQUIDATION || $budget_request_form->status == BudgetRequestFormStatus::FOR_BANK_DEPOSIT_SLIP || $budget_request_form->status == BudgetRequestFormStatus::FOR_LIQUIDATION_BANK_DEPOSIT_SLIP || $budget_request_form->status == BudgetRequestFormStatus::FOR_OFFICIAL_RECEIPT || $budget_request_form->status == BudgetRequestFormStatus::DONE)

                                                @if (BudgetRequestFormFile::where('budget_request_form_id', $budget_request_form->id)
                                                ->where('status', BudgetRequestFormFileStatus::ACTIVE)
                                                ->exists())
                                                @php
                                                    $brf_file = BudgetRequestFormFile::where('budget_request_form_id', $budget_request_form->id)
                                                                    ->where('status', BudgetRequestFormFileStatus::ACTIVE)
                                                                    ->first();
                                                @endphp

                                                    <a href="{{ url($brf_file->file) }}" download>
                                                        <button class="btn btn-sm btn-primary">Download File</button>
                                                    </a>
                                                @else
                                                    <a href="#" data-toggle="modal" data-target="#release-file-{{ $budget_request_form->id }}">
                                                        <button class="btn btn-primary btn-sm">Upload File</button>
                                                    </a>
                                                @endif

                                            @endif

                                        @endif
                                    </td>
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
                                        @elseif ($budget_request_form->status == BudgetRequestFormStatus::FOR_LIQUIDATION)
                                            <div class="badge badge-success ml-2">FOR LIQUIDATION</div>
                                        @elseif ($budget_request_form->status == BudgetRequestFormStatus::FOR_OFFICIAL_RECEIPT)
                                            <div class="badge badge-success ml-2">FOR OFFICIAL RECEIPT</div>
                                        @elseif ($budget_request_form->status == BudgetRequestFormStatus::FOR_BANK_DEPOSIT_SLIP)
                                            <div class="badge badge-success ml-2">FOR BANK DEPOSIT SLIP</div>
                                        @elseif ($budget_request_form->status == BudgetRequestFormStatus::FOR_LIQUIDATION_BANK_DEPOSIT_SLIP)
                                            <div class="badge badge-success ml-2">FOR LIQUIDATION BANK DEPOSIT SLIP</div>
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