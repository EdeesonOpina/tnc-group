@include('layouts.auth.header')
@php
    use App\Models\ReturnInventoryStatus;
@endphp

<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('internals.rma') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">RMA</li>
                </ol>
            </nav>
            <h1 class="m-0">RMA</h1>
        </div>
        <a href="{{ route('internals.rma.add') }}" class="btn btn-primary"><i class="material-icons">add</i> Create RMA</a>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')

    <form action="{{ route('internals.rma.search') }}" method="post">
        {{ csrf_field() }}
        <div class="card card-form d-flex flex-column flex-sm-row">
            <div class="card-form__body card-body-form-group flex">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>RMA#</label>
                            <input name="reference_number" type="text" class="form-control" placeholder="Search by RMA number" value="{{ old('reference_number') }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Customer</label>
                            <input name="name" type="text" class="form-control" placeholder="Search by name" value="{{ old('name') }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" data-toggle="select">
                                @if (old('status'))
                                    @if (old('status') != '*')
                                        @if (old('status') == ReturnInventoryStatus::FOR_WARRANTY)
                                            <option value="{{ old('status') }}">FOR_WARRANTY</option>
                                        @endif

                                        @if (old('status') == ReturnInventoryStatus::WAITING)
                                            <option value="{{ old('status') }}">WAITING</option>
                                        @endif

                                        @if (old('status') == ReturnInventoryStatus::FOR_RELEASE)
                                            <option value="{{ old('status') }}">FOR_RELEASE</option>
                                        @endif

                                        @if (old('status') == ReturnInventoryStatus::CLEARED)
                                            <option value="{{ old('status') }}">CLEARED</option>
                                        @endif

                                        @if (old('status') == ReturnInventoryStatus::CANCELLED)
                                            <option value="{{ old('status') }}">Cancelled</option>
                                        @endif
                                    @endif
                                @endif
                                <option value="*">All</option>
                                <option value="{{ ReturnInventoryStatus::FOR_WARRANTY }}">FOR WARRANTY</option>
                                <option value="{{ ReturnInventoryStatus::ON_PROCESS }}">ON PROCESS</option>
                                <option value="{{ ReturnInventoryStatus::WAITING }}">WAITING</option>
                                <option value="{{ ReturnInventoryStatus::FOR_RELEASE }}">FOR RELEASE</option>
                                <option value="{{ ReturnInventoryStatus::CLEARED }}">CLEARED</option>
                                <option value="{{ ReturnInventoryStatus::OUT_OF_WARRANTY }}">OUT OF WARRANTY</option>
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
                        <label><a href="{{ route('internals.rma') }}" id="no-underline">Clear Filters</a></label>
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
                    <h4 class="card-header__title flex m-0">RMA</h4>
                    <div data-toggle="flatpickr" data-flatpickr-wrap="true" data-flatpickr-static="true" data-flatpickr-mode="range" data-flatpickr-alt-format="d/m/Y" data-flatpickr-date-format="d/m/Y">
                        
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table">RMA#</th>
                                <th id="compact-table">SO#</th>
                                <th id="compact-table">Supplier</th>
                                <th id="compact-table">Customer</th>
                                <th id="compact-table">Status</th>
                                <th id="compact-table">Created At</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                            @foreach($return_inventories as $return_inventory)
                                <tr>
                                    <td id="compact-table">
                                        <strong># {{ $return_inventory->reference_number }}</strong>
                                        <div class="d-flex">
                                            <a href="{{ route('internals.rma.view', [$return_inventory->reference_number]) }}" id="table-letter-margin">View</a> | 
                                            @if ($return_inventory->status == ReturnInventoryStatus::ON_PROCESS)
                                                <a href="{{ route('internals.rma.manage', [$return_inventory->reference_number]) }}" id="space-table">Manage</a> | 
                                            @endif

                                            @if ($return_inventory->status != ReturnInventoryStatus::CANCELLED)
                                                @if ($return_inventory->status == ReturnInventoryStatus::CLEARED)

                                                @else
                                                    <a href="#" data-href="{{ route('internals.rma.cancel', [$return_inventory->reference_number]) }}" id="space-table" data-toggle="modal" data-target="#confirm-action">Cancel</a>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                    <td id="compact-table"><strong># {{ $return_inventory->payment_receipt->so_number }}</strong></td>
                                    <td id="compact-table">
                                        @if ($return_inventory->goods_receipt)
                                            <i class="material-icons icon-16pt mr-1 text-muted">business</i> {{ $return_inventory->goods_receipt->purchase_order->supplier->name ?? null }}
                                        @endif

                                        @if ($return_inventory->supplier)
                                            <i class="material-icons icon-16pt mr-1 text-muted">business</i> {{ $return_inventory->supplier->name ?? null }}
                                        @endif
                                    </td>
                                    <td id="compact-table"><i class="material-icons icon-16pt mr-1 text-muted">face</i> {{ $return_inventory->payment_receipt->user->firstname }} {{ $return_inventory->payment_receipt->user->lastname }}</td>
                                    <td id="compact-table">
                                        @if ($return_inventory->status == ReturnInventoryStatus::WAITING)
                                            <div class="badge badge-warning">WAITING</div>
                                        @elseif ($return_inventory->status == ReturnInventoryStatus::ON_PROCESS)
                                            <div class="badge badge-warning">ON PROCESS</div>
                                        @elseif ($return_inventory->status == ReturnInventoryStatus::FOR_WARRANTY)
                                            <div class="badge badge-warning">FOR WARRANTY</div>
                                        @elseif ($return_inventory->status == ReturnInventoryStatus::FOR_RELEASE)
                                            <div class="badge badge-warning">FOR RELEASE</div>
                                        @elseif ($return_inventory->status == ReturnInventoryStatus::CLEARED)
                                            <div class="badge badge-success">CLEARED</div>
                                        @elseif ($return_inventory->status == ReturnInventoryStatus::OUT_OF_WARRANTY)
                                            <div class="badge badge-danger">OUT OF WARRANTY</div>
                                        @elseif ($return_inventory->status == ReturnInventoryStatus::CANCELLED)
                                            <div class="badge badge-danger">CANCELLED</div>
                                        @elseif ($return_inventory->status == ReturnInventoryStatus::INACTIVE)
                                            <div class="badge badge-danger">INACTIVE</div>
                                        @endif
                                    </td>
                                    <td id="compact-table"><i class="material-icons icon-16pt text-muted mr-1">today</i> {{ $return_inventory->created_at->format('M d Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if (count($return_inventories) <= 0)
                        <div style="padding: 20px">
                            <center><i class="material-icons icon-16pt mr-1 text-muted">assignment</i> No record/s found</center>
                        </div>
                    @endif
                </div>
            </div>
            {{ $return_inventories->links() }}
        </div>
    </div>
</div>


@include('layouts.auth.footer')