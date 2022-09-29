@include('layouts.auth.header')
@php
    use App\Models\Order;
    use App\Models\OrderStatus;
    use App\Models\PurchaseOrderStatus;
@endphp

<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Purchase Orders</li>
                </ol>
            </nav>
            <h1 class="m-0">Purchase Orders</h1>
        </div>
        <a href="{{ route('internals.purchase-orders.add') }}" class="btn btn-primary"><i class="material-icons">add</i> Add</a>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')
    @include('layouts.partials.top-tabs')

    <form action="{{ route('internals.purchase-orders.search') }}" method="post">
        {{ csrf_field() }}
        <div class="card card-form d-flex flex-column flex-sm-row">
            <div class="card-form__body card-body-form-group flex">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>PO#</label>
                            <input name="reference_number" type="text" class="form-control" placeholder="Search by PO number" value="{{ old('reference_number') }}">
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
                                        @if (old('status') == PurchaseOrderStatus::ON_PROCESS)
                                            <option value="{{ old('status') }}">On Process</option>
                                        @endif

                                        @if (old('status') == PurchaseOrderStatus::READY_FOR_GRPO)
                                            <option value="{{ old('status') }}">Ready For GRPO</option>
                                        @endif

                                        @if (old('status') == PurchaseOrderStatus::CHECKING_FOR_GRPO)
                                            <option value="{{ old('status') }}">Checking For GRPO</option>
                                        @endif

                                        @if (old('status') == PurchaseOrderStatus::DONE)
                                            <option value="{{ old('status') }}">Done</option>
                                        @endif

                                        @if (old('status') == PurchaseOrderStatus::FOR_APPROVAL)
                                            <option value="{{ old('status') }}">For Approval</option>
                                        @endif

                                        @if (old('status') == PurchaseOrderStatus::APPROVED)
                                            <option value="{{ old('status') }}">Approved</option>
                                        @endif

                                        @if (old('status') == PurchaseOrderStatus::DISAPPROVED)
                                            <option value="{{ old('status') }}">Disapproved</option>
                                        @endif

                                        @if (old('status') == PurchaseOrderStatus::CANCELLED)
                                            <option value="{{ old('status') }}">Cancelled</option>
                                        @endif
                                    @endif
                                @endif
                                <option value="*">All</option>
                                <option value="{{ PurchaseOrderStatus::ON_PROCESS }}">On Process</option>
                                <option value="{{ PurchaseOrderStatus::READY_FOR_GRPO }}">Ready For GRPO</option>
                                <option value="{{ PurchaseOrderStatus::CHECKING_FOR_GRPO }}">Checking For GRPO</option>
                                <option value="{{ PurchaseOrderStatus::DONE }}">Done</option>
                                <option value="{{ PurchaseOrderStatus::FOR_APPROVAL }}">For Approval</option>
                                <option value="{{ PurchaseOrderStatus::APPROVED }}">Approved</option>
                                <option value="{{ PurchaseOrderStatus::DISAPPROVED }}">Disapproved</option>
                                <option value="{{ PurchaseOrderStatus::CANCELLED }}">Cancelled</option>
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
                        <label><a href="{{ route('internals.purchase-orders') }}" id="no-underline">Clear Filters</a></label>
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
                    <h4 class="card-header__title flex m-0">Purchase Orders</h4>
                    <div data-toggle="flatpickr" data-flatpickr-wrap="true" data-flatpickr-static="true" data-flatpickr-mode="range" data-flatpickr-alt-format="d/m/Y" data-flatpickr-date-format="d/m/Y">
                        
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table">PO#</th>
                                <th id="compact-table">Grand Total</th>
                                <th id="compact-table">Supplier</th>
                                <th id="compact-table">Destination</th>
                                <th id="compact-table">Created By</th>
                                <th id="compact-table">Approved By</th>
                                <th id="compact-table">Status</th>
                                <th id="compact-table">Created At</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                            @foreach($purchase_orders as $purchase_order)
                            @php
                                $orders_total = Order::where('purchase_order_id', $purchase_order->id)
                                                    ->where('status', OrderStatus::ACTIVE)
                                                    ->sum('total');
                            @endphp
                                <tr>
                                    <td id="compact-table">
                                        <b># {{ $purchase_order->reference_number }}</b>
                                        <div class="d-flex">
                                            <a href="{{ route('internals.purchase-orders.view', [$purchase_order->id]) }}" id="table-letter-margin">View</a> |  

                                            @if ($purchase_order->status == PurchaseOrderStatus::ON_PROCESS)
                                                @if (auth()->user()->role == 'Super Admin' || auth()->user()->role == 'Admin' || $purchase_order->created_by_user->id == auth()->user()->id)
                                                    <a href="{{ route('internals.purchase-orders.manage', [$purchase_order->id]) }}" id="space-table">Manage</a> | 
                                                @endif

                                                <a href="#" data-href="{{ route('internals.purchase-orders.cancel', [$purchase_order->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Cancel</a>
                                            @endif

                                            @if ($purchase_order->status == PurchaseOrderStatus::READY_FOR_GRPO)
                                                <a href="#" data-href="{{ route('internals.purchase-orders.cancel', [$purchase_order->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Cancel</a>
                                            @endif

                                            @if ($purchase_order->status == PurchaseOrderStatus::APPROVED)
                                                <a href="#" data-href="{{ route('internals.purchase-orders.cancel', [$purchase_order->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Cancel</a>
                                            @endif

                                            @if ($purchase_order->status == PurchaseOrderStatus::CHECKING_FOR_GRPO)
                                                <a href="#" data-href="{{ route('internals.purchase-orders.cancel', [$purchase_order->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Cancel</a>
                                            @endif

                                            @if ($purchase_order->status == PurchaseOrderStatus::FOR_APPROVAL)
                                                @if (auth()->user()->role == 'Super Admin')
                                                    <a href="#" data-href="{{ route('internals.purchase-orders.approve', [$purchase_order->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Approve</a> | 

                                                    <a href="#" data-href="{{ route('internals.purchase-orders.disapprove', [$purchase_order->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Disapprove</a> | 

                                                    <a href="#" data-href="{{ route('internals.purchase-orders.cancel', [$purchase_order->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Cancel</a>
                                                @endif

                                                @if ($purchase_order->created_by_user_id != auth()->user()->id)
                                                    <a href="#" data-href="{{ route('internals.purchase-orders.approve', [$purchase_order->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Approve</a> | 

                                                    <a href="#" data-href="{{ route('internals.purchase-orders.disapprove', [$purchase_order->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Disapprove</a> | 

                                                    <a href="#" data-href="{{ route('internals.purchase-orders.cancel', [$purchase_order->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Cancel</a>
                                                @endif
                                            @endif

                                            @if ($purchase_order->status == PurchaseOrderStatus::CANCELLED)
                                                <a href="#" data-href="{{ route('internals.purchase-orders.recover', [$purchase_order->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Recover</a>
                                            @endif
                                        </div>
                                    </td>
                                    <td id="compact-table">P{{ number_format($orders_total, 2) }}</td>
                                    <td id="compact-table"><i class="material-icons icon-16pt mr-1 text-muted">business</i> {{ $purchase_order->supplier->name }}</td>
                                    <td id="compact-table"><i class="material-icons icon-16pt mr-1 text-muted">business</i> {{ $purchase_order->branch->name }}</td>
                                    <td id="compact-table"><i class="material-icons icon-16pt mr-1 text-muted">face</i> {{ $purchase_order->created_by_user->firstname }} {{ $purchase_order->created_by_user->lastname }}</td>
                                    <td id="compact-table">
                                        @if ($purchase_order->approved_by_user)
                                            <i class="material-icons icon-16pt mr-1 text-muted">face</i> {{ $purchase_order->approved_by_user->firstname }} {{ $purchase_order->approved_by_user->lastname }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($purchase_order->status == PurchaseOrderStatus::ON_PROCESS)
                                            <div class="badge badge-warning ml-2">ON PROCESS</div>
                                        @elseif ($purchase_order->status == PurchaseOrderStatus::CHECKING_FOR_GRPO)
                                            <div class="badge badge-success ml-2">CHECKING FOR GRPO</div>
                                        @elseif ($purchase_order->status == PurchaseOrderStatus::READY_FOR_GRPO)
                                            <div class="badge badge-success ml-2">READY FOR GRPO</div>
                                        @elseif ($purchase_order->status == PurchaseOrderStatus::DONE)
                                            <div class="badge badge-success ml-2">DONE</div>
                                        @elseif ($purchase_order->status == PurchaseOrderStatus::FOR_APPROVAL)
                                            <div class="badge badge-success ml-2">FOR APPROVAL</div>
                                        @elseif ($purchase_order->status == PurchaseOrderStatus::APPROVED)
                                            <div class="badge badge-success ml-2">APPROVED</div>
                                        @elseif ($purchase_order->status == PurchaseOrderStatus::DISAPPROVED)
                                            <div class="badge badge-danger ml-2">DISAPPROVED</div>
                                        @elseif ($purchase_order->status == PurchaseOrderStatus::CANCELLED)
                                            <div class="badge badge-danger ml-2">CANCELLED</div>
                                        @endif
                                    </td>
                                    <td id="compact-table"><i class="material-icons icon-16pt text-muted mr-1">today</i> {{ $purchase_order->created_at->format('M d Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if (count($purchase_orders) <= 0)
                        <div style="padding: 20px">
                            <center><i class="material-icons icon-16pt mr-1 text-muted">assignment</i> No record/s found</center>
                        </div>
                    @endif
                </div>
            </div>
            {{ $purchase_orders->links() }}
        </div>
    </div>
</div>


@include('layouts.auth.footer')