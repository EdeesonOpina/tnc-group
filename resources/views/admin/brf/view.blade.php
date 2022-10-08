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
                    <li class="breadcrumb-item"><a href="{{ route('internals.brf') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item">BRF</li>
                    <li class="breadcrumb-item active" aria-current="page">Manage BRF</li>
                </ol>
            </nav>
            <h1 class="m-0">Manage BRF</h1>
        </div>
        
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
                            <strong>Pay To</strong>
                        </div>
                    </div>
                    <div class="col">
                        {{ $budget_request_form->payment_for_user->firstname }} {{ $budget_request_form->payment_for_user->lastname }}
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
                        {{ $budget_request_form->project->prepared_by_user->company->name }}
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <strong>Needed Date</strong>
                        </div>
                    </div>
                    <div class="col">
                        {{ $budget_request_form->needed_date }}
                    </div>
                </div>

                <hr>

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
                                <th id="compact-table">#ID</th>
                                <th id="compact-table">Particulars</th>
                                <th id="compact-table">Quantity</th>
                                <th id="compact-table">Unit Price</th>
                                <th id="compact-table">Total Price</th>
                                <th id="compact-table">Status</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                            @foreach ($budget_request_form_details as $budget_request_form_detail)
                                <tr>
                                    <td>{{ $budget_request_form_detail->id }}</td>
                                    <td>
                                        {{ $budget_request_form_detail->name }}
                                    </td>
                                    <td>{{ $budget_request_form_detail->qty }}</td>
                                    <td>P{{ number_format($budget_request_form_detail->price, 2) }}</td>
                                    <td>P{{ number_format($budget_request_form_detail->total, 2) }}</td>
                                    <td>
                                        @if ($budget_request_form_detail->status == BudgetRequestFormStatus::FOR_APPROVAL)
                                            <div class="badge badge-warning ml-2">For Approval</div>
                                        @elseif ($budget_request_form_detail->status == BudgetRequestFormStatus::APPROVED)
                                            <div class="badge badge-success ml-2">Approved</div>
                                        @elseif ($budget_request_form_detail->status == BudgetRequestFormStatus::DISAPPROVED)
                                            <div class="badge badge-danger ml-2">Disapproved</div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr> 
                                <td colspan="3">&nbsp;</td>
                                <td id="compact-table"><strong>Total Cost</strong></th>
                                <td id="compact-table">P{{ number_format($budget_request_form_details_total, 2) }}</th>
                                <td>&nbsp;</td>
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