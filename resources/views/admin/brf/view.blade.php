@include('layouts.auth.header')
@php
    use Carbon\Carbon;
    use App\Models\LiquidationStatus;
    use App\Models\ProjectDetailStatus;
    use App\Models\BudgetRequestFormStatus;
    use App\Models\BudgetRequestFormFile;
    use App\Models\BudgetRequestFormFileStatus;
@endphp

<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('internals.brf') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item">BRF</li>
                    <li class="breadcrumb-item active" aria-current="page">Manage BRF</li>
                </ol>
            </nav>
            <h1 class="m-0">View BRF</h1>
        </div>
        @if ($budget_request_form->status == BudgetRequestFormStatus::APPROVED || $budget_request_form->status == BudgetRequestFormStatus::FOR_RELEASE || $budget_request_form->status == BudgetRequestFormStatus::RELEASED || $budget_request_form->status == BudgetRequestFormStatus::FOR_LIQUIDATION || $budget_request_form->status == BudgetRequestFormStatus::FOR_OFFICIAL_RECEIPT || $budget_request_form->status == BudgetRequestFormStatus::FOR_BANK_DEPOSIT_SLIP || $budget_request_form->status == BudgetRequestFormStatus::FOR_LIQUIDATION_BANK_DEPOSIT_SLIP || $budget_request_form->status == BudgetRequestFormStatus::DONE)
            <a href="{{ route('internals.brf.send-to-finance', [$budget_request_form->reference_number]) }}">
                <button type="button" class="btn btn-success" id="margin-right"><i class="fa fa-envelope" id="margin-right"></i>Send To Finance</button>
            </a>

            <a href="{{ route('internals.exports.brf.print', [$budget_request_form->reference_number]) }}">
                <button type="button" class="btn btn-light" id="margin-right"><i class="fa fa-print" id="margin-right"></i>Print</button>
            </a>
        @endif
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')

    <div class="row">
        <div class="col-md-12">

            <div id="spaced-card" class="card card-body">

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <strong>BRF #</strong>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="form-group">
                            {{ $budget_request_form->reference_number }}
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <strong>Date</strong>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            {{ $budget_request_form->created_at->format('M d Y') }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <strong>CE #</strong>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="form-group">
                            <a href="{{ route('internals.projects.view', [$budget_request_form->project->reference_number]) }}">
                                {{ $budget_request_form->project->reference_number }}
                            </a>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <strong>Status</strong>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            @if ($budget_request_form->status == BudgetRequestFormStatus::FOR_APPROVAL)
                                <div class="badge badge-info">FOR APPROVAL</div>
                            @elseif ($budget_request_form->status == BudgetRequestFormStatus::FOR_FINAL_APPROVAL)
                                <div class="badge badge-info">FOR FINAL APPROVAL</div>
                            @elseif ($budget_request_form->status == BudgetRequestFormStatus::ON_PROCESS)
                                <div class="badge badge-warning">ON PROCESS</div>
                            @elseif ($budget_request_form->status == BudgetRequestFormStatus::APPROVED)
                                <div class="badge badge-success">APPROVED</div>
                            @elseif ($budget_request_form->status == BudgetRequestFormStatus::DISAPPROVED)
                                <div class="badge badge-danger">DISAPPROVED</div>
                            @elseif ($budget_request_form->status == BudgetRequestFormStatus::FOR_RELEASE)
                                <div class="badge badge-info">FOR RELEASE</div>
                            @elseif ($budget_request_form->status == BudgetRequestFormStatus::RELEASED)
                                <div class="badge badge-success">RELEASED</div>
                            @elseif ($budget_request_form->status == BudgetRequestFormStatus::FOR_LIQUIDATION)
                                <div class="badge badge-success">FOR LIQUIDATION</div>
                            @elseif ($budget_request_form->status == BudgetRequestFormStatus::FOR_BANK_DEPOSIT_SLIP)
                                <div class="badge badge-success">FOR BANK DEPOSIT SLIP</div>
                            @elseif ($budget_request_form->status == BudgetRequestFormStatus::FOR_LIQUIDATION_BANK_DEPOSIT_SLIP)
                                <div class="badge badge-success">FOR LIQUIDATION BANK DEPOSIT SLIP</div>
                            @elseif ($budget_request_form->status == BudgetRequestFormStatus::FOR_OFFICIAL_RECEIPT)
                                <div class="badge badge-success">FOR OFFICIAL RECEIPT</div>
                            @elseif ($budget_request_form->status == BudgetRequestFormStatus::DONE)
                                <div class="badge badge-success">DONE</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <strong>Pay To</strong>
                        </div>
                    </div>
                    <div class="col-md-7">
                        @if ($budget_request_form->payment_for_user)
                            {{ $budget_request_form->payment_for_user->firstname }} {{ $budget_request_form->payment_for_user->lastname }}
                        @endif

                        @if ($budget_request_form->payment_for_supplier)
                            {{ $budget_request_form->payment_for_supplier->name }}
                        @endif
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <strong>Release File 
                                @if (BudgetRequestFormFile::where('budget_request_form_id', $budget_request_form->id)
                                ->where('status', BudgetRequestFormFileStatus::ACTIVE)
                                ->exists())
                                @php
                                    $brf_file = BudgetRequestFormFile::where('budget_request_form_id', $budget_request_form->id)
                                                    ->where('status', BudgetRequestFormFileStatus::ACTIVE)
                                                    ->first();
                                @endphp
                                    <a href="#" data-href="{{ route('internals.brf.file.delete', [$brf_file->id]) }}" data-toggle="modal" data-target="#confirm-action"><i class="material-icons icon-16pt text-danger">delete</i></a>
                                @endif
                            </strong>
                        </div>
                    </div>
                    <div class="col">
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
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <strong>In Payment For</strong>
                        </div>
                    </div>
                    <div class="col">
                        {{ $budget_request_form->name }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <strong>Project</strong>
                        </div>
                    </div>
                    <div class="col">
                        {{ $budget_request_form->project->name }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <strong>Get Budget From</strong>
                        </div>
                    </div>
                    <div class="col">
                        {{ $budget_request_form->project->company->name }}
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <strong>Needed Date</strong>
                        </div>
                    </div>
                    <div class="col">
                        {{ Carbon::parse($budget_request_form->needed_date)->format('M d Y') }}
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col">
                        <h4 class="card-header__title flex m-0">BRF Details</h4>
                    </div>
                </div>
                <br>

                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table">Particulars</th>
                                <th id="compact-table">Description</th>
                                <th id="compact-table">A. File</th>
                                <th id="compact-table">Quantity</th>
                                <th id="compact-table">Unit Price</th>
                                <th id="compact-table">Total Price</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                            @foreach ($budget_request_form_details as $budget_request_form_detail)
                                <tr>
                                    <td>
                                        <strong>{{ $budget_request_form_detail->name }}</strong>
                                        <!-- @if ($budget_request_form_detail->status == BudgetRequestFormStatus::FOR_APPROVAL)
                                            <div class="badge badge-warning">For Approval</div>
                                        @elseif ($budget_request_form_detail->status == BudgetRequestFormStatus::APPROVED)
                                            <div class="badge badge-success">Approved</div>
                                        @elseif ($budget_request_form_detail->status == BudgetRequestFormStatus::DISAPPROVED)
                                            <div class="badge badge-danger">Disapproved</div>
                                        @endif -->
                                    </td>
                                    <td>{{ $budget_request_form_detail->description }}</td>
                                    <td id="compact-table">
                                        @if ($budget_request_form_detail->file)
                                            <a href="{{ url($budget_request_form_detail->file) }}" download>
                                                <button class="btn btn-sm btn-primary">Download File</button>
                                            </a>
                                        @endif
                                    </td>
                                    <td>{{ $budget_request_form_detail->qty }}</td>
                                    <td>P{{ number_format($budget_request_form_detail->price, 2) }}</td>
                                    <td>P{{ number_format($budget_request_form_detail->total, 2) }}</td>
                                </tr>
                            @endforeach
                            <tr> 
                                <td colspan="4">&nbsp;</td>
                                <td id="compact-table"><strong>Total Cost</strong></td>
                                <td id="compact-table">P{{ number_format($budget_request_form_details_total, 2) }}</td>
                            </tr>

                            <!-- <tr> 
                                <td colspan="3">&nbsp;</td>
                                <td id="compact-table"><strong>Project Balance</strong></th>
                                <td id="compact-table">P{{ number_format($budget_request_form->project->total - $budget_request_form_details_total, 2) }}</th>
                                <td>&nbsp;</td>
                            </tr> -->
                        </tbody>
                    </table>

                    @if (count($budget_request_form_details) <= 0)
                        <div style="padding: 20px">
                            <center><i class="material-icons icon-16pt mr-1 text-muted">assignment</i> No record/s found</center>
                        </div>
                    @endif
                </div>

                <br><br>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <strong>Requested By</strong>
                            @if ($budget_request_form->checked_by_user->signature)
                                  <br><img src="{{ url($budget_request_form->requested_by_user->signature) }}" width="80px" height="60px"><br>
                            @else
                                <br><br><br><br>
                            @endif
                            <strong>{{ $budget_request_form->requested_by_user->firstname }} {{ $budget_request_form->requested_by_user->lastname }}</strong><br>
                            {{ $budget_request_form->requested_by_user->position }}<br>
                            {{ $budget_request_form->requested_by_user->company->name }}<br>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <strong>Checked By</strong>
                            @if ($budget_request_form->status == BudgetRequestFormStatus::APPROVED || $budget_request_form->status == BudgetRequestFormStatus::FOR_FINAL_APPROVAL || $budget_request_form->status == BudgetRequestFormStatus::DONE || $budget_request_form->status == BudgetRequestFormStatus::FOR_APPROVAL || $budget_request_form->status == BudgetRequestFormStatus::FOR_FINAL_APPROVAL || $budget_request_form->status == BudgetRequestFormStatus::FOR_LIQUIDATION || $budget_request_form->status == BudgetRequestFormStatus::FOR_OFFICIAL_RECEIPT || $budget_request_form->status == BudgetRequestFormStatus::FOR_BANK_DEPOSIT_SLIP || $budget_request_form->status == BudgetRequestFormStatus::FOR_LIQUIDATION_BANK_DEPOSIT_SLIP || $budget_request_form->status == BudgetRequestFormStatus::RELEASED || $budget_request_form->status == BudgetRequestFormStatus::FOR_RELEASE)
                                @if ($budget_request_form->checked_by_user->signature)
                                      <br><img src="{{ url($budget_request_form->checked_by_user->signature) }}" width="80px" height="60px"><br>
                                @else
                                    <br><br><br><br>
                                @endif
                            @else
                                <br><br><br><br>
                            @endif
                            <strong>{{ $budget_request_form->checked_by_user->firstname }} {{ $budget_request_form->checked_by_user->lastname }}</strong><br>
                            {{ $budget_request_form->checked_by_user->position }}<br>
                            {{ $budget_request_form->checked_by_user->company->name }}<br>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <strong>Noted By</strong>
                            @if ($budget_request_form->status == BudgetRequestFormStatus::DONE || $budget_request_form->status == BudgetRequestFormStatus::FOR_LIQUIDATION || $budget_request_form->status == BudgetRequestFormStatus::FOR_OFFICIAL_RECEIPT || $budget_request_form->status == BudgetRequestFormStatus::FOR_BANK_DEPOSIT_SLIP || $budget_request_form->status == BudgetRequestFormStatus::FOR_LIQUIDATION_BANK_DEPOSIT_SLIP || $budget_request_form->status == BudgetRequestFormStatus::RELEASED || $budget_request_form->status == BudgetRequestFormStatus::FOR_RELEASE)
                                @if ($budget_request_form->noted_by_user->signature || $budget_request_form->status == BudgetRequestFormStatus::FOR_APPROVAL || $budget_request_form->status == BudgetRequestFormStatus::FOR_FINAL_APPROVAL)
                                      <br><img src="{{ url($budget_request_form->noted_by_user->signature) }}" width="80px" height="60px"><br>
                                @else
                                    <br><br><br><br>
                                @endif
                            @else
                                <br><br><br><br>
                            @endif
                            <strong>{{ $budget_request_form->noted_by_user->firstname }} {{ $budget_request_form->noted_by_user->lastname }}</strong><br>
                            {{ $budget_request_form->noted_by_user->position }}<br>
                            {{ $budget_request_form->noted_by_user->company->name }}<br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.auth.footer')