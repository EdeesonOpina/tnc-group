@include('layouts.auth.header')
@php
    use App\Models\Order;
    use App\Models\ItemStatus;
    use App\Models\GoodsReceiptStatus;

    $can_finalize = true;

    foreach($orders as $order) {
        if ($order->qty + $order->free_qty != $order->received_qty)
            $can_finalize = false;
    }
@endphp
<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('internals.goods-receipts') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('internals.goods-receipts') }}">Goods Receipts</a></li>
                    <li class="breadcrumb-item active" aria-current="page">View</li>
                </ol>
            </nav>
            <h1 class="m-0">Goods Receipt</h1>
        </div>
        @if (auth()->user()->role == 'Super Admin' || auth()->user()->role == 'Admin' || auth()->user()->role == 'Accountant' || auth()->user()->role == 'Sales' || auth()->user()->role == 'Stockman' || auth()->user()->role == 'Programs')
            @if ($goods_receipt->status == GoodsReceiptStatus::CLEARED)
                <a href="{{ route('internals.exports.goods-receipts.print', [$goods_receipt->id]) }}">
                    <button type="button" class="btn btn-light" id="margin-right"><i class="fa fa-print" id="margin-right"></i>Print</button>
                </a>
                <a href="{{ route('internals.exports.goods-receipts.excel', [$goods_receipt->id]) }}">
                    <button type="button" class="btn btn-success" id="margin-right"><i class="fa fa-file-excel" id="margin-right"></i>Create Excel</button>
                </a>
                <a href="{{ route('internals.exports.goods-receipts.pdf', [$goods_receipt->id]) }}">
                    <button type="button" class="btn btn-danger"><i class="fa fa-file-pdf" id="margin-right"></i>Create PDF</button>
                </a>
            @endif

            @if($can_finalize)
                @if (count($delivery_receipts) > 0)
                    @if ($goods_receipt->status == GoodsReceiptStatus::FULFILLING)
                        <a href="#" data-href="{{ route('internals.goods-receipts.clear', [$goods_receipt->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table" class="btn btn-success"><i class="material-icons">check</i> Mark As Cleared</a>
                    @endif
                @else
                    <button class="btn btn-success" disabled><i class="material-icons">check</i> Mark As Cleared</button>
                @endif
            @else
                <button class="btn btn-success" disabled><i class="material-icons">check</i> Mark As Cleared</button>
            @endif
        @endif
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header card-header-large bg-white">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Delivery Receipt/s</strong>
                        </div>
                        <div class="col">
                                @if (count($delivery_receipts) > 0)
                                    @foreach ($delivery_receipts as $delivery_receipt)
                                        <strong>{{ $delivery_receipt->delivery_receipt_number }}</strong>
                                        <br>
                                    @endforeach
                                    <br>
                                @else
                                    <i class="material-icons">info</i> Not assigned yet
                                    <br><br>
                                @endif
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table"></th>
                                <th id="compact-table">Name</th>
                                <th id="compact-table">Qty</th>
                                <th id="compact-table">Free Qty</th>
                                <th id="compact-table">Expected Qty</th>
                                <th id="compact-table">Received Qty</th>
                                <th id="compact-table">Received By</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                            @foreach($orders as $order)
                                <tr>
                                    <td id="compact-table">
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex align-items-center">
                                                @if ($order->supply->item->image)
                                                    <img src="{{ url($order->supply->item->image) }}" width="40px">
                                                @else
                                                    <img src="{{ url(env('APP_ICON')) }}" width="40px" style="margin-right: 7px;">
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td id="compact-table">
                                        <b>Supplier Price:</b> P{{ number_format($order->price, 2) }}<br>
                                        <b>Total:</b> P{{ number_format($order->total, 2) }}<br>
                                        <b>{{ $order->item->name }}</b><br>
                                        {{ $order->item->brand->name }}<br>
                                        {{ $order->item->category->name }}
                                    </td>
                                    <td id="compact-table">
                                        {{ $order->qty }}
                                    </td>
                                    <td id="compact-table">
                                        {{ $order->free_qty }}
                                    </td>
                                    <td id="compact-table">
                                        {{ $order->qty + $order->free_qty }}
                                    </td>
                                    <td id="compact-table">
                                        <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">store_mall_directory</i><b>{{ $order->received_qty }}</b>
                                    </td>
                                    <td id="compact-table">
                                        @if ($order->performed_by_user)
                                            <i class="material-icons icon-16pt mr-1 text-muted">face</i> {{ $order->performed_by_user->firstname }} {{ $order->performed_by_user->lastname }}
                                        @endif
                                    </td>
                                </tr>

                            @if (($order->qty + $order->free_qty) != $order->received_qty)
                                @php 
                                    $can_finalize = false;
                                @endphp
                            @endif
                            @endforeach
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
                            <h6>Goods Receipt Order #</h6>
                            <span class="badge badge-success" id="large-font">{{ $goods_receipt->reference_number }}</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Purchase Order #</h6>
                            <a href="{{ route('internals.purchase-orders.view', [$goods_receipt->purchase_order->id]) }}" class="no-underline">
                            <span class="badge badge-warning" id="large-font">{{ $goods_receipt->purchase_order->reference_number }}</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Status</h6>
                            @if ($goods_receipt->status == GoodsReceiptStatus::FULFILLING)
                                <span class="badge badge-warning" id="large-font">FULFILLING</span>
                            @elseif ($goods_receipt->status == GoodsReceiptStatus::CLEARED)
                                <span class="badge badge-success" id="large-font">CLEARED</span>
                            @elseif ($goods_receipt->status == GoodsReceiptStatus::CANCELLED)
                                <span class="badge badge-danger" id="large-font">CANCELLED</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Supplier</h6>
                            {{ $goods_receipt->purchase_order->supplier->name }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Destination</h6>
                            {{ $goods_receipt->purchase_order->company->name }}
                        </div>
                    </div>
                </div>
            </div>
            <br>
        </div>
    </div>
</div>
@include('layouts.auth.footer')