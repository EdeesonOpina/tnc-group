@include('layouts.auth.header')
@php
    use App\Models\ReturnInventory;
    use App\Models\ReturnInventoryItem;
    use App\Models\Inventory;
    use App\Models\ItemStatus;
    use App\Models\ReturnInventoryItemStatus;
    use App\Models\ReturnInventoryStatus;
@endphp
<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('internals.return-inventories') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item">Return Inventories</li>
                    <li class="breadcrumb-item active" aria-current="page">Manage</li>
                </ol>
            </nav>
            <h1 class="m-0">Return Inventory</h1>
        </div>
        @if (ReturnInventoryItem::where('return_inventory_id', $return_inventory->id)
                ->where('status', ReturnInventoryItemStatus::ACTIVE)
                ->exists())
            <form action="{{ route('internals.return-inventories.finalize') }}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="return_inventory_id" value="{{ $return_inventory->id }}">

            <button type="submit" class="btn btn-success"><i class="material-icons">check</i> Finalize</button>
            </form>
        @else
            <button type="button" class="btn btn-success" disabled><i class="material-icons">check</i> Finalize</button>
        @endif
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')

    <div class="row">
        <div class="col-md-8">
            <form action="{{ route('internals.return-inventories.inventories.search', [$return_inventory->id]) }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="return_inventory_id" value="{{ $return_inventory->id }}">
                <div class="card card-form d-flex flex-column flex-sm-row">
                    <div class="card-form__body card-body-form-group flex">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Search For Item</label>
                                    <input name="name" type="text" class="form-control" placeholder="Search by name" value="{{ old('name') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label><a href="{{ route('internals.return-inventories.manage', [$return_inventory->id]) }}" id="no-underline">Clear Filters</a></label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn bg-primary border-left border-top border-top-sm-0 rounded-top-0 rounded-top-sm rounded-left-sm-0"><i class="material-icons text-white icon-20pt">search</i></button>
                </div>
            </form>
            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h4 class="card-header__title flex m-0">Please Select Item/s</h4>
                    <div data-toggle="flatpickr" data-flatpickr-wrap="true" data-flatpickr-static="true" data-flatpickr-mode="range" data-flatpickr-alt-format="d/m/Y" data-flatpickr-date-format="d/m/Y">
                        
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table"></th>
                                <th id="compact-table"></th>
                                <th id="compact-table">Name</th>
                                <th id="compact-table">In Stock</th>
                                <th id="compact-table">Qty</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                            @foreach($inventories as $inventory)
                            @php
                                $return_inventory_item = ReturnInventoryItem::where('return_inventory_id', $return_inventory->id)
                                            ->where('inventory_id', $inventory->id)
                                            ->where('status', ReturnInventoryItemStatus::ACTIVE)
                                            ->first();

                                $inventory = Inventory::where('branch_id', auth()->user()->branch_id)
                                                    ->where('item_id', $inventory->item->id)
                                                    ->first();
                            @endphp
                                <tr>
                                    <td>
                                        @if ($return_inventory_item)
                                            <a href="{{ route('internals.return-inventories.inventories.delete', [$return_inventory->id, $return_inventory_item->id]) }}">
                                                <button class="btn btn-sm btn-danger"><i class="material-icons icon-20pt text-white" data-toggle="tooltip" data-placement="top" title="Delete">delete</i></button>
                                            </a>
                                            
                                        @else
                                            <a href="#" data-toggle="modal" data-target="#add-return-inventory-item-{{ $inventory->item->id }}">
                                                <button class="btn btn-sm btn-primary"><i class="material-icons icon-20pt text-white" data-toggle="tooltip" data-placement="top" title="Add To List">add</i></button>
                                            </a>
                                        @endif
                                    </td>
                                    <td id="compact-table">
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex align-items-center">
                                                @if ($return_inventory_item)
                                                    @if ($return_inventory_item->inventory->item->image)
                                                        <img src="{{ url($return_inventory_item->inventory->item->image) }}" width="100px">
                                                    @else
                                                        <img src="{{ url(env('APP_ICON')) }}" width="40px" style="margin-right: 7px;">
                                                    @endif
                                                @else
                                                    @if ($inventory->item->image)
                                                        <img src="{{ url($inventory->item->image) }}" width="100px">
                                                    @else
                                                        <img src="{{ url(env('APP_ICON')) }}" width="40px" style="margin-right: 7px;">
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td id="compact-table">
                                        <b>Supplier Price:</b> P{{ number_format($inventory->price, 2) }}<br>
                                        <b>{{ $inventory->item->name }}</b><br>
                                        {{ $inventory->item->brand->name }}<br>
                                        {{ $inventory->item->category->name }}
                                    </td>
                                    <td id="compact-table">{{ $inventory->qty ?? 0 }}</td>
                                    <td id="compact-table">
                                        @if ($return_inventory_item)
                                            <a href="#" data-toggle="modal" data-target="#edit-return-inventory-item-qty-{{ $return_inventory_item->id }}">
                                                {{ $return_inventory_item->qty }}
                                            </a>
                                        @else
                                            0
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div style="padding-top: 20px">
                        {{ $inventories->links() }}
                    </div>

                    @if (count($inventories) <= 0)
                        <div style="padding: 20px">
                            <center><i class="material-icons icon-16pt mr-1 text-muted">assignment</i> No record/s found</center>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div id="spaced-card" class="card card-body">
                <h3>Return</h3>
                <br>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Return #</h6>
                            <span class="badge badge-success" id="large-font">{{ $return_inventory->reference_number }}</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Status</h6>
                            @if ($return_inventory->status == ReturnInventoryStatus::ON_PROCESS)
                                <span class="badge badge-warning" id="large-font">ON PROCESS</span>
                            @elseif ($return_inventory->status == ReturnInventoryStatus::READY_FOR_GRPO)
                                <span class="badge badge-success" id="large-font">READY FOR GRPO</span>
                            @elseif ($return_inventory->status == ReturnInventoryStatus::CHECKING_FOR_GRPO)
                                <span class="badge badge-success" id="large-font">CHECKING FOR GRPO</span>
                            @elseif ($return_inventory->status == ReturnInventoryStatus::DONE)
                                <span class="badge badge-success" id="large-font">DONE</span>
                            @elseif ($return_inventory->status == ReturnInventoryStatus::FOR_APPROVAL)
                                <span class="badge badge-success" id="large-font">FOR APPROVAL</span>
                            @elseif ($return_inventory->status == ReturnInventoryStatus::APPROVED)
                                <span class="badge badge-success" id="large-font">APPROVED</span>
                            @elseif ($return_inventory->status == ReturnInventoryStatus::DISAPPROVED)
                                <span class="badge badge-danger" id="large-font">DISAPPROVED</span>
                            @elseif ($return_inventory->status == ReturnInventoryStatus::CANCELLED)
                                <span class="badge badge-danger" id="large-font">CANCELLED</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Supplier</h6>
                            {{ $return_inventory->supplier->name }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Destination</h6>
                            {{ $return_inventory->branch->name }}
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div id="spaced-card" class="card card-body">
                <h3>Return List</h3>
                <a href="{{ route('internals.return-inventories.inventories.masterlist', [$return_inventory->id]) }}">
                    View Masterlist
                </a>

                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table"></th>
                                <th id="compact-table">Name</th>
                                <th id="compact-table">Qty</th>
                                <th id="compact-table">Brand</th>
                                <th id="compact-table">Category</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                            @foreach($return_inventory_items as $return_inventory_item)
                                <tr>
                                    <td id="compact-table">
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex align-items-center">
                                                @if ($return_inventory_item->inventory->item->image)
                                                    <img src="{{ url($return_inventory_item->inventory->item->image) }}" width="40px">
                                                @else
                                                    <img src="{{ url(env('APP_ICON')) }}" width="40px" style="margin-right: 7px;">
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td id="compact-table"><b>{{ $return_inventory_item->inventory->item->name }}</b></td>
                                    <td id="compact-table">{{ $return_inventory_item->qty }}</td>
                                    <td id="compact-table">{{ $return_inventory_item->inventory->item->brand->name }}</td>
                                    <td id="compact-table">{{ $return_inventory_item->inventory->item->category->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if (count($return_inventory_items) <= 0)
                        <div style="padding: 20px">
                            <center><i class="material-icons icon-16pt mr-1 text-muted">assignment</i> No record/s found</center>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.auth.footer')