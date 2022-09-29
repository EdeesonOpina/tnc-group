@include('layouts.auth.header')
@php
    use App\Models\Inventory;
    use App\Models\InventoryStatus;
    use App\Models\ReturnInventoryItem;
    use App\Models\ReturnInventoryStatus;
    use App\Models\ReturnInventoryItemStatus;
@endphp

<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Return Inventories</li>
                </ol>
            </nav>
            <h1 class="m-0">Return Inventories</h1>
        </div>
        <a href="{{ route('internals.return-inventories.add') }}" class="btn btn-primary"><i class="material-icons">add</i> Add</a>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')
    @include('layouts.partials.top-tabs')

    <form action="{{ route('internals.return-inventories.search') }}" method="post">
        {{ csrf_field() }}
        <div class="card card-form d-flex flex-column flex-sm-row">
            <div class="card-form__body card-body-form-group flex">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>Return#</label>
                            <input name="reference_number" type="text" class="form-control" placeholder="Search by return number" value="{{ old('reference_number') }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Supplier</label>
                            <input name="name" type="text" class="form-control" placeholder="Search by name" value="{{ old('name') }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" data-toggle="select">
                                @if (old('status'))
                                    @if (old('status') != '*')
                                        @if (old('status') == ReturnInventoryStatus::ON_PROCESS)
                                            <option value="{{ old('status') }}">On Process</option>
                                        @endif

                                        @if (old('status') == ReturnInventoryStatus::READY_FOR_GRPO)
                                            <option value="{{ old('status') }}">Ready For GRPO</option>
                                        @endif

                                        @if (old('status') == ReturnInventoryStatus::CHECKING_FOR_GRPO)
                                            <option value="{{ old('status') }}">Checking For GRPO</option>
                                        @endif

                                        @if (old('status') == ReturnInventoryStatus::DONE)
                                            <option value="{{ old('status') }}">Done</option>
                                        @endif

                                        @if (old('status') == ReturnInventoryStatus::FOR_APPROVAL)
                                            <option value="{{ old('status') }}">For Approval</option>
                                        @endif

                                        @if (old('status') == ReturnInventoryStatus::APPROVED)
                                            <option value="{{ old('status') }}">Approved</option>
                                        @endif

                                        @if (old('status') == ReturnInventoryStatus::DISAPPROVED)
                                            <option value="{{ old('status') }}">Disapproved</option>
                                        @endif

                                        @if (old('status') == ReturnInventoryStatus::CANCELLED)
                                            <option value="{{ old('status') }}">Cancelled</option>
                                        @endif
                                    @endif
                                @endif
                                <option value="*">All</option>
                                <option value="{{ ReturnInventoryStatus::ON_PROCESS }}">On Process</option>
                                <option value="{{ ReturnInventoryStatus::READY_FOR_GRPO }}">Ready For GRPO</option>
                                <option value="{{ ReturnInventoryStatus::CHECKING_FOR_GRPO }}">Checking For GRPO</option>
                                <option value="{{ ReturnInventoryStatus::DONE }}">Done</option>
                                <option value="{{ ReturnInventoryStatus::FOR_APPROVAL }}">For Approval</option>
                                <option value="{{ ReturnInventoryStatus::APPROVED }}">Approved</option>
                                <option value="{{ ReturnInventoryStatus::DISAPPROVED }}">Disapproved</option>
                                <option value="{{ ReturnInventoryStatus::CANCELLED }}">Cancelled</option>
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
                        <label><a href="{{ route('internals.return-inventories') }}" id="no-underline">Clear Filters</a></label>
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
                    <h4 class="card-header__title flex m-0">Return Inventories</h4>
                    <div data-toggle="flatpickr" data-flatpickr-wrap="true" data-flatpickr-static="true" data-flatpickr-mode="range" data-flatpickr-alt-format="d/m/Y" data-flatpickr-date-format="d/m/Y">
                        
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table">Return#</th>
                                <th id="compact-table">Total Qty</th>
                                <th id="compact-table">Supplier</th>
                                <th id="compact-table">Destination</th>
                                <th id="compact-table">Created By</th>
                                <th id="compact-table">Approved By</th>
                                <th id="compact-table">Status</th>
                                <th id="compact-table">Created At</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                            @foreach($return_inventories as $return_inventory)
                            @php
                                $return_inventory_items_count = ReturnInventoryItem::where('return_inventory_id', $return_inventory->id)
                                                    ->where('status', ReturnInventoryItemStatus::ACTIVE)
                                                    ->sum('qty');
                            @endphp
                                <tr>
                                    <td id="compact-table">
                                        <b># {{ $return_inventory->reference_number }}</b>
                                        <div class="d-flex">
                                            <a href="{{ route('internals.return-inventories.view', [$return_inventory->id]) }}" id="table-letter-margin">View</a> |  

                                            @if ($return_inventory->status == ReturnInventoryStatus::ON_PROCESS)
                                                @if (auth()->user()->role == 'Super Admin' || auth()->user()->role == 'Admin' || $return_inventory->created_by_user->id == auth()->user()->id)
                                                    <a href="{{ route('internals.return-inventories.manage', [$return_inventory->id]) }}" id="space-table">Manage</a> | 
                                                @endif

                                                <a href="#" data-href="{{ route('internals.return-inventories.cancel', [$return_inventory->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Cancel</a>
                                            @endif

                                            @if ($return_inventory->status == ReturnInventoryStatus::READY_FOR_GRPO)
                                                <a href="#" data-href="{{ route('internals.return-inventories.cancel', [$return_inventory->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Cancel</a>
                                            @endif

                                            @if ($return_inventory->status == ReturnInventoryStatus::APPROVED)
                                                <a href="#" data-href="{{ route('internals.return-inventories.cancel', [$return_inventory->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Cancel</a>
                                            @endif

                                            @if ($return_inventory->status == ReturnInventoryStatus::CHECKING_FOR_GRPO)
                                                <a href="#" data-href="{{ route('internals.return-inventories.cancel', [$return_inventory->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Cancel</a>
                                            @endif

                                            @if ($return_inventory->status == ReturnInventoryStatus::FOR_APPROVAL)
                                                <a href="#" data-href="{{ route('internals.return-inventories.approve', [$return_inventory->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Approve</a> | 

                                                <a href="#" data-href="{{ route('internals.return-inventories.disapprove', [$return_inventory->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Disapprove</a> | 

                                                <a href="#" data-href="{{ route('internals.return-inventories.cancel', [$return_inventory->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Cancel</a>
                                            @endif

                                            @if ($return_inventory->status == ReturnInventoryStatus::CANCELLED)
                                                <a href="#" data-href="{{ route('internals.return-inventories.recover', [$return_inventory->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Recover</a>
                                            @endif
                                        </div>
                                    </td>
                                    <td id="compact-table">{{ $return_inventory_items_count }}</td>
                                    <td id="compact-table"><i class="material-icons icon-16pt mr-1 text-muted">business</i> {{ $return_inventory->supplier->name }}</td>
                                    <td id="compact-table"><i class="material-icons icon-16pt mr-1 text-muted">business</i> {{ $return_inventory->branch->name }}</td>
                                    <td id="compact-table"><i class="material-icons icon-16pt mr-1 text-muted">face</i> {{ $return_inventory->created_by_user->firstname }} {{ $return_inventory->created_by_user->lastname }}</td>
                                    <td id="compact-table">
                                        @if ($return_inventory->approved_by_user)
                                            <i class="material-icons icon-16pt mr-1 text-muted">face</i> {{ $return_inventory->approved_by_user->firstname }} {{ $return_inventory->approved_by_user->lastname }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($return_inventory->status == ReturnInventoryStatus::ON_PROCESS)
                                            <div class="badge badge-warning ml-2">ON PROCESS</div>
                                        @elseif ($return_inventory->status == ReturnInventoryStatus::CHECKING_FOR_GRPO)
                                            <div class="badge badge-success ml-2">CHECKING FOR GRPO</div>
                                        @elseif ($return_inventory->status == ReturnInventoryStatus::READY_FOR_GRPO)
                                            <div class="badge badge-success ml-2">READY FOR GRPO</div>
                                        @elseif ($return_inventory->status == ReturnInventoryStatus::DONE)
                                            <div class="badge badge-success ml-2">DONE</div>
                                        @elseif ($return_inventory->status == ReturnInventoryStatus::FOR_APPROVAL)
                                            <div class="badge badge-success ml-2">FOR APPROVAL</div>
                                        @elseif ($return_inventory->status == ReturnInventoryStatus::APPROVED)
                                            <div class="badge badge-success ml-2">APPROVED</div>
                                        @elseif ($return_inventory->status == ReturnInventoryStatus::DISAPPROVED)
                                            <div class="badge badge-danger ml-2">DISAPPROVED</div>
                                        @elseif ($return_inventory->status == ReturnInventoryStatus::CANCELLED)
                                            <div class="badge badge-danger ml-2">CANCELLED</div>
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