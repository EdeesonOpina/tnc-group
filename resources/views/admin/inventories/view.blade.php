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
        </div>
        <h1 class="m-0">{{ $company->name }} Inventory</h1>
        <a href="{{ route('internals.inventories.print', [$company->id]) }}">
            <button type="button" class="btn btn-light" id="margin-right"><i class="fa fa-print" id="margin-right"></i>Print</button>
        </a>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')

    <div class="row">
        <div class="col">
            <div id="spaced-card" class="card card-body">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Company</h6>
                            {{ $company->name }}
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <h6>Email Address</h6>
                            {{ $company->email }}
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Contact Person</h6>
                            {{ $company->person }}
                        </div>
                    </div>
                    <div class="col">
                        &nbsp;
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Phone</h6>
                            {{ $company->phone }}
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <h6>Mobile</h6>
                            {{ $company->mobile }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Line Address 1</h6>
                            {{ $company->line_address_1 }}
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <h6>Line Address 2</h6>
                            {{ $company->line_address_2 }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
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
                                    <td><div class="badge badge-light">#{{ $inventory->item->id }}</div></td>
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
                                        <strong>Barcode: {{ $inventory->item->barcode }}</strong><br>
                                        <strong>{{ $inventory->item->name }}</strong><br>
                                        {{ $inventory->item->brand->name }}<br>
                                        {{ $inventory->item->category->name }}
                                    </td>
                                    <!-- <td id="compact-table">P{{ number_format($inventory->price, 2) }}</td>
                                    <td id="compact-table">P{{ number_format($inventory->agent_price, 2) }}</td>
                                    <td id="compact-table">P{{ number_format($inventory->discount, 2) }}</td> -->
                                    <td id="compact-table">{{ $inventory->qty }}</td>
                                    <td id="compact-table">{{ $item_serial_numbers_count }}</td>
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