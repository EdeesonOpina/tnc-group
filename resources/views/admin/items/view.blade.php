@include('layouts.auth.header')
@php
    use App\Models\ItemPhotoStatus;
    use App\Models\ItemSerialNumberStatus;
@endphp
<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.items') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.items') }}">Items</a></li>
                    <li class="breadcrumb-item active" aria-current="page">View</li>
                </ol>
            </nav>
            <h1 class="m-0">{{ $item->name }}</h1>
        </div>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header card-header-large bg-white">
                    <div class="row">
                        <div class="col">
                            <strong>Item Serial Number/s</strong>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table">#ID</th>
                                <th id="compact-table">Code</th>
                                <th id="compact-table">Sold To</th>
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
                                    <td id="compact-table">
                                        @if ($item_serial_number->payment)
                                            <i class="material-icons icon-16pt mr-1 text-muted">receipt</i> <a href="{{ route('accounting.payments.view', [$item_serial_number->payment->so_number]) }}">{{ $item_serial_number->payment->so_number }}</a>
                                            <br>
                                            <i class="material-icons icon-16pt mr-1 text-muted">face</i> {{ $item_serial_number->payment->user->firstname }} {{ $item_serial_number->payment->user->lastname }}
                                        @endif
                                    </td>
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
            <br>
            <div class="card">
                <div class="card-header card-header-large bg-white">
                    <div class="row">
                        <div class="col">
                            <strong>Item Photo/s</strong>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table">#ID</th>
                                <th id="compact-table"></th>
                                <th id="compact-table">Name</th>
                                <th id="compact-table">Status</th>
                                <th id="compact-table">Created At</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                            @foreach($item_photos as $item_photo)
                                <tr>
                                    <td><div class="badge badge-light">#{{ $item_photo->id }}</div></td>
                                    <td id="compact-table">
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex align-items-center">
                                                @if ($item_photo->image)
                                                    <img src="{{ url($item_photo->image) }}" width="100px">
                                                @else
                                                    <img src="{{ url(env('APP_ICON')) }}" width="40px" style="margin-right: 7px;">
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td id="compact-table">
                                        <strong>{{ $item_photo->name }}</strong>
                                        <div class="d-flex">
                                            <a href="{{ url($item_photo->image) }}" style="margin-right: 7px" target="_blank">View</a> | 
                                            <a href="#" data-toggle="modal" data-target="#edit-item-photo-{{ $item_photo->id }}" id="space-table" style="margin-right: 7px">Edit</a> | 
                                            @if ($item_photo->status == ItemPhotoStatus::ACTIVE)
                                                <a href="#" data-href="{{ route('admin.items.photos.delete', [$item->id, $item_photo->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Delete</a>
                                            @endif

                                            @if ($item_photo->status == ItemPhotoStatus::INACTIVE)
                                                <a href="#" data-href="{{ route('admin.items.photos.recover', [$item->id, $item_photo->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Recover</a>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if ($item_photo->status == ItemPhotoStatus::ACTIVE)
                                            <div class="badge badge-success ml-2">Active</div>
                                        @elseif ($item_photo->status == ItemPhotoStatus::INACTIVE)
                                            <div class="badge badge-danger ml-2">Inactive</div>
                                        @endif
                                    </td>
                                    <td id="compact-table"><i class="material-icons icon-16pt text-muted mr-1">today</i> {{ $item_photo->created_at->format('M d Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if (count($item_photos) <= 0)
                        <div style="padding: 20px">
                            <center><i class="material-icons icon-16pt mr-1 text-muted">assignment</i> No record/s found</center>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col">
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