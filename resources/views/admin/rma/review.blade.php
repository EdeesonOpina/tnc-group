@include('layouts.auth.header')
@php
    use Carbon\Carbon;
    use App\Models\PaymentStatus;
    use App\Models\ItemSerialNumber;
    use App\Models\PaymentReceiptStatus;
    use App\Models\ReturnInventoryStatus;
    use App\Models\ItemSerialNumberStatus;
@endphp
<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('internals.rma') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('internals.rma') }}">RMA</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('internals.rma') }}">Receipt</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Item</li>
                </ol>
            </nav>
            <h1 class="m-0">Receipt</h1>
        </div>
        <a href="{{ route('internals.rma.items', [$return_inventory->reference_number]) }}">
            <button type="button" class="btn btn-light" id="margin-right"><i class="fa fa-plus" id="margin-right"></i>Add Item</button>
        </a>
        
        @if ($return_inventory->status == ReturnInventoryStatus::ON_PROCESS)
            <a href="{{ route('internals.rma.for-warranty', [$return_inventory->reference_number]) }}">
                <button type="button" class="btn btn-warning" id="margin-right"><i class="fa fa-check" id="margin-right"></i>Mark As For Warranty</button>
            </a>
        @endif
    </div>
</div>

<div class="container-fluid page__container">
    <div class="row">
        <div class="col-md-5">
            <div id="spaced-card" class="card card-body">
                <h3>RMA Form</h3>
                <br>
                <form action="{{ route('internals.rma.items.create', [$return_inventory->reference_number]) }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="return_inventory_id" value="{{ $return_inventory->id }}">
                <input type="hidden" name="inventory_id" value="{{ $inventory->id }}">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <h6>RMA #</h6>
                            <span class="badge badge-success" id="large-font">{{ $return_inventory->reference_number }}</span>
                        </div>

                        <div class="form-group">
                            <label>Item</label>
                            <input type="text" name="inventory_id" class="form-control" placeholder="Name" value="{{ $inventory->item->name }}" disabled>
                        </div>

                        <div class="form-group">
                            <label>Return Type</label>
                            <select name="type" class="form-control">
                                <option value="advanced-replacement">Advanced Replacement</option>
                                <option value="for-warranty">For Warranty</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Qty</label>
                            <input type="text" name="qty" class="form-control" placeholder="Qty" min="1" value="1">
                        </div>

                        <div class="form-group">
                            <label>Remarks</label>
                            <textarea class="form-control" name="remarks" placeholder="Enter remarks here">
                                {{ old('remarks') }}
                            </textarea>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="form-control btn btn-success">Save</button>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>

        <div class="col-md-7">
            @include('layouts.partials.alerts')
            <div class="card">
                <div class="card-body">
                    @if ($return_inventory->status == ReturnInventoryStatus::FOR_WARRANTY)
                        <div class="badge badge-warning">FOR WARRANTY</div>
                    @elseif ($return_inventory->status == ReturnInventoryStatus::WAITING)
                        <div class="badge badge-warning">WAITING</div>
                    @elseif ($return_inventory->status == ReturnInventoryStatus::FOR_RELEASE)
                        <div class="badge badge-warning">FOR RELEASE</div>
                    @elseif ($return_inventory->status == ReturnInventoryStatus::CLEARED)
                        <div class="badge badge-success">CLEARED</div>
                    @elseif ($return_inventory->status == ReturnInventoryStatus::OUT_OF_WARRANTY)
                        <div class="badge badge-danger">OUT OF WARRANTY</div>
                    @elseif ($return_inventory->status == ReturnInventoryStatus::CANCELLED)
                        <div class="badge badge-danger">CANCELLED</div>
                    @elseif ($return_inventory->status == ReturnInventoryStatus::INACTIVE)
                        <div class="badge badge-danger">INACTIVE</div>
                    @endif
                    <br><br>
                    <div class="px-3">
                        <div class="row">
                            <div class="col-md-3">
                                <!-- <img class="navbar-brand-icon mb-2" src="{{ url(env('APP_LOGO_WITH_TEXT')) }}" width="100%" alt="{{ env('APP_NAME') }}"> -->
                                <img class="navbar-brand-icon mb-2" src="{{ url(env('BIG_FOUR_LOGO')) }}" width="100%" alt="{{ env('APP_NAME') }}">
                            </div>
                        </div>
                        <br>
                        <div class="row mb-3">
                            <div class="col-lg">
                                <div class="text-label">RMA#</div>
                                <p class="mb-4">
                                    <strong class="text-body">{{ $return_inventory->reference_number }}</strong>
                                </p>
                                @if ($return_inventory->delivery_receipt_number)
                                    <div class="text-label">Supplier</div>
                                    {{ $return_inventory->goods_receipt->purchase_order->supplier->name }}
                                    <br><br>
                                @elseif ($return_inventory->supplier)
                                    <div class="text-label">Supplier</div>
                                    {{ $return_inventory->supplier->name }}
                                    <br><br>
                                @else
                                    <div class="text-label">Supplier</div>
                                    <a href="{{ route('internals.rma.suppliers', [$return_inventory->reference_number]) }}" class="no-underline">
                                        <button class="btn btn-success btn-sm">Assign Supplier</button>
                                    </a>
                                    <br><br>
                                @endif

                                <div class="text-label">Customer</div>
                                {{ $return_inventory->payment_receipt->user->firstname }} {{ $return_inventory->payment_receipt->user->lastname }}
                            </div>
                            <div class="col-lg text-right">
                                <div class="text-label">SO#</div>
                                <p class="mb-4">
                                    <strong class="text-body">{{ $return_inventory->payment_receipt->so_number }}</strong>
                                </p>
                                @if ($return_inventory->delivery_receipt_number)
                                    <div class="text-label">GRPO#</div>
                                    <strong class="text-body">{{ $return_inventory->goods_receipt->reference_number }}</strong>
                                    <br><br>
                                    <div class="text-label">DR#</div>
                                    <strong class="text-body">
                                        {{ $return_inventory->delivery_receipt_number }}
                                        <a href="#" data-toggle="modal" data-target="#delivery-receipt-{{ $return_inventory->reference_number }}" class="no-underline">
                                            <i class="material-icons icon-16pt text-success" data-toggle="tooltip" data-placement="top" title="Delivery Receipt">edit</i>
                                        </a>
                                    </strong>
                                    <br><br>
                                @else
                                    <div class="text-label">GRPO#</div>
                                    N/A
                                    <br><br>
                                    <div class="text-label">DR#</div>
                                    <a href="#" data-toggle="modal" data-target="#delivery-receipt-{{ $return_inventory->reference_number }}" class="no-underline">
                                        <button class="btn btn-success btn-sm">Add DR#</button>
                                    </a>
                                    <br><br>
                                @endif
                                <div class="text-label">Date</div>
                                {{ $return_inventory->created_at->format('M d Y') }}
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table border-bottom mb-5">
                                <thead>
                                    <tr class="bg-light">
                                        <th>Item</th>
                                        <th>Type</th>
                                        <th>Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($return_inventory_items as $return_inventory_item)
                                        <tr>
                                            <td>
                                                {{ $return_inventory_item->inventory->item->name }} <a href="{{ route('internals.rma.items.delete', [$return_inventory->id, $return_inventory_item->id]) }}">
                                                    <i class="material-icons icon-20pt text-danger" data-toggle="tooltip" data-placement="top" title="Delete">delete</i>
                                                </a>
                                                @if ($return_inventory_item->remarks)
                                                    <br><br>
                                                    <strong>Remarks: </strong>{{ $return_inventory_item->remarks }}
                                                @endif
                                            </td>
                                            <td>{{ $return_inventory_item->type }}</td>
                                            <td>{{ $return_inventory_item->qty }}</td>
                                        </tr>
                                    @endforeach

                                    @if (count($return_inventory_items) <= 0)
                                        <tr>
                                            <td colspan="3"><center>No data yet</center></td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg">
                                {!! QrCode::size(60)->generate(url(route('qr.payments.view', [$payment_receipt->so_number]))) !!}<br><br>
                            </div>
                            <div class="col-lg text-right">
                                <div class="text-label">RMA</div>
                                <strong class="text-body">{{ $return_inventory->created_by_user->firstname }} {{ $return_inventory->created_by_user->lastname }}</strong><br>
                                {{ $return_inventory->created_by_user->role }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    @if ($payment_receipt->status == PaymentReceiptStatus::PENDING)
                        <div class="badge badge-warning">PROCESSING</div>
                    @elseif ($payment_receipt->status == PaymentReceiptStatus::CONFIRMED)
                        <div class="badge badge-success">CONFIRMED</div>
                    @elseif ($payment_receipt->status == PaymentReceiptStatus::FOR_DELIVERY)
                        <div class="badge badge-success">FOR DELIVERY</div>
                    @elseif ($payment_receipt->status == PaymentReceiptStatus::DELIVERED)
                        <div class="badge badge-success">DELIVERED</div>
                    @elseif ($payment_receipt->status == PaymentReceiptStatus::CANCELLED)
                        <div class="badge badge-danger">CANCELLED</div>
                    @elseif ($payment_receipt->status == PaymentReceiptStatus::INACTIVE)
                        <div class="badge badge-danger">INACTIVE</div>
                    @endif
                    <br><br>
                    <div class="px-3">
                        <div class="row">
                            <div class="col-md-3">
                                <!-- <img class="navbar-brand-icon mb-2" src="{{ url(env('APP_LOGO_WITH_TEXT')) }}" width="100%" alt="{{ env('APP_NAME') }}"> -->
                                <img class="navbar-brand-icon mb-2" src="{{ url(env('BIG_FOUR_LOGO')) }}" width="100%" alt="{{ env('APP_NAME') }}">
                            </div>
                        </div>
                        <br>
                        <div class="row mb-3">
                            <div class="col-lg">
                                <div class="text-label">FROM</div>
                                <p class="mb-4">
                                    <strong class="text-body">{{ $branch->name }}</strong><br>
                                    {{ $branch->line_address_1 }}<br>
                                    {{ $branch->line_address_2 }}<br>
                                    @if ($branch->phone)
                                        {{ $branch->phone }} / 
                                    @endif
                                    @if ($branch->mobile)
                                        {{ $branch->mobile }}
                                    @endif
                                </p>
                                <div class="text-label">SO NUMBER</div>
                                <p>{{ $payment_receipt->so_number }}</p>
                            </div>
                            <div class="col-lg text-right">
                                <div class="text-label">TO (CUSTOMER)</div>
                                <p class="mb-4">
                                    <strong class="text-body">{{ $user->firstname }} {{ $user->lastname }}</strong><br>
                                    {{ $user->line_address_1 }}<br>
                                    {{ $user->line_address_2 }}<br>
                                    @if ($user->phone)
                                        {{ $user->phone }} / 
                                    @endif
                                    @if ($user->mobile)
                                        {{ $user->mobile }}
                                    @endif
                                </p>
                                <div class="text-label">Date</div>
                                {{ $payment_receipt->created_at->format('M d Y') }}
                            </div>
                        </div>

                        @if ($payment_receipt->invoice_number || $payment_receipt->bir_number)
                            <div class="row">
                                <div class="col-lg">
                                    <div class="text-label">INVOICE NUMBER</div>
                                    <p class="mb-4">{{ $payment_receipt->invoice_number }}</p>
                                </div>
                                <div class="col-lg text-right">
                                    <div class="text-label">OR NUMBER</div>
                                    <p class="mb-4">{{ $payment_receipt->bir_number }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="row mb-3">
                            <div class="col-lg">
                                &nbsp;
                            </div>
                            <div class="col-lg text-right">
                                <div class="text-label">MODE OF PAYMENT</div>
                                <p class="mb-4"><strong class="text-body">
                                    <!-- CHECK IF IT STILL DID NOT MEET THE DEADLINE -->
                                    @if (Carbon::now() <= $payment_receipt->created_at->add(2, 'days'))
                                        <a href="#" data-toggle="modal" data-target="#assign-mop-{{ $payment_receipt->id }}" class="no-underline">
                                        @if ($payment_receipt->mop == 'cash')
                                            Cash
                                        @endif

                                        @if ($payment_receipt->mop == 'credit')
                                            Credit
                                        @endif

                                        @if ($payment_receipt->mop == 'cheque')
                                            Cheque
                                        @endif

                                        @if ($payment_receipt->mop == 'credit-card')
                                            Credit Card
                                        @endif

                                        @if ($payment_receipt->mop == 'bank-deposit')
                                            Bank Deposit
                                        @endif

                                        @if ($payment_receipt->mop == 'paypal')
                                            PayPal
                                        @endif
                                        </a>
                                    @else
                                        @if ($payment_receipt->mop == 'cash')
                                            Cash
                                        @endif

                                        @if ($payment_receipt->mop == 'credit')
                                            Credit
                                        @endif

                                        @if ($payment_receipt->mop == 'cheque')
                                            Cheque
                                        @endif

                                        @if ($payment_receipt->mop == 'credit-card')
                                            Credit Card
                                        @endif

                                        @if ($payment_receipt->mop == 'bank-deposit')
                                            Bank Deposit
                                        @endif

                                        @if ($payment_receipt->mop == 'paypal')
                                            PayPal
                                        @endif
                                    @endif
                                </strong></p>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table border-bottom mb-5">
                                <thead>
                                    <tr class="bg-light">
                                        <th>Item</th>
                                        <th class="text-right">Price</th>
                                        <th>Qty</th>
                                        <th class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($payments as $payment)
                                        <tr>
                                            <td>
                                                {{ $payment->inventory->item->name }}
                                            </td>
                                            <td class="text-right">P{{ number_format($payment->price, 2) }}</td>
                                            <td>{{ $payment->qty }}</td>
                                            <td class="text-right">P{{ number_format($payment->total, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg">
                                {!! QrCode::size(60)->generate(url(route('qr.payments.view', [$payment_receipt->so_number]))) !!}<br><br>
                            </div>
                            <div class="col-lg text-right">
                                <div class="text-label">CASHIER</div>
                                <strong class="text-body">{{ $cashier->firstname }} {{ $cashier->lastname }}</strong><br>
                                {{ $cashier->role }}
                            </div>
                        </div>

                        <div class="text-label">Notes</div>
                        <small class="text-muted note">One year warranty on parts. Accessories, software, virus and or consumables are NOT covered by this warranty. Warranty shall be void if warranty seal has been tampered or altered in anyway. If the items has been damaged brought about accident, misuse misapplication, abnormal causes or if the items has repaired or serviced by others. Inspect all items before signing this receipt.</small>

                    </div>
                </div>
            </div>

            @if (count($item_serial_numbers) > 0)
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
                                        @foreach ($payments->unique('item_id') as $payment)
                                        @php
                                            $tbl_item_serial_numbers = ItemSerialNumber::where('item_id', $payment->item_id)
                                                                                    ->get();
                                        @endphp
                                            <tr>
                                                <td>{{ $payment->inventory->item->name }}</td>
                                                <td class="text-right">
                                                    @foreach($tbl_item_serial_numbers as $tbl_item_serial_number)
                                                        @if ($tbl_item_serial_number->payment)
                                                            @if ($tbl_item_serial_number->payment->so_number == $payment->so_number)
                                                                {{ $tbl_item_serial_number->code }}; 
                                                            @endif
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
                                    {!! QrCode::size(60)->generate(url(route('qr.payments.view', [$payment_receipt->id]))) !!}<br><br>
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
            @endif
        </div>
    </div>
</div>
@include('layouts.auth.footer')