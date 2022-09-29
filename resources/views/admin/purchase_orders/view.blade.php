@include('layouts.auth.header')
@php
    use App\Models\Order;
    use App\Models\Inventory;
    use App\Models\ItemStatus;
    use App\Models\PurchaseOrderStatus;
@endphp
<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('internals.purchase-orders') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item">Purchase Orders</li>
                    <li class="breadcrumb-item active" aria-current="page">View</li>
                </ol>
            </nav>
            <h1 class="m-0">Purchase Order</h1>
        </div>
        <div class="row"> 
            <!-- START APPROVAL -->
            @if (auth()->user()->role == 'Super Admin' || auth()->user()->role == 'Admin' || auth()->user()->role == 'Accountant' || auth()->user()->role == 'Stockman')
                @if ($purchase_order->status == PurchaseOrderStatus::FOR_APPROVAL)
                    @if (auth()->user()->role == 'Super Admin')
                        <a href="#" data-href="{{ route('internals.purchase-orders.approve', [$purchase_order->id]) }}" data-toggle="modal" data-target="#confirm-action">
                            <button class="btn btn-success" id="margin-right"><i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">check</i>Approve</button>
                        </a>
                        <a href="#" data-href="{{ route('internals.purchase-orders.disapprove', [$purchase_order->id]) }}" data-toggle="modal" data-target="#confirm-action">
                        <button class="btn btn-danger" id="margin-right"><i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">cancel</i>Disapprove</button>
                        </a>
                    @endif

                    @if ($purchase_order->created_by_user_id != auth()->user()->id)
                        <a href="#" data-href="{{ route('internals.purchase-orders.approve', [$purchase_order->id]) }}" data-toggle="modal" data-target="#confirm-action">
                            <button class="btn btn-success" id="margin-right"><i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">check</i>Approve</button>
                        </a>
                        <a href="#" data-href="{{ route('internals.purchase-orders.disapprove', [$purchase_order->id]) }}" data-toggle="modal" data-target="#confirm-action">
                        <button class="btn btn-danger" id="margin-right"><i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">cancel</i>Disapprove</button>
                        </a>
                    @endif
                @endif
            @endif
            <!-- END APPROVAL -->

            @if (auth()->user()->role == 'Super Admin' || auth()->user()->role == 'Admin' || auth()->user()->role == 'Accountant' || auth()->user()->role == 'Sales')
                @if ($purchase_order->status == PurchaseOrderStatus::APPROVED)
                    @if (count($orders) > 0)
                        <form action="{{ route('internals.goods-receipts.create') }}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="purchase_order_id" value="{{ $purchase_order->id }}">

                        <button type="submit" class="btn btn-success" id="submitButton" onclick="submitForm(this);"><i class="material-icons">check</i> Create GRPO</button>
                        </form>
                    @endif
                @else
                    @if ($purchase_order->status == PurchaseOrderStatus::FOR_APPROVAL || $purchase_order->status == PurchaseOrderStatus::CHECKING_FOR_GRPO || $purchase_order->status == PurchaseOrderStatus::DONE)
                        <a href="{{ route('internals.exports.purchase-orders.print', [$purchase_order->id]) }}">
                            <button type="button" class="btn btn-light" id="margin-right"><i class="fa fa-print" id="margin-right"></i>Print</button>
                        </a>
                        <a href="{{ route('internals.exports.purchase-orders.excel', [$purchase_order->id]) }}">
                            <button type="button" class="btn btn-success" id="margin-right"><i class="fa fa-file-excel" id="margin-right"></i>Create Excel</button>
                        </a>
                        <a href="{{ route('internals.exports.purchase-orders.pdf', [$purchase_order->id]) }}">
                            <button type="button" class="btn btn-danger"><i class="fa fa-file-pdf" id="margin-right"></i>Create PDF</button>
                        </a>
                    @else
                        <button class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Purchase Order must be approved first" disabled><i class="material-icons">check</i> Create GRPO</button>
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
                            @foreach($orders as $order)
                            @php
                                $inventory = Inventory::where('company_id', auth()->user()->company_id)
                                                    ->where('item_id', $order->item->id)
                                                    ->first();
                            @endphp
                                <tr>
                                    <td id="compact-table">
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex align-items-center">
                                                @if ($order->supply->item->image)
                                                    <img src="{{ url($order->supply->item->image) }}" width="100px">
                                                @else
                                                    <img src="{{ url(env('APP_ICON')) }}" width="40px" style="margin-right: 7px;">
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td id="compact-table">
                                        <b>Supplier Price:</b> P{{ number_format($order->price, 2) }}<br>
                                        <b>{{ $order->item->name }}</b><br>
                                        {{ $order->item->brand->name }}<br>
                                        {{ $order->item->category->name }}
                                    </td>
                                    <td id="compact-table">
                                        @if (auth()->user()->role == 'Super Admin' || auth()->user()->role == 'Admin')
                                            <a href="#" data-toggle="modal" data-target="#edit-order-price-{{ $order->id }}">
                                                P{{ number_format($order->price, 2) }}
                                            </a>
                                        @else
                                            P{{ number_format($order->price, 2) }}
                                        @endif
                                    </td>
                                    <td id="compact-table">
                                        @if (auth()->user()->role == 'Super Admin' || auth()->user()->role == 'Admin')
                                            <a href="#" data-toggle="modal" data-target="#edit-order-discount-{{ $order->id }}">
                                                P{{ number_format($order->discount, 2) }}
                                            </a>
                                        @else
                                            P{{ number_format($order->discount, 2) }}
                                        @endif
                                    </td>
                                    <td id="compact-table">{{ $inventory->qty ?? 0 }}</td>
                                    <td id="compact-table">
                                        @if (auth()->user()->role == 'Super Admin' || auth()->user()->role == 'Admin')
                                            <a href="#" data-toggle="modal" data-target="#edit-order-qty-{{ $order->id }}">
                                                {{ $order->qty }}
                                            </a>
                                        @else
                                            {{ $order->qty }}
                                        @endif
                                    </td>
                                    <td id="compact-table">
                                        @if (auth()->user()->role == 'Super Admin' || auth()->user()->role == 'Admin')
                                            <a href="#" data-toggle="modal" data-target="#edit-order-free-qty-{{ $order->id }}">
                                                {{ $order->free_qty }}
                                            </a>
                                        @else
                                            {{ $order->free_qty }}
                                        @endif
                                    </td>
                                    <td id="compact-table">
                                        P{{ number_format($order->total, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td><strong>Grand Total</strong></td>
                                <td colspan="7" class="text-right"><strong>
                                P{{ number_format($orders_total, 2) }}
                                </strong></td>
                            </tr>
                        </tbody>
                    </table>

                    <div style="padding-top: 20px">
                        {{ $orders->links() }}
                    </div>

                    @if (count($orders) <= 0)
                        <div style="padding: 20px">
                            <center><i class="material-icons icon-16pt mr-1 text-muted">assignment</i> No record/s found</center>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col">
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
                            {{ $purchase_order->company->name }}
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
                            {{ $purchase_order->supplier->name }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Email Address</h6>
                            {{ $purchase_order->supplier->email }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Contact Person</h6>
                            {{ $purchase_order->supplier->person }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Phone</h6>
                            {{ $purchase_order->supplier->phone }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Mobile</h6>
                            {{ $purchase_order->supplier->mobile }}
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Line Address 1</h6>
                            {{ $purchase_order->supplier->line_address_1 }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Line Address 2</h6>
                            {{ $purchase_order->supplier->line_address_2 }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.auth.footer')