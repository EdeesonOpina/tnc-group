@include('layouts.auth.header')
@php
    use Carbon\Carbon;
    use App\Models\LiquidationStatus;
    use App\Models\ProjectDetailStatus;
    use App\Models\BudgetRequestFormStatus;
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
        <a href="{{ route('internals.exports.brf.print', [$budget_request_form->reference_number]) }}">
            <button type="button" class="btn btn-light" id="margin-right"><i class="fa fa-print" id="margin-right"></i>Print</button>
        </a>
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
                    <div class="col">
                        <div class="form-group">
                            <a href="{{ route('internals.projects.view', [$budget_request_form->project->reference_number]) }}">
                                <strong>{{ $budget_request_form->project->reference_number }}</strong>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <strong>Pay To</strong>
                        </div>
                    </div>
                    <div class="col">
                        @if ($budget_request_form->payment_for_user)
                            {{ $budget_request_form->payment_for_user->firstname }} {{ $budget_request_form->payment_for_user->lastname }}
                        @endif

                        @if ($budget_request_form->payment_for_supplier)
                            {{ $budget_request_form->payment_for_supplier->name }}
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
                                        @if ($budget_request_form_detail->status == BudgetRequestFormStatus::FOR_APPROVAL)
                                            <div class="badge badge-warning">For Approval</div>
                                        @elseif ($budget_request_form_detail->status == BudgetRequestFormStatus::APPROVED)
                                            <div class="badge badge-success">Approved</div>
                                        @elseif ($budget_request_form_detail->status == BudgetRequestFormStatus::DISAPPROVED)
                                            <div class="badge badge-danger">Disapproved</div>
                                        @endif
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
                                <td id="compact-table"><strong>Total Cost</strong></th>
                                <td id="compact-table">P{{ number_format($budget_request_form_details_total, 2) }}</th>
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
                            @if ($budget_request_form->status == BudgetRequestFormStatus::APPROVED || $budget_request_form->status == BudgetRequestFormStatus::FOR_FINAL_APPROVAL || $budget_request_form->status == BudgetRequestFormStatus::DONE)
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
                            @if ($budget_request_form->status == BudgetRequestFormStatus::APPROVED || $budget_request_form->status == BudgetRequestFormStatus::DONE)
                                @if ($budget_request_form->noted_by_user->signature)
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

            <br>

            <!-- <div id="spaced-card" class="card card-body">
                <div class="row">
                    <div class="col">
                        <h4 class="card-header__title flex m-0">Liquidations</h4>
                    </div>

                    <div class="col-md-2">
                        
                    </div>
                </div>
                <br>

                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table">Particulars</th>
                                <th id="compact-table">Category</th>
                                <th id="compact-table">Particulars</th>
                                <th id="compact-table">Description</th>
                                <th id="compact-table">Cost</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                            @foreach($liquidations as $liquidation)
                                <tr>
                                    <td id="compact-table">
                                        <strong>{{ $liquidation->budget_request_form->name }}</strong>
                                        @if ($liquidation->status == LiquidationStatus::APPROVED)
                                            <div class="badge badge-success">Approved</div>
                                        @elseif ($liquidation->status == LiquidationStatus::DISAPPROVED)
                                            <div class="badge badge-danger">Disapproved</div>
                                        @elseif ($liquidation->status == LiquidationStatus::FOR_APPROVAL)
                                            <div class="badge badge-warning">For Approval</div>
                                        @elseif ($liquidation->status == LiquidationStatus::INACTIVE)
                                            <div class="badge badge-danger">Inactive</div>
                                        @endif
                                    </td>
                                    <td id="compact-table">
                                        <b>{{ $liquidation->category->name }}</b>
                                    </td>
                                    <td id="compact-table">{{ $liquidation->name }}</td>
                                    <td id="compact-table">{{ $liquidation->description }}</td>
                                    <td id="compact-table">P{{ number_format($liquidation->cost, 2) }}</td> 
                                </tr>
                            @endforeach

                            <tr> 
                                <td colspan="3">&nbsp;</td>
                                <td id="compact-table"><strong>Total Cost</strong></th>
                                <td id="compact-table">P{{ number_format($liquidations_total, 2) }}</th>
                            </tr>
                        </tbody>
                    </table>

                    @if (count($liquidations) <= 0)
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
                            @if ($budget_request_form->status == BudgetRequestFormStatus::APPROVED || $budget_request_form->status == BudgetRequestFormStatus::FOR_FINAL_APPROVAL || $budget_request_form->status == BudgetRequestFormStatus::DONE)
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
                            @if ($budget_request_form->status == BudgetRequestFormStatus::APPROVED || $budget_request_form->status == BudgetRequestFormStatus::DONE)
                                @if ($budget_request_form->noted_by_user->signature)
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
        </div> -->
    </div>
</div>

@include('layouts.auth.footer')