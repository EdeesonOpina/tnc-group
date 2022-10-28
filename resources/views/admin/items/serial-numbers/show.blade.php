@include('layouts.auth.header')
@php
    use App\Models\ItemSerialNumberStatus;
@endphp
<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('internals.inventories') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('internals.inventories') }}">Inventories</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('internals.inventories') }}">Item</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Serial Number/s</li>
                </ol>
            </nav>
            <h1 class="m-0">Item Serial Number/s</h1>
        </div>
        <a href="#" data-toggle="modal" data-target="#add-serial-number" id="space-table" class="btn btn-success"><i class="material-icons">add</i> Add S/N</a>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')

    <form action="{{ route('internals.inventories.items.serial-numbers.search', $item->id) }}" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="item_id" value="{{ $item->id }}">
        <div class="card card-form d-flex flex-column flex-sm-row">
            <div class="card-form__body card-body-form-group flex">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>Serial #</label>
                            <input name="code" type="text" class="form-control" placeholder="Search by serial number" value="{{ old('code') }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" data-toggle="select">
                                @if (old('status'))
                                    @if (old('status') != '*')
                                        @if (old('status') == ItemSerialNumberStatus::AVAILABLE)
                                            <option value="{{ old('status') }}">Available</option>
                                        @endif

                                        @if (old('status') == ItemSerialNumberStatus::FOR_CHECKOUT)
                                            <option value="{{ old('status') }}">For Checkout</option>
                                        @endif

                                        @if (old('status') == ItemSerialNumberStatus::SOLD)
                                            <option value="{{ old('status') }}">Sold</option>
                                        @endif

                                        @if (old('status') == ItemSerialNumberStatus::FLOATING)
                                            <option value="{{ old('status') }}">Floating</option>
                                        @endif

                                        @if (old('status') == ItemSerialNumberStatus::INACTIVE)
                                            <option value="{{ old('status') }}">Inactive</option>
                                        @endif
                                    @endif
                                @endif
                                <option value="*">All</option>
                                <option value="{{ ItemSerialNumberStatus::AVAILABLE }}">Available</option>
                                <option value="{{ ItemSerialNumberStatus::FOR_CHECKOUT }}">For Checkout</option>
                                <option value="{{ ItemSerialNumberStatus::SOLD }}">Sold</option>
                                <option value="{{ ItemSerialNumberStatus::FLOATING }}">Floating</option>
                                <option value="{{ ItemSerialNumberStatus::INACTIVE }}">Inactive</option>

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
                        <label><a href="{{ route('internals.inventories.items.serial-numbers', [$item->id]) }}" id="no-underline">Clear Filters</a></label>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn bg-white border-left border-top border-top-sm-0 rounded-top-0 rounded-top-sm rounded-left-sm-0"><i class="material-icons text-primary icon-20pt">search</i></button>
        </div>
    </form>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header card-header-large bg-white">
                    <div class="row">
                        <div class="col">
                            <strong>Serial Number/s</strong>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table">#ID</th>
                                <th id="compact-table">Code</th>
                                <th id="compact-table">DR#</th>
                                <th id="compact-table">GRPO#</th>
                                <th id="compact-table">Supplier</th>
                                <th id="compact-table">Status</th>
                                <th id="compact-table">Created At</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                            @foreach($item_serial_numbers as $item_serial_number)
                                <tr>
                                    <td><div class="badge badge-light">#{{ $item_serial_number->id }}</div></td>
                                    <td id="compact-table">
                                        <strong>{{ $item_serial_number->code }}</strong><br>
                                        <a href="#" data-toggle="modal" data-target="#edit-serial-number-{{ $item_serial_number->id }}">Edit</a> | 

                                        @if ($item_serial_number->status == ItemSerialNumberStatus::AVAILABLE)
                                            <a href="#" data-href="{{ route('internals.inventories.items.serial-numbers.delete', [$item->id, $item_serial_number->id]) }}" data-toggle="modal" data-target="#confirm-action">Delete</a>
                                        @endif

                                        @if ($item_serial_number->status == ItemSerialNumberStatus::FOR_CHECKOUT)
                                            <a href="#" data-href="{{ route('internals.inventories.items.serial-numbers.available', [$item->id, $item_serial_number->id]) }}" data-toggle="modal" data-target="#confirm-action">Cancel</a> | 

                                            <a href="#" data-href="{{ route('internals.inventories.items.serial-numbers.delete', [$item->id, $item_serial_number->id]) }}" data-toggle="modal" data-target="#confirm-action">Delete</a>
                                        @endif

                                        @if ($item_serial_number->status == ItemSerialNumberStatus::SOLD)
                                            <a href="#" data-href="{{ route('internals.inventories.items.serial-numbers.floating', [$item->id, $item_serial_number->id]) }}" data-toggle="modal" data-target="#confirm-action">Floating</a> | 

                                            <a href="#" data-href="{{ route('internals.inventories.items.serial-numbers.revert', [$item->id, $item_serial_number->id]) }}" data-toggle="modal" data-target="#confirm-action">Revert</a> | 

                                            <a href="#" data-href="{{ route('internals.inventories.items.serial-numbers.delete', [$item->id, $item_serial_number->id]) }}" data-toggle="modal" data-target="#confirm-action">Delete</a>
                                        @endif

                                        @if ($item_serial_number->status == ItemSerialNumberStatus::INACTIVE)
                                            <a href="#" data-href="{{ route('internals.inventories.items.serial-numbers.recover', [$item->id, $item_serial_number->id]) }}" data-toggle="modal" data-target="#confirm-action">Recover</a>
                                        @endif

                                    </td>
                                    <td id="compact-table">{{ $item_serial_number->delivery_receipt->delivery_receipt_number ?? null }}</td>
                                    <td id="compact-table">
                                        @if ($item_serial_number->delivery_receipt)
                                            <a href="{{ route('internals.goods-receipts.view', [$item_serial_number->delivery_receipt->goods_receipt->id]) }}">
                                            {{ $item_serial_number->delivery_receipt->goods_receipt->reference_number ?? null }}
                                        </a>
                                        @endif
                                    </td>
                                    <td id="compact-table">{{ $item_serial_number->delivery_receipt->goods_receipt->purchase_order->supplier->name ?? null }}</td>
                                    <td>
                                        @if ($item_serial_number->status == ItemSerialNumberStatus::AVAILABLE)
                                            <div class="badge badge-success ml-2">AVAILABLE</div>
                                        @elseif ($item_serial_number->status == ItemSerialNumberStatus::FOR_CHECKOUT)
                                            <div class="badge badge-warning ml-2">FOR CHECKOUT</div>
                                        @elseif ($item_serial_number->status == ItemSerialNumberStatus::SOLD)
                                            <div class="badge badge-success ml-2">SOLD</div>
                                        @elseif ($item_serial_number->status == ItemSerialNumberStatus::FLOATING)
                                            <div class="badge badge-info ml-2">FLOATING</div>
                                        @elseif ($item_serial_number->status == ItemSerialNumberStatus::INACTIVE)
                                            <div class="badge badge-danger ml-2">INACTIVE</div>
                                        @endif
                                    </td>
                                    <td id="compact-table"><i class="material-icons icon-16pt text-muted mr-1">today</i> {{ $item_serial_number->created_at->format('M d Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div style="padding-top: 20px">
                        {{ $item_serial_numbers->links() }}
                    </div>

                    @if (count($item_serial_numbers) <= 0)
                        <div style="padding: 20px">
                            <center><i class="material-icons icon-16pt mr-1 text-muted">assignment</i> No record/s found</center>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col">
            <div id="spaced-card" class="card card-body">
                <h3>Quick Add Scan</h3>
                <br>
                    <form action="{{ route('internals.inventories.items.serial-numbers.create', [$item->id]) }}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                        <label>Serial Number</label><br>
                        <input type="text" name="code" class="form-control" placeholder="Scan or enter serial number" autofocus>
                        <br>
                        <label>Delivery Receipt Number</label><br>
                        <input type="text" name="delivery_receipt_number" class="form-control" placeholder="Enter delivery receipt number" value="{{ old('delivery_receipt_number') }}" required><br>
                    </form>
            </div>
            <br>
            <div id="spaced-card" class="card card-body">
                <h3>Item</h3>
                <br>
                <div class="row">
                    <div class="col">
                        @if ($item->image)
                            <img src="{{ url($item->image) }}" width="100%">
                        @else
                            <img src="{{ url(env('APP_ICON')) }}" width="100%" style="padding: 5%;">
                        @endif
                    </div>
                
                    <div class="col">
                        <div class="form-group">
                            <strong>BARCODE: {{ $item->barcode }}</strong><br>
                            <strong>{{ $item->name }}</strong><br>
                            {{ $item->category->name }}<br>
                            {{ $item->brand->name }}<br><br>
                            {!! QrCode::size(50)->generate(url(route('qr.items.view', [$item->id]))) !!}
                        </div>
                    </div>
                </div>
            </div>
            <br>
        </div>
    </div>
</div>
@include('layouts.auth.footer')