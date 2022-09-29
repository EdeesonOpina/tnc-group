@include('layouts.auth.header')
@php
    use App\Models\Order;
    use App\Models\Inventory;
    use App\Models\ItemStatus;
    use App\Models\ReturnInventoryStatus;
@endphp
<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('internals.return-inventories') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item">Return Inventories</li>
                    <li class="breadcrumb-item active" aria-current="page">View</li>
                </ol>
            </nav>
            <h1 class="m-0">Return Inventory</h1>
        </div>
        <div class="row"> 
            <!-- START APPROVAL -->
            @if (auth()->user()->role == 'Super Admin' || auth()->user()->role == 'Admin' || auth()->user()->role == 'Accountant')
                @if ($return_inventory->status == ReturnInventoryStatus::FOR_APPROVAL)
                    <a href="#" data-href="{{ route('internals.return-inventories.approve', [$return_inventory->id]) }}" data-toggle="modal" data-target="#confirm-action">
                        <button class="btn btn-success" id="margin-right"><i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">check</i>Approve</button>
                    </a>
                    <a href="#" data-href="{{ route('internals.return-inventories.disapprove', [$return_inventory->id]) }}" data-toggle="modal" data-target="#confirm-action">
                    <button class="btn btn-danger" id="margin-right"><i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">cancel</i>Disapprove</button>
                    </a>
                @endif
            @endif
            <!-- END APPROVAL -->

            @if (auth()->user()->role == 'Super Admin' || auth()->user()->role == 'Admin' || auth()->user()->role == 'Accountant' || auth()->user()->role == 'Sales')
                @if ($return_inventory->status == ReturnInventoryStatus::APPROVED)
                    @if (count($return_inventory_items) > 0)
                        <form action="{{ route('internals.return-inventories.apply') }}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="return_inventory_id" value="{{ $return_inventory->id }}">

                        <button type="submit" class="btn btn-success"><i class="material-icons">check</i> Apply To Inventory</button>
                        </form>
                    @endif
                @else
                    @if ($return_inventory->status == ReturnInventoryStatus::FOR_APPROVAL || $return_inventory->status == ReturnInventoryStatus::CHECKING_FOR_GRPO || $return_inventory->status == ReturnInventoryStatus::DONE)
                        <a href="{{ route('internals.exports.purchase-orders.print', [$return_inventory->id]) }}">
                            <button type="button" class="btn btn-light" id="margin-right"><i class="fa fa-print" id="margin-right"></i>Print</button>
                        </a>
                        <a href="{{ route('internals.exports.purchase-orders.excel', [$return_inventory->id]) }}">
                            <button type="button" class="btn btn-success" id="margin-right"><i class="fa fa-file-excel" id="margin-right"></i>Create Excel</button>
                        </a>
                        <a href="{{ route('internals.exports.purchase-orders.pdf', [$return_inventory->id]) }}">
                            <button type="button" class="btn btn-danger"><i class="fa fa-file-pdf" id="margin-right"></i>Create PDF</button>
                        </a>
                    @else
                        <button class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Return Inventory must be approved first" disabled><i class="material-icons">check</i> Apply To Inventory</button>
                    @endif
                @endif
            @endif
        </div>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h4 class="card-header__title flex m-0">Selected Item/s</h4>
                    <div data-toggle="flatpickr" data-flatpickr-wrap="true" data-flatpickr-static="true" data-flatpickr-mode="range" data-flatpickr-alt-format="d/m/Y" data-flatpickr-date-format="d/m/Y">
                        
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table"></th>
                                <th id="compact-table">Item For Return</th>
                                <th id="compact-table">In Stock</th>
                                <th id="compact-table">Qty</th>
                                <th id="compact-table">Replacement Qty</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                            @foreach($return_inventory_items as $return_inventory_item)
                                <tr>
                                    <td id="compact-table">
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex align-items-center">
                                                @if ($return_inventory_item->inventory->item->image)
                                                    <img src="{{ url($return_inventory_item->inventory->item->image) }}" width="100px">
                                                @else
                                                    <img src="{{ url(env('APP_ICON')) }}" width="40px" style="margin-right: 7px;">
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td id="compact-table">
                                        <b>Supplier Price:</b> P{{ number_format($return_inventory_item->price, 2) }}<br>
                                        <b>For Return: {{ $return_inventory_item->inventory->item->name }}</b><br>
                                        <b>Replacement: {{ $return_inventory_item->replacement_item->name }}</b><br>
                                        {{ $return_inventory_item->inventory->item->brand->name }}<br>
                                        {{ $return_inventory_item->inventory->item->category->name }}
                                    </td>
                                    <td id="compact-table">{{ $return_inventory_item->inventory->qty ?? 0 }}</td>
                                    <td id="compact-table">
                                        @if (auth()->user()->role == 'Super Admin' || auth()->user()->role == 'Admin')
                                            <a href="#" data-toggle="modal" data-target="#edit-return-inventory-item-{{ $return_inventory_item->id }}">
                                                {{ $return_inventory_item->qty }}
                                            </a>
                                        @else
                                            {{ $return_inventory_item->qty }}
                                        @endif
                                    </td>
                                    <td id="compact-table">
                                        @if (auth()->user()->role == 'Super Admin' || auth()->user()->role == 'Admin')
                                            <a href="#" data-toggle="modal" data-target="#edit-return-inventory-item-{{ $return_inventory_item->id }}">
                                                {{ $return_inventory_item->qty }}
                                            </a>
                                        @else
                                            {{ $return_inventory_item->qty }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div style="padding-top: 20px">
                        {{ $return_inventory_items->links() }}
                    </div>

                    @if (count($return_inventory_items) <= 0)
                        <div style="padding: 20px">
                            <center><i class="material-icons icon-16pt mr-1 text-muted">assignment</i> No record/s found</center>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col">
            <div id="spaced-card" class="card card-body">
                <h3>Return Inventory</h3>
                <br>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Return Inventory #</h6>
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
                <h3>Supplier</h3>
                <br>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Supplier Name</h6>
                            {{ $return_inventory->supplier->name }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Email Address</h6>
                            {{ $return_inventory->supplier->email }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Contact Person</h6>
                            {{ $return_inventory->supplier->person }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Phone</h6>
                            {{ $return_inventory->supplier->phone }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Mobile</h6>
                            {{ $return_inventory->supplier->mobile }}
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Line Address 1</h6>
                            {{ $return_inventory->supplier->line_address_1 }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Line Address 2</h6>
                            {{ $return_inventory->supplier->line_address_2 }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.auth.footer')