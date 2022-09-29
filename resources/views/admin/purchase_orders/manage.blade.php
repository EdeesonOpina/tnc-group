@include('layouts.auth.header')
@php
    use App\Models\Order;
    use App\Models\Inventory;
    use App\Models\ItemStatus;
    use App\Models\OrderStatus;
    use App\Models\PurchaseOrderStatus;
@endphp
<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('internals.purchase-orders') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item">Purchase Orders</li>
                    <li class="breadcrumb-item active" aria-current="page">Manage</li>
                </ol>
            </nav>
            <h1 class="m-0">Purchase Order</h1>
        </div>
        @if (Order::where('purchase_order_id', $purchase_order->id)
                ->where('status', OrderStatus::ACTIVE)
                ->exists())
            <form action="{{ route('internals.purchase-orders.finalize') }}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="purchase_order_id" value="{{ $purchase_order->id }}">

            <button type="submit" class="btn btn-success" id="submitButton" onclick="submitForm(this);"><i class="material-icons">check</i> Finalize</button>
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
            <form action="{{ route('internals.orders.search', [$purchase_order->id]) }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="purchase_order_id" value="{{ $purchase_order->id }}">
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
                                <label><a href="{{ route('internals.purchase-orders.manage', [$purchase_order->id]) }}" id="no-underline">Clear Filters</a></label>
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
                                <th id="compact-table">Price</th>
                                <th id="compact-table">Discount</th>
                                <th id="compact-table">In Stock</th>
                                <th id="compact-table">Qty</th>
                                <th id="compact-table">Free Qty</th>
                                <th id="compact-table">Total</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                            @foreach($supplies->unique('item_id') as $supply)
                            @php
                                $order = Order::where('purchase_order_id', $purchase_order->id)
                                            ->where('supply_id', $supply->id)
                                            ->where('status', OrderStatus::ACTIVE)
                                            ->first();

                                $inventory = Inventory::where('branch_id', auth()->user()->branch_id)
                                                    ->where('item_id', $supply->item->id)
                                                    ->first();
                            @endphp
                                <tr>
                                    <td>
                                        @if ($order)
                                            <a href="{{ route('internals.orders.delete', [$purchase_order->supplier->id, $order->id]) }}">
                                                <button class="btn btn-sm btn-danger"><i class="material-icons icon-20pt text-white" data-toggle="tooltip" data-placement="top" title="Delete">delete</i></button>
                                            </a>
                                            
                                        @else
                                            <a href="#" data-toggle="modal" data-target="#add-order-{{ $supply->item->id }}">
                                                <button class="btn btn-sm btn-primary"><i class="material-icons icon-20pt text-white" data-toggle="tooltip" data-placement="top" title="Add To List">add</i></button>
                                            </a>
                                        @endif
                                    </td>
                                    <td id="compact-table">
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex align-items-center">
                                                @if ($order)
                                                    @if ($order->supply->item->image)
                                                        <img src="{{ url($order->supply->item->image) }}" width="100px">
                                                    @else
                                                        <img src="{{ url(env('APP_ICON')) }}" width="40px" style="margin-right: 7px;">
                                                    @endif
                                                @else
                                                    @if ($supply->item->image)
                                                        <img src="{{ url($supply->item->image) }}" width="100px">
                                                    @else
                                                        <img src="{{ url(env('APP_ICON')) }}" width="40px" style="margin-right: 7px;">
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td id="compact-table">
                                        <strong>ID:</strong> <div class="badge badge-light">#{{ $supply->id }}</div><br>
                                        <b>Supplier Price:</b> P{{ number_format($supply->price, 2) }}<br>
                                        <b>{{ $supply->item->name }}</b><br>
                                        {{ $supply->item->brand->name }}<br>
                                        {{ $supply->item->category->name }}
                                    </td>
                                    <td id="compact-table">
                                        @if ($order)
                                            <a href="#" data-toggle="modal" data-target="#edit-order-price-{{ $order->id }}">
                                                P{{ number_format($order->price, 2) }}
                                            </a>
                                        @else
                                            P{{ number_format(0, 2) }}
                                        @endif
                                    </td>
                                    <td id="compact-table">
                                        @if ($order)
                                            <a href="#" data-toggle="modal" data-target="#edit-order-discount-{{ $order->id }}">
                                                P{{ number_format($order->discount, 2) }}
                                            </a>
                                        @else
                                            P{{ number_format(0, 2) }}
                                        @endif
                                    </td>
                                    <td id="compact-table">{{ $inventory->qty ?? 0 }}</td>
                                    <td id="compact-table">
                                        @if ($order)
                                            <a href="#" data-toggle="modal" data-target="#edit-order-qty-{{ $order->id }}">
                                                {{ $order->qty }}
                                            </a>
                                        @else
                                            0
                                        @endif
                                    </td>
                                    <td id="compact-table">
                                        @if ($order)
                                            <a href="#" data-toggle="modal" data-target="#edit-order-free-qty-{{ $order->id }}">
                                                {{ $order->free_qty }}
                                            </a>
                                        @else
                                            0
                                        @endif
                                    </td>
                                    <td id="compact-table">
                                        @if ($order)
                                            P{{ number_format($order->total, 2) }}
                                        @else
                                            P{{ number_format(0, 2) }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div style="padding-top: 20px">
                        {{ $supplies->links() }}
                    </div>

                    @if (count($supplies) <= 0)
                        <div style="padding: 20px">
                            <center><i class="material-icons icon-16pt mr-1 text-muted">assignment</i> No record/s found</center>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div id="spaced-card" class="card card-body">
                <h3>Purchase Order</h3>
                <br>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Purchase Order #</h6>
                            <span class="badge badge-success" id="large-font">{{ $purchase_order->reference_number }}</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Status</h6>
                            @if ($purchase_order->status == PurchaseOrderStatus::ON_PROCESS)
                                <span class="badge badge-warning" id="large-font">ON PROCESS</span>
                            @elseif ($purchase_order->status == PurchaseOrderStatus::READY_FOR_GRPO)
                                <span class="badge badge-success" id="large-font">READY FOR GRPO</span>
                            @elseif ($purchase_order->status == PurchaseOrderStatus::CHECKING_FOR_GRPO)
                                <span class="badge badge-success" id="large-font">CHECKING FOR GRPO</span>
                            @elseif ($purchase_order->status == PurchaseOrderStatus::DONE)
                                <span class="badge badge-success" id="large-font">DONE</span>
                            @elseif ($purchase_order->status == PurchaseOrderStatus::FOR_APPROVAL)
                                <span class="badge badge-success" id="large-font">FOR APPROVAL</span>
                            @elseif ($purchase_order->status == PurchaseOrderStatus::APPROVED)
                                <span class="badge badge-success" id="large-font">APPROVED</span>
                            @elseif ($purchase_order->status == PurchaseOrderStatus::DISAPPROVED)
                                <span class="badge badge-danger" id="large-font">DISAPPROVED</span>
                            @elseif ($purchase_order->status == PurchaseOrderStatus::CANCELLED)
                                <span class="badge badge-danger" id="large-font">CANCELLED</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Supplier</h6>
                            {{ $purchase_order->supplier->name }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Destination</h6>
                            {{ $purchase_order->branch->name }}
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div id="spaced-card" class="card card-body">
                <h3>Order List</h3>
                <a href="{{ route('internals.purchase-orders.orders.masterlist', [$purchase_order->id]) }}">
                    View Masterlist
                </a>

                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table"></th>
                                <th id="compact-table">Name</th>
                                <th id="compact-table">Price</th>
                                <th id="compact-table">Brand</th>
                                <th id="compact-table">Category</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                            @foreach($current_orders as $current_order)
                                <tr>
                                    <td id="compact-table">
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex align-items-center">
                                                @if ($current_order->item->image)
                                                    <img src="{{ url($current_order->item->image) }}" width="40px">
                                                @else
                                                    <img src="{{ url(env('APP_ICON')) }}" width="40px" style="margin-right: 7px;">
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td id="compact-table"><b>{{ $current_order->item->name }}</b></td>
                                    <td id="compact-table">P{{ number_format($current_order->price, 2) }}</td>
                                    <td id="compact-table">{{ $current_order->item->brand->name }}</td>
                                    <td id="compact-table">{{ $current_order->item->category->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if (count($current_orders) <= 0)
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