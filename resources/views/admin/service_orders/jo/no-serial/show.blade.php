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
                    <li class="breadcrumb-item active" aria-current="page">No Serial Number</li>
                </ol>
            </nav>
            <h1 class="m-0">POS - No Serial Number</h1>
        </div>
        <button id="margin-right" type="button" class="btn btn-warning text-white" data-toggle="modal" data-target="#credit"><i class="material-icons text-white icon-16pt">receipt</i> Credit</button>
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

                        <h3>POS - No S/N</h3>
                        <br>
                        <div class="row">
                            <div class="col">
                                <a href="{{ route('pos.standard', [$user->id]) }}">
                                    <button class="btn btn-light form-control">Standard</button>
                                </a>
                            </div>

                            <div class="col">
                                <a href="{{ route('pos.no-serial', [$user->id]) }}">
                                    <button class="btn btn-primary form-control">No S/N</button>
                                </a>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col">
                                <a href="{{ route('pos.service-order', [$user->id]) }}">
                                    <button class="btn btn-light form-control"><i class="material-icons icon-16pt">build</i> Service Order</button>
                                </a>
                            </div>
                        </div>
                        <br>
                        <form action="{{ route('pos.no-serial.scan', [$user->id]) }}" method="post" id="form" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Barcode</label><br>
                                    <input type="text" name="barcode" class="form-control" placeholder="Scan or enter by barcode" value="{{ old('barcode') }}" autofocus required>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
            <br>
            <div class="card">
                <div class="card-body">
                    <div class="px-3">
                        <br>
                        <strong>Serial Numbers</strong>
                        <br><br>
                        <div class="table-responsive">
                            <table class="table border-bottom mb-5">
                                <thead>
                                    <tr class="bg-light">
                                        <th>Item</th>
                                        <th class="text-right">S/N</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($carts as $cart)
                                    @php
                                        $tbl_item_serial_numbers = ItemSerialNumber::where('item_id', $cart->item->id)
                                            ->where('cart_id', $cart->id)
                                            ->get();
                                    @endphp
                                        <tr>
                                            <td>{{ $cart->inventory->item->name }}</td>
                                            <td class="text-right">
                                                @foreach($tbl_item_serial_numbers as $tbl_item_serial_number)
                                                    @if ($tbl_item_serial_number->item_id == $cart->inventory->item->id)
                                                        {{ $tbl_item_serial_number->code }}; 
                                                    @endif
                                                @endforeach

                                                <!-- IF NO S/N FOR THIS ITEM -->
                                                @if (count($tbl_item_serial_numbers) <= 0)
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
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
                                <div class="text-label">SO NUMBER</div>
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

                        <div class="row">
                            <div class="col-lg">
                                <div class="text-label">INVOICE NUMBER</div>
                                <p class="mb-4"><i class="material-icons">info</i> To be assigned</p>
                            </div>
                            <div class="col-lg text-right">
                                <div class="text-label">OR NUMBER</div>
                                <p class="mb-4"><i class="material-icons">info</i> To be assigned</p>
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
                                        <th>Barcode</th>
                                        <th>Item</th>
                                        <th class="text-right">Price</th>
                                        <th>Qty</th>
                                        <th class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($carts as $cart)
                                        <tr>
                                            <td>{{ $cart->inventory->item->barcode }}</td>
                                            <td>
                                                {{ $cart->inventory->item->name }}
                                                <a href="#" data-href="{{ route('pos.standard.delete', [$user->id, $cart->id]) }}" class="text-danger" data-toggle="modal" data-target="#confirm-action"><i class="material-icons" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete">delete</i></a>
                                            </td>
                                            <td class="text-right">P{{ number_format($cart->price, 2) }}</td>
                                            <td><a href="#" data-toggle="modal" data-target="#set-qty-{{ $cart->id }}">{{ $cart->qty }}</a></td>
                                            <td class="text-right">P{{ number_format($cart->total, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td><strong>Discount</strong></td>
                                        <td colspan="4" class="text-right"><strong>
                                        P{{ number_format(0, 2) }}
                                         </strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>VAT</strong></td>
                                        <td colspan="4" class="text-right"><strong>P{{ number_format(0, 2) }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Grand Total</strong></td>
                                        <td colspan="4" class="text-right"><strong>P{{ number_format($carts_total, 2) }}</strong></td>
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