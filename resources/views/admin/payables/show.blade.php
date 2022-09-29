@include('layouts.auth.header')
@php
    use App\Models\Order;
    use App\Models\OrderStatus;
    use App\Models\PayableStatus;
    use App\Models\PurchaseOrder;
    use App\Models\PurchaseOrderStatus;
@endphp

<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Payables</li>
                </ol>
            </nav>
            <h1 class="m-0">Payables</h1>
        </div>
        
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')
    @include('layouts.partials.payables.top-tabs')

    <form action="{{ route('accounting.payables.search') }}" method="post">
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
                                        @if (old('status') == PayableStatus::PENDING)
                                            <option value="{{ old('status') }}">Pending</option>
                                        @endif

                                        @if (old('status') == PayableStatus::PAID)
                                            <option value="{{ old('status') }}">Paid</option>
                                        @endif

                                        @if (old('status') == PayableStatus::UNPAID)
                                            <option value="{{ old('status') }}">Unpaid</option>
                                        @endif
                                    @endif
                                @endif
                                <option value="*">All</option>
                                <option value="{{ PayableStatus::PENDING }}">Pending</option>
                                <option value="{{ PayableStatus::PAID }}">Paid</option>
                                <option value="{{ PayableStatus::UNPAID }}">Unpaid</option>
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
                        <label><a href="{{ route('accounting.payables') }}" id="no-underline">Clear Filters</a></label>
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
                    <h4 class="card-header__title flex m-0">Payables</h4>
                    <a href="#" data-href="{{ route('accounting.payables.database.update') }}" data-toggle="modal" data-target="#confirm-action">
                        <button class="btn btn-light"><i class="fa fa-database" id="margin-right"></i>Update Database</button>
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table"></th>
                                <th id="compact-table">PO#</th>
                                <th id="compact-table">Check #</th>
                                <th id="compact-table">Supplier</th>
                                <th id="compact-table">MOP</th>
                                <th id="compact-table">Grand Total</th>
                                <th id="compact-table">Date Issued</th>
                                <th id="compact-table">Date Released</th>
                                <th id="compact-table">Due Date</th>
                                <th id="compact-table">Status</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                            @foreach($payables as $payable)
                            @php
                                $purchase_order = PurchaseOrder::find($payable->purchase_order->id);
                                $orders_total = Order::where('purchase_order_id', $purchase_order->id)
                                                    ->where('status', OrderStatus::ACTIVE)
                                                    ->sum('total');
                            @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex align-items-center">
                                                @if ($payable->image)
                                                    <a href="{{ url($payable->image) }}" target="_blank">
                                                        <img src="{{ url($payable->image) }}" width="100px">
                                                    </a>
                                                @else
                                                    <img src="{{ url(env('APP_ICON')) }}" width="40px" style="margin-right: 7px;">
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td id="compact-table">
                                        <b># {{ $purchase_order->reference_number }}</b>
                                        <div class="d-flex">
                                            <a href="{{ route('internals.goods-receipts.view', [$payable->goods_receipt->id]) }}" id="table-letter-margin">View</a> |  

                                            @if (! $payable->date_released)
                                                <a href="#" data-toggle="modal" data-target="#date-released-{{ $payable->id }}" id="space-table">Release Date</a> | 
                                            @endif

                                            @if ($payable->status != PayableStatus::INACTIVE)
                                                <a href="#" data-href="{{ route('accounting.payables.delete', [$payable->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Delete</a>
                                            @endif
                                        </div>
                                    </td>
                                    <td id="compact-table">{{ $payable->check_number }}</td>
                                    <td id="compact-table">{{ $payable->purchase_order->supplier->name }}</td>
                                    <td id="compact-table">{{ $payable->mop }}</td>
                                    <td id="compact-table">P{{ number_format($payable->price, 2) }}</td>
                                    <td id="compact-table"><i class="material-icons icon-16pt text-muted mr-1">today</i> {{ date("M d Y", strtotime($payable->date_issued)) }}</td>
                                    <td id="compact-table">
                                        @if ($payable->date_released)
                                            <i class="material-icons icon-16pt text-muted mr-1">today</i> {{ date("M d Y", strtotime($payable->date_released)) }}
                                        @endif
                                    </td>
                                    <td id="compact-table"><i class="material-icons icon-16pt text-muted mr-1">today</i> {{ date("M d Y", strtotime($payable->due_date)) }}</td>
                                    
                                    <td>
                                        @if ($payable->status == PayableStatus::PENDING)
                                            <div class="badge badge-warning ml-2">PENDING</div>
                                        @elseif ($payable->status == PayableStatus::PAID)
                                            <div class="badge badge-success ml-2">PAID</div>
                                        @elseif ($payable->status == PayableStatus::UNPAID)
                                            <div class="badge badge-danger ml-2">UNPAID</div>
                                        @elseif ($payable->status == PayableStatus::INACTIVE)
                                            <div class="badge badge-danger ml-2">INACTIVE</div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if (count($payables) <= 0)
                        <div style="padding: 20px">
                            <center><i class="material-icons icon-16pt mr-1 text-muted">assignment</i> No record/s found</center>
                        </div>
                    @endif
                </div>
            </div>
            {{ $payables->links() }}
        </div>
    </div>
</div>


@include('layouts.auth.footer')