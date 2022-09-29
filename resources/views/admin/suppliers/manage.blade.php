@include('layouts.auth.header')
@php
    use App\Models\Supply;
    use App\Models\ItemStatus;
    use App\Models\SupplyStatus;
@endphp
<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.suppliers') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item">Suppliers</li>
                    <li class="breadcrumb-item active" aria-current="page">Add Supplier</li>
                </ol>
            </nav>
            <h1 class="m-0">Add Supplier</h1>
        </div>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')

    <div class="row">
        <div class="col-md-8">
            <form action="{{ route('admin.supplies.search', [$supplier->id]) }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
                <div class="card card-form d-flex flex-column flex-sm-row">
                    <div class="card-form__body card-body-form-group flex">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Search For Item</label>
                                    <input name="name" type="text" class="form-control" placeholder="Search by name" value="{{ old('name') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label><a href="{{ route('admin.suppliers.manage', [$supplier->id]) }}" id="no-underline">Clear Filters</a></label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn bg-primary border-left border-top border-top-sm-0 rounded-top-0 rounded-top-sm rounded-left-sm-0"><i class="material-icons text-white icon-20pt">search</i></button>
                </div>
            </form>
            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h4 class="card-header__title flex m-0">Please Select Item/s</h4>
                    <div data-toggle="flatpickr" data-flatpickr-wrap="true" data-flatpickr-static="true" data-flatpickr-mode="range" data-flatpickr-alt-format="d/m/Y" data-flatpickr-date-format="d/m/Y">
                        
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table"></th>
                                <th id="compact-table"></th>
                                <th id="compact-table">Name</th>
                                <th id="compact-table">Price</th>
                                <th id="compact-table">Brand</th>
                                <th id="compact-table">Category</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                            @foreach($items as $item)
                            @php
                                $supply = Supply::where('supplier_id', $supplier->id)
                                            ->where('item_id', $item->id)
                                            ->first()
                            @endphp
                                <tr>
                                    <td>
                                        @if ($supply)
                                            @if ($supply->status == SupplyStatus::ACTIVE)
                                                <a href="{{ route('admin.supplies.delete', [$supplier->id, $supply->id]) }}">
                                                    <button class="btn btn-sm btn-danger"><i class="material-icons icon-20pt text-white" data-toggle="tooltip" data-placement="top" title="Delete">delete</i></button>
                                                </a>
                                            @endif

                                            @if ($supply->status == SupplyStatus::INACTIVE)
                                                <a href="{{ route('admin.supplies.recover', [$supplier->id, $supply->id]) }}">
                                                    <button class="btn btn-sm btn-success"><i class="material-icons icon-20pt text-white" data-toggle="tooltip" data-placement="top" title="Recover">undo</i></button>
                                                </a>
                                            @endif
                                            
                                        @else
                                            <a href="#" data-toggle="modal" data-target="#add-supply-{{ $item->id }}">
                                                <button class="btn btn-sm btn-primary"><i class="material-icons icon-20pt text-white" data-toggle="tooltip" data-placement="top" title="Add To List">add</i></button>
                                            </a>
                                        @endif
                                    </td>
                                    <td id="compact-table">
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex align-items-center">
                                                @if ($item->image)
                                                    <img src="{{ url($item->image) }}" width="100px">
                                                @else
                                                    <img src="{{ url(env('BIG_FOUR_ICON')) }}" width="40px" style="margin-right: 7px;">
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td id="compact-table"><b>{{ $item->name }}</b></td>
                                    <td id="compact-table">
                                        @if ($supply)
                                            <a href="#" data-toggle="modal" data-target="#edit-supply-price-{{ $supply->id }}">
                                                P{{ number_format($supply->price, 2) }}
                                            </a>
                                        @else
                                            P{{ number_format(0, 2) }}
                                        @endif
                                    </td>
                                    <td id="compact-table">{{ $item->brand_name }}</td>
                                    <td id="compact-table">{{ $item->category_name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div style="padding-top: 20px">
                        {{ $items->links() }}
                    </div>

                    @if (count($items) <= 0)
                        <div style="padding: 20px">
                            <center><i class="material-icons icon-16pt mr-1 text-muted">assignment</i> No record/s found</center>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div id="spaced-card" class="card card-body">
                <h3>Supplier</h3>
                <br>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Supplier Name</h6>
                            {{ $supplier->name }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Email Address</h6>
                            {{ $supplier->email }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Contact Person</h6>
                            {{ $supplier->person }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Phone</h6>
                            {{ $supplier->phone }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Mobile</h6>
                            {{ $supplier->mobile }}
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Line Address 1</h6>
                            {{ $supplier->line_address_1 }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>Line Address 2</h6>
                            {{ $supplier->line_address_2 }}
                        </div>
                    </div>
                </div>

            </div>
            <br>
            <div id="spaced-card" class="card card-body">
                <h3>Supplies</h3>
                <a href="{{ route('admin.suppliers.items.masterlist', [$supplier->id]) }}">
                    View Masterlist
                </a>

                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table"></th>
                                <th id="compact-table">Name</th>
                                <th id="compact-table">Price</th>
                                <th id="compact-table">Brand</th>
                                <th id="compact-table">Category</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                            @foreach($supplier_items as $supplier_item)
                                <tr>
                                    <td id="compact-table">
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex align-items-center">
                                                @if ($supplier_item->item->image)
                                                    <img src="{{ url($supplier_item->item->image) }}" width="40px">
                                                @else
                                                    <img src="{{ url(env('BIG_FOUR_ICON')) }}" width="40px" style="margin-right: 7px;">
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td id="compact-table"><b>{{ $supplier_item->item->name }}</b></td>
                                    <td id="compact-table">P{{ number_format($supplier_item->price, 2) }}</td>
                                    <td id="compact-table">{{ $supplier_item->item->brand->name }}</td>
                                    <td id="compact-table">{{ $supplier_item->item->category->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if (count($supplier_items) <= 0)
                        <div style="padding: 20px">
                            <center><i class="material-icons icon-16pt mr-1 text-muted">assignment</i> No record/s found</center>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.auth.footer')