@include('layouts.auth.header')
@php
    use App\Models\ItemPhotoStatus;
@endphp

<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.items') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.items') }}">Items</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Photos</li>
                </ol>
            </nav>
            <h1 class="m-0">Photos</h1>
        </div>
        <a href="#" data-toggle="modal" data-target="#add-item-photo" id="space-table" class="btn btn-success"><i class="material-icons">add</i> Add Photo</a>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')

    <form action="{{ route('admin.items.photos.search', [$item->id]) }}" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="item_id" value="{{ $item->id }}">
        <div class="card card-form d-flex flex-column flex-sm-row">
            <div class="card-form__body card-body-form-group flex">
                <div class="row">
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
                                        @if (old('status') == ItemPhotoStatus::ACTIVE)
                                            <option value="{{ old('status') }}">Active</option>
                                        @endif

                                        @if (old('status') == ItemPhotoStatus::INACTIVE)
                                            <option value="{{ old('status') }}">Inactive</option>
                                        @endif
                                    @endif
                                @endif
                                <option value="*">All</option>
                                <option value="{{ ItemPhotoStatus::ACTIVE }}">Active</option>
                                <option value="{{ ItemPhotoStatus::INACTIVE }}">Inactive</option>
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
                        <label><a href="{{ route('admin.items.photos', [$item->id]) }}" id="no-underline">Clear Filters</a></label>
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
                    <h4 class="card-header__title flex m-0">Item Photos</h4>
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
            {{ $item_photos->links() }}
        </div>
        <div class="col-md-4">
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
        </div>
    </div>
</div>


@include('layouts.auth.footer')