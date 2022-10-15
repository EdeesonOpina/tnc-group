@include('layouts.auth.header')
@php
    use Carbon\Carbon;
    use App\Models\LiquidationStatus;
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
        <a href="{{ route('accounting.liquidations.add') }}" class="btn btn-primary"><i class="material-icons">add</i> Add</a>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')
    @include('layouts.partials.top-tabs')

    <form action="{{ route('accounting.liquidations.search') }}" method="post">
        {{ csrf_field() }}
        <div class="card card-form d-flex flex-column flex-sm-row">
            <div class="card-form__body card-body-form-group flex">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>BRF#</label>
                            <input name="reference_number" type="text" class="form-control" placeholder="Search by reference number" value="{{ old('reference_number') }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" data-toggle="select">
                                @if (old('status'))
                                    @if (old('status') != '*')
                                        @if (old('status') == LiquidationStatus::FOR_APPROVAL)
                                            <option value="{{ old('status') }}">For Approval</option>
                                        @endif

                                        @if (old('status') == LiquidationStatus::INACTIVE)
                                            <option value="{{ old('status') }}">Inactive</option>
                                        @endif
                                    @endif
                                @endif
                                <option value="*">All</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
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
                    <div data-toggle="flatpickr" data-flatpickr-wrap="true" data-flatpickr-static="true" data-flatpickr-mode="range" data-flatpickr-alt-format="d/m/Y" data-flatpickr-date-format="d/m/Y">
                        
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table">BRF#</th>
                                <th id="compact-table">Category</th>
                                <th id="compact-table">Particulars</th>
                                <th id="compact-table">Description</th>
                                <th id="compact-table">Cost</th>
                                <th id="compact-table">Status</th>
                                <th id="compact-table">Date</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                            @foreach($liquidations as $liquidation)
                                <tr>
                                    <td><strong>
                                        <a href="{{ route('internals.brf.view', [$liquidation->budget_request_form->reference_number]) }}" id="margin-right">{{ $liquidation->budget_request_form->reference_number }}
                                        </a>
                                        </strong></td>
                                    <td id="compact-table">
                                        <b>{{ $liquidation->category->name }}</b>
                                        <div class="d-flex">
                                            <a href="{{ route('accounting.liquidations.edit', [$liquidation->id]) }}" id="margin-right">Edit</a> | 

                                            @if ($liquidation->status == LiquidationStatus::FOR_APPROVAL)
                                                <a href="#" data-href="{{ route('accounting.liquidations.approve', [$liquidation->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Approve</a> | 
                                                <a href="#" data-href="{{ route('accounting.liquidations.disapprove', [$liquidation->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Disapprove</a> | 
                                            @endif

                                            @if ($liquidation->status == LiquidationStatus::APPROVED || $liquidation->status == LiquidationStatus::DISAPPROVED)
                                                <a href="#" data-href="{{ route('accounting.liquidations.delete', [$liquidation->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Delete</a>
                                            @endif

                                            @if ($liquidation->status == LiquidationStatus::INACTIVE)
                                                <a href="#" data-href="{{ route('accounting.liquidations.recover', [$liquidation->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Recover</a>
                                            @endif
                                        </div>
                                    </td>
                                    <td id="compact-table">{{ $liquidation->name }}</td>
                                    <td id="compact-table">{{ $liquidation->description }}</td>
                                    <td id="compact-table">P{{ number_format($liquidation->cost, 2) }}</td> 
                                    <td>
                                        @if ($liquidation->status == LiquidationStatus::FOR_APPROVAL)
                                            <div class="badge badge-warning">For Approval</div>
                                        @elseif ($liquidation->status == LiquidationStatus::APPROVED)
                                            <div class="badge badge-success">Approved</div>
                                        @elseif ($liquidation->status == LiquidationStatus::DISAPPROVED)
                                            <div class="badge badge-danger">Disapproved</div>
                                        @elseif ($liquidation->status == LiquidationStatus::INACTIVE)
                                            <div class="badge badge-danger">Inactive</div>
                                        @endif
                                    </td>
                                    <td id="compact-table"><i class="material-icons icon-16pt text-muted mr-1">today</i> {{ Carbon::parse($liquidation->date)->format('M d Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if (count($liquidations) <= 0)
                        <div style="padding: 20px">
                            <center><i class="material-icons icon-16pt mr-1 text-muted">assignment</i> No record/s found</center>
                        </div>
                    @endif
                </div>
            </div>
            {{ $liquidations->links() }}
        </div>
    </div>
</div>


@include('layouts.auth.footer')