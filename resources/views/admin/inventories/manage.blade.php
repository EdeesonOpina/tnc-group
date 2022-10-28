@include('layouts.auth.header')
@php
    use App\Models\ItemStatus;
    use App\Models\ItemSerialNumber;
    use App\Models\ItemSerialNumberStatus;
@endphp

<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('internals.inventories') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('internals.inventories.manage', [auth()->user()->company->id]) }}">Inventory</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $company->name }}</li>
                </ol>
            </nav>
            <h1 class="m-0">{{ $company->name }} Inventory</h1>
        </div>
        <a href="{{ route('internals.inventories.print', [auth()->user()->company->id]) }}">
                            <button type="button" class="btn btn-light" id="margin-right"><i class="fa fa-print" id="margin-right"></i>Print</button>
                        </a>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')

    <form action="{{ route('internals.inventories.items.search', [$company->id]) }}" method="post">
        {{ csrf_field() }}
        <div class="card card-form d-flex flex-column flex-sm-row">
            <div class="card-form__body card-body-form-group flex">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>Barcode</label>
                            <input name="barcode" type="text" class="form-control" placeholder="Scan or enter barcode" value="{{ old('barcode') }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Name</label>
                            <input name="name" type="text" class="form-control" placeholder="Search by name" value="{{ old('name') }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" data-toggle="select">
                                @if (old('status'))
                                    @if (old('status') != '*')
                                        @if (old('status') == ItemStatus::ACTIVE)
                                            <option value="{{ old('status') }}">Active</option>
                                        @endif

                                        @if (old('status') == ItemStatus::INACTIVE)
                                            <option value="{{ old('status') }}">Inactive</option>
                                        @endif
                                    @endif
                                @endif
                                <option value="*">All</option>
                                <option value="{{ ItemStatus::ACTIVE }}">Active</option>
                                <option value="{{ ItemStatus::INACTIVE }}">Inactive</option>
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
                        <label><a href="{{ route('internals.inventories.manage', [$company->id]) }}" id="no-underline">Clear Filters</a></label>
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
                    <h4 class="card-header__title flex m-0">{{ $company->name }} Inventory</h4>
                    <div data-toggle="flatpickr" data-flatpickr-wrap="true" data-flatpickr-static="true" data-flatpickr-mode="range" data-flatpickr-alt-format="d/m/Y" data-flatpickr-date-format="d/m/Y">
                        
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table">#ID</th>
                                <th id="compact-table"></th>
                                <th id="compact-table">Name</th>
                                <!-- <th id="compact-table">Price</th>
                                <th id="compact-table">Agent Price</th>
                                <th id="compact-table">Discount</th> -->
                                <th id="compact-table">Qty</th>
                                <th id="compact-table">S/N available</th>
                                <th id="compact-table">Status</th>
                                <th id="compact-table">Created At</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                            @foreach($inventories as $inventory)
                            @php
                                $item_serial_numbers_count = ItemSerialNumber::where('item_id', $inventory->item->id)
                                                                        ->where('status', ItemSerialNumberStatus::AVAILABLE)
                                                                        ->count();
                            @endphp
                                <tr>
                                    <td><div class="badge badge-light">#{{ $inventory->id }}</div></td>
                                    <td id="compact-table">
                                        <div class="d-flex">
                                            @if ($inventory->item->image)
                                                <img src="{{ url($inventory->item->image) }}" width="40">
                                            @else
                                                <img src="{{ url(env('BIG_FOUR_ICON')) }}" width="40px" style="margin-right: 7px;">
                                            @endif
                                        </div>
                                    </td>
                                    <td id="compact-table">
                                        <strong>Item ID:</strong><div class="badge badge-light">#{{ $inventory->item->id }}</div><br>
                                        <strong>Barcode: {{ $inventory->item->barcode }}</strong><br>
                                        <strong>{{ $inventory->item->name }}</strong><br>
                                        {{ $inventory->item->brand->name }}<br>
                                        {{ $inventory->item->category->name }}
                                        <div class="d-flex">
                                            <!-- @if (auth()->user()->role == 'Super Admin' || auth()->user()->role == 'Admin' || auth()->user()->role == 'Accountant')
                                                <a href="#" data-toggle="modal" data-target="#set-price-{{ $inventory->item->id }}" style="margin-right: 7px">Set Price</a> | 
                                                <a href="#" data-toggle="modal" data-target="#set-discount-{{ $inventory->item->id }}" id="space-table">Set Discount</a> | 
                                            @endif -->
                                            <a href="#" data-toggle="modal" data-target="#set-barcode-{{ $inventory->item->id }}" id="margin-right">Set Barcode</a> | 
                                            <a href="{{ route('internals.inventories.items.serial-numbers', [$inventory->item->id]) }}" id="space-table">S/N</a>
                                        </div>
                                    </td>
                                    <!-- <td id="compact-table">
                                        <a href="#" data-toggle="modal" data-target="#set-price-{{ $inventory->item->id }}">
                                            P{{ number_format($inventory->price, 2) }}
                                        </a>
                                    </td>
                                    <td id="compact-table">
                                        <a href="#" data-toggle="modal" data-target="#set-price-{{ $inventory->item->id }}">
                                            P{{ number_format($inventory->agent_price, 2) }}
                                        </a>
                                    </td>
                                    <td id="compact-table">
                                        <a href="#" data-toggle="modal" data-target="#set-discount-{{ $inventory->item->id }}">
                                            P{{ number_format($inventory->discount, 2) }}
                                        </a>
                                    </td> -->
                                    <td id="compact-table">{{ $inventory->qty }}</td>
                                    <td id="compact-table">
                                        <a href="{{ route('internals.inventories.items.serial-numbers', [$inventory->item->id]) }}">
                                            {{ $item_serial_numbers_count }}
                                        </a>
                                    </td>
                                    <td>
                                        @if ($inventory->item->status == ItemStatus::ACTIVE)
                                            <div class="badge badge-success ml-2">Active</div>
                                        @elseif ($inventory->item->status == ItemStatus::INACTIVE)
                                            <div class="badge badge-danger ml-2">Inactive</div>
                                        @endif
                                    </td>
                                    <td id="compact-table"><i class="material-icons icon-16pt text-muted mr-1">today</i> {{ $inventory->item->created_at->format('M d Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if (count($inventories) <= 0)
                        <div style="padding: 20px">
                            <center><i class="material-icons icon-16pt mr-1 text-muted">assignment</i> No record/s found</center>
                        </div>
                    @endif
                </div>
            </div>
            {{ $inventories->links() }}
        </div>
    </div>
</div>


@include('layouts.auth.footer')