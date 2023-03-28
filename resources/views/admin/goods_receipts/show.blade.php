@include('layouts.auth.header')
@php
    use App\Models\Order;
    use App\Models\OrderStatus;
    use App\Models\GoodsReceiptStatus;
@endphp

<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Goods Receipts</li>
                </ol>
            </nav>
            <h1 class="m-0">Goods Receipts</h1>
        </div>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')
    @include('layouts.partials.top-tabs')

    <form action="{{ route('internals.goods-receipts.search') }}" method="post">
        {{ csrf_field() }}
        <div class="card card-form d-flex flex-column flex-sm-row">
            <div class="card-form__body card-body-form-group flex">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>GRPO#</label>
                            <input name="reference_number" type="text" class="form-control" placeholder="Search by GRPO number" value="{{ old('reference_number') }}">
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
                                        @if (old('status') == GoodsReceiptStatus::FULFILLING)
                                            <option value="{{ old('status') }}">Fulfilling</option>
                                        @endif

                                        @if (old('status') == GoodsReceiptStatus::CLEARED)
                                            <option value="{{ old('status') }}">Cleared</option>
                                        @endif

                                        @if (old('status') == GoodsReceiptStatus::CANCELLED)
                                            <option value="{{ old('status') }}">Cancelled</option>
                                        @endif
                                    @endif
                                @endif
                                <option value="*">All</option>
                                <option value="{{ GoodsReceiptStatus::FULFILLING }}">Fulfilling</option>
                                <option value="{{ GoodsReceiptStatus::CLEARED }}">Cleared</option>
                                <option value="{{ GoodsReceiptStatus::CANCELLED }}">Cancelled</option>

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
                        <label><a href="{{ route('internals.goods-receipts') }}" id="no-underline">Clear Filters</a></label>
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
                    <h4 class="card-header__title flex m-0">Goods Receipts</h4>
                    <div data-toggle="flatpickr" data-flatpickr-wrap="true" data-flatpickr-static="true" data-flatpickr-mode="range" data-flatpickr-alt-format="d/m/Y" data-flatpickr-date-format="d/m/Y">
                        
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table">GRPO#</th>
                                <th id="compact-table">PO#</th>
                                <th id="compact-table">Amount Paid</th>
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
                            @foreach($goods_receipts as $goods_receipt)
                            @php
                                $orders_total = Order::where('goods_receipt_id', $goods_receipt->id)
                                                    ->where('status', OrderStatus::ACTIVE)
                                                    ->sum('total');
                            @endphp
                                <tr>
                                    <td id="compact-table">
                                        <b># {{ $goods_receipt->reference_number }}</b>
                                        <div class="d-flex">
                                            <a href="{{ route('internals.goods-receipts.view', [$goods_receipt->id]) }}" id="table-letter-margin" target="_blank">View</a> |  

                                            @if ($goods_receipt->status == GoodsReceiptStatus::FULFILLING)
                                                @if (auth()->user()->role == 'Super Admin' || auth()->user()->role == 'Admin' || auth()->user()->role == 'Stockman' || auth()->user()->role == 'Programs' || $goods_receipt->created_by_user->id == auth()->user()->id)
                                                    <a href="{{ route('internals.goods-receipts.manage', [$goods_receipt->id]) }}" id="space-table">Manage</a> | 
                                                @endif

                                                <a href="#" data-href="{{ route('internals.goods-receipts.cancel', [$goods_receipt->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Cancel</a>
                                            @endif

                                            @if ($goods_receipt->status == GoodsReceiptStatus::CLEARED)
                                                @if (auth()->user()->role == 'Super Admin' || auth()->user()->role == 'Admin')
                                                    <a href="#" data-href="{{ route('internals.goods-receipts.cancel', [$goods_receipt->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Cancel</a>
                                                @endif 
                                            @endif
                                        </div>
                                    </td>
                                    <td id="compact-table">
                                        <b># {{ $goods_receipt->purchase_order->reference_number }}</b>
                                    </td>
                                    <td id="compact-table">P{{ number_format($orders_total, 2) }}</td>
                                    <td id="compact-table"><i class="material-icons icon-16pt mr-1 text-muted">business</i> {{ $goods_receipt->purchase_order->supplier->name }}</td>
                                    <td id="compact-table"><i class="material-icons icon-16pt mr-1 text-muted">business</i> {{ $goods_receipt->purchase_order->company->name }}</td>
                                    <td id="compact-table"><i class="material-icons icon-16pt mr-1 text-muted">face</i> {{ $goods_receipt->purchase_order->created_by_user->firstname }} {{ $goods_receipt->purchase_order->created_by_user->lastname }}</td>
                                    <td id="compact-table">
                                        @if ($goods_receipt->purchase_order->approved_by_user)
                                            <i class="material-icons icon-16pt mr-1 text-muted">face</i> {{ $goods_receipt->purchase_order->approved_by_user->firstname }} {{ $goods_receipt->purchase_order->approved_by_user->lastname }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($goods_receipt->status == GoodsReceiptStatus::FULFILLING)
                                            <div class="badge badge-warning ml-2">FULFILLING</div>
                                        @elseif ($goods_receipt->status == GoodsReceiptStatus::CLEARED)
                                            <div class="badge badge-success ml-2">CLEARED</div>
                                        @elseif ($goods_receipt->status == GoodsReceiptStatus::CANCELLED)
                                            <div class="badge badge-danger ml-2">CANCELLED</div>
                                        @endif
                                    </td>
                                    <td id="compact-table"><i class="material-icons icon-16pt text-muted mr-1">today</i> {{ $goods_receipt->created_at->format('M d Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if (count($goods_receipts) <= 0)
                        <div style="padding: 20px">
                            <center><i class="material-icons icon-16pt mr-1 text-muted">assignment</i> No record/s found</center>
                        </div>
                    @endif
                </div>
            </div>
            {{ $goods_receipts->links() }}
        </div>
    </div>
</div>


@include('layouts.auth.footer')