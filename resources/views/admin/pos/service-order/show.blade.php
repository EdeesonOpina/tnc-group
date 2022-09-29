@include('layouts.auth.header')
@php
    use App\Models\ItemSerialNumber;
@endphp
<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('pos') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('pos.customer.new') }}">POS</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Service Order</li>
                </ol>
            </nav>
            <h1 class="m-0">POS - Service Order</h1>
        </div>
        <!-- <button id="margin-right" type="button" class="btn btn-warning text-white" data-toggle="modal" data-target="#credit"><i class="material-icons text-white icon-16pt">receipt</i> Credit</button> -->
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#checkout"><i class="material-icons text-white icon-16pt">receipt</i> Checkout</button>
    </div>
</div>

<div class="container-fluid page__container">
    <div class="row">
        <div class="col-md-4">
            @include('layouts.partials.alerts')
            <div id="spaced-card" class="card card-body">
                <div class="row">
                    <div class="col">

                        <h3>POS - Service Order</h3>
                        <br>
                        <div class="row">
                            <div class="col">
                                <a href="{{ route('pos.standard', [$user->id]) }}">
                                    <button class="btn btn-light form-control">Standard</button>
                                </a>
                            </div>

                            <div class="col">
                                <a href="{{ route('pos.no-serial', [$user->id]) }}">
                                    <button class="btn btn-light form-control">No S/N</button>
                                </a>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col">
                                <a href="{{ route('pos.service-order', [$user->id]) }}">
                                    <button class="btn btn-primary form-control"><i class="material-icons text-white icon-16pt">build</i> Service Order</button>
                                </a>
                            </div>
                        </div>
                        <br>
                        <form action="{{ route('pos.service-order.create', [$user->id]) }}" method="post" id="form" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Service Name</label><br>
                                    <textarea name="name" class="form-control" placeholder="Enter service name" autofocus required>{{ old('name') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Qty</label><br>
                                    <input type="number" name="qty" class="form-control" placeholder="0" value="{{ old('qty') ?? 1 }}" min="1">
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label>Price</label><br>
                                    <input type="text" name="price" class="form-control" placeholder="0.00" value="{{ old('price') ?? '0.00' }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Remarks (optional)</label><br>
                                    <textarea name="remarks" class="form-control" placeholder="Enter remarks here">{{ old('remarks') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
            <br>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="badge badge-warning">Processing</div>

                    <div class="px-3">
                        <div class="d-flex flex-column my-3 navbar-light">
                            <a href="#" class="navbar-brand d-flex m-0" style="min-width: 0">
                                <img class="navbar-brand-icon mb-2" src="{{ url(env('APP_LOGO_WITH_TEXT')) }}" width="200" alt="{{ env('TNCPC_WAREHOUSE') }}">
                            </a>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg">
                                <div class="text-label">FROM</div>
                                <p class="mb-4">
                                    <strong class="text-body">{{ $branch->name }}</strong><br>
                                    {{ $branch->line_address_1 }}<br>
                                    {{ $branch->line_address_2 }}
                                </p>
                                <div class="text-label">JO NUMBER</div>
                                <p><i class="material-icons">info</i> To be assigned</p>
                            </div>
                            <div class="col-lg text-right">
                                <div class="text-label">TO (CUSTOMER)</div>
                                <p class="mb-4">
                                    <strong class="text-body">{{ $user->firstname }} {{ $user->lastname }}</strong><br>
                                    {{ $user->line_address_1 }}<br>
                                    {{ $user->line_address_2 }}<br>
                                </p>
                                <div class="text-label">Date</div>
                                {{ date('M d Y') }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg">
                                &nbsp;
                            </div>
                            <div class="col-lg text-right">
                                <div class="text-label">MODE OF PAYMENT</div>
                                <p class="mb-4"><strong class="text-body">Cash</strong></p>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table border-bottom mb-5">
                                <thead>
                                    <tr class="bg-light">
                                        <th>Service</th>
                                        <th class="text-right">Price</th>
                                        <th>Qty</th>
                                        <th class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($service_order_details as $service_order_detail)
                                        <tr>
                                            <td>
                                                {{ $service_order_detail->name }}
                                                <a href="#" data-href="{{ route('pos.service-order.delete', [$user->id, $service_order_detail->id]) }}" class="text-danger" data-toggle="modal" data-target="#confirm-action"><i class="material-icons" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete">delete</i></a>
                                                <br>
                                                <strong>Note:</strong> {{ $service_order_detail->remarks }}
                                            </td>
                                            <td class="text-right">P{{ number_format($service_order_detail->price, 2) }}</td>
                                            <td><a href="#" data-toggle="modal" data-target="#set-qty-{{ $service_order_detail->id }}">{{ $service_order_detail->qty }}</a></td>
                                            <td class="text-right">P{{ number_format($service_order_detail->total, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td><strong>Grand Total</strong></td>
                                        <td colspan="4" class="text-right"><strong>P{{ number_format($service_order_details_total, 2) }}</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg">
                                <div class="text-label">Notes</div>
                                <p class="text-muted">All prices are exclusive of taxes</p>
                            </div>
                            <div class="col-lg text-right">
                                <div class="text-label">CASHIER</div>
                                <strong class="text-body">{{ $cashier->firstname }} {{ $cashier->lastname }}</strong><br>
                                {{ $cashier->role }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.auth.footer')