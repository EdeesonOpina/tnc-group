@include('layouts.auth.header')
@php
    use Carbon\Carbon;
    use App\Models\ServiceOrderStatus;
@endphp
<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('operations.service-orders') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('operations.service-orders') }}">Job Orders</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('operations.service-orders') }}">Receipt</a></li>
                    <li class="breadcrumb-item active" aria-current="page">View</li>
                </ol>
            </nav>
            <h1 class="m-0">Receipt</h1>
        </div>

        @if ($service_order->status == ServiceOrderStatus::FOR_REPAIR || $service_order->status == ServiceOrderStatus::BACK_JOB)
            <a href="{{ route('operations.service-orders.for-release', [$service_order->jo_number]) }}">
                <button type="button" class="btn btn-success" id="margin-right"><i class="fa fa-check" id="margin-right"></i>For Release</button>
            </a>
        @endif

        <a href="{{ route('operations.service-orders.print', [$service_order->jo_number]) }}">
            <button type="button" class="btn btn-light" id="margin-right"><i class="fa fa-print" id="margin-right"></i>Print</button>
        </a>
        <a href="{{ route('operations.service-orders.excel', [$service_order->jo_number]) }}">
            <button type="button" class="btn btn-success" id="margin-right"><i class="fa fa-file-excel" id="margin-right"></i>Create Excel</button>
        </a>
        <a href="{{ route('operations.service-orders.pdf', [$service_order->jo_number]) }}">
            <button type="button" class="btn btn-danger"><i class="fa fa-file-pdf" id="margin-right"></i>Create PDF</button>
        </a>
    </div>
</div>

<div class="container-fluid page__container">
    <div class="row">
        <div class="col-md-8">
            @include('layouts.partials.alerts')
            <div class="card">
                <div class="card-body">
                    @if ($service_order->status == ServiceOrderStatus::PENDING)
                        <div class="badge badge-warning">PROCESSING</div>
                    @elseif ($service_order->status == ServiceOrderStatus::CONFIRMED)
                        <div class="badge badge-success">CONFIRMED</div>
                    @elseif ($service_order->status == ServiceOrderStatus::BACK_JOB)
                        <div class="badge badge-success">BACK JOB</div>
                    @elseif ($service_order->status == ServiceOrderStatus::COMPLETED)
                        <div class="badge badge-success">COMPLETED</div>
                    @elseif ($service_order->status == ServiceOrderStatus::FOR_REPAIR)
                        <div class="badge badge-success">FOR REPAIR</div>
                    @elseif ($service_order->status == ServiceOrderStatus::FOR_RELEASE)
                        <div class="badge badge-success">FOR RELEASE</div>
                    @elseif ($service_order->status == ServiceOrderStatus::CANCELLED)
                        <div class="badge badge-danger">CANCELLED</div>
                    @elseif ($service_order->status == ServiceOrderStatus::INACTIVE)
                        <div class="badge badge-danger">INACTIVE</div>
                    @endif

                    @if ($service_order->is_completion == 1)
                        <br>
                        <div class="badge badge-success">FOR COMPLETION</div>
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
                                <div class="text-label">Date In</div>
                                {{ $service_order->created_at->format('M d Y') }}
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
                                <div class="text-label">Date Out</div>
                                    @if (!$service_order->date_out)
                                        @if ($service_order->status == ServiceOrderStatus::FOR_RELEASE)
                                            <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#date-out-{{ $service_order->id }}">Assign Date Out</button>
                                        @endif
                                    @else
                                        @if ($service_order->status == ServiceOrderStatus::FOR_RELEASE || $service_order->status == ServiceOrderStatus::BACK_JOB)
                                            <a href="#" data-toggle="modal" data-target="#date-out-{{ $service_order->id }}">
                                                <strong>{{ date("M d Y", strtotime($service_order->date_out)) }}</strong>
                                            </a>
                                        @else
                                            {{ date("M d Y", strtotime($service_order->date_out)) }}
                                        @endif
                                    @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg">
                                <div class="text-label">JO NUMBER</div>
                                <p>{{ $service_order->jo_number }}</p>
                            </div>
                            <div class="col-lg text-right">
                                <div class="text-label">MODE OF PAYMENT</div>
                                <p class="mb-4"><strong class="text-body">
                                    <!-- CHECK IF IT STILL DID NOT MEET THE DEADLINE -->
                                    @if (Carbon::now() <= $service_order->created_at->add(2, 'days'))
                                        <a href="#" data-toggle="modal" data-target="#assign-mop-{{ $service_order->id }}" class="no-underline">
                                        @if ($service_order->mop == 'cash')
                                            Cash
                                        @endif

                                        @if ($service_order->mop == 'credit')
                                            Credit
                                        @endif

                                        @if ($service_order->mop == 'cheque')
                                            Cheque
                                        @endif

                                        @if ($service_order->mop == 'credit-card')
                                            Credit Card
                                        @endif

                                        @if ($service_order->mop == 'bank-deposit')
                                            Bank Deposit
                                        @endif

                                        @if ($service_order->mop == 'paypal')
                                            PayPal
                                        @endif
                                        </a>
                                    @else
                                        @if ($service_order->mop == 'cash')
                                            Cash
                                        @endif

                                        @if ($service_order->mop == 'credit')
                                            Credit
                                        @endif

                                        @if ($service_order->mop == 'cheque')
                                            Cheque
                                        @endif

                                        @if ($service_order->mop == 'credit-card')
                                            Credit Card
                                        @endif

                                        @if ($service_order->mop == 'bank-deposit')
                                            Bank Deposit
                                        @endif

                                        @if ($service_order->mop == 'paypal')
                                            PayPal
                                        @endif
                                    @endif
                                </strong></p>
                            </div>
                        </div>

                        @if ($service_order->invoice_number || $service_order->bir_number)
                            <div class="row">
                                <div class="col-lg">
                                    <div class="text-label">INVOICE NUMBER</div>
                                    <p class="mb-4">{{ $service_order->invoice_number }}</p>
                                </div>
                                <div class="col-lg text-right">
                                    <div class="text-label">OR NUMBER</div>
                                    <p class="mb-4">{{ $service_order->bir_number }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="row mb-3">
                            <div class="col-lg">
                                &nbsp;
                            </div>
                            <div class="col-lg text-right">
                                
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
                                                {{ $service_order_detail->name }}<br>
                                                @if ($service_order_detail->serial_number_notes)
                                                    <br>
                                                    <strong>S/N:</strong> {{ $service_order_detail->serial_number_notes }}
                                                    <br>
                                                @endif

                                                @if ($service_order_detail->remarks)
                                                    <br>
                                                    <strong>Note:</strong> {{ $service_order_detail->remarks }}
                                                    <br>
                                                @endif

                                                @if ($service_order_detail->action_taken)
                                                    <br>
                                                    @if ($service_order->status == ServiceOrderStatus::FOR_REPAIR || $service_order->status == ServiceOrderStatus::BACK_JOB)
                                                        <strong>Action Taken:</strong> <a href="#" data-toggle="modal" data-target="#action-taken-{{ $service_order_detail->id }}"><strong>{{ $service_order_detail->action_taken }}</strong></a>
                                                    @else
                                                        <strong>Action Taken:</strong> {{ $service_order_detail->action_taken }}
                                                    @endif
                                                @else
                                                    @if ($service_order->status == ServiceOrderStatus::FOR_REPAIR || $service_order->status == ServiceOrderStatus::BACK_JOB)
                                                        <br>
                                                        <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#action-taken-{{ $service_order_detail->id }}">Add Action</button>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                @if ($service_order->status == ServiceOrderStatus::FOR_REPAIR || $service_order->status == ServiceOrderStatus::BACK_JOB)
                                                    <a href="#" data-toggle="modal" data-target="#set-price-{{ $service_order_detail->id }}">
                                                    <strong>P{{ number_format($service_order_detail->price, 2) }}</strong>
                                                    </a>
                                                @else
                                                    P{{ number_format($service_order_detail->price, 2) }}</strong>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($service_order->status == ServiceOrderStatus::FOR_REPAIR || $service_order->status == ServiceOrderStatus::BACK_JOB)
                                                    <a href="#" data-toggle="modal" data-target="#set-qty-{{ $service_order_detail->id }}">
                                                    <strong>{{ $service_order_detail->qty }}</strong>
                                                    </a>
                                                @else
                                                    {{ $service_order_detail->qty }}
                                                @endif
                                            </td>
                                            <td class="text-right">P{{ number_format($service_order_detail->total, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td><strong>Grand Total</strong></td>
                                        <td colspan="6" class="text-right"><strong>P{{ number_format($service_order_details_total, 2) }}</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg">
                                <div class="text-label">TECHNICAL</div>
                                <strong class="text-body">{{ $cashier->firstname }} {{ $cashier->lastname }}</strong><br>
                                {{ $cashier->role }}
                            </div>
                            <div class="col-lg text-right">
                                {!! QrCode::size(60)->generate(url(route('qr.payments.view', [$service_order->jo_number]))) !!}<br><br>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg">
                                <div class="text-label"><br><br>ACKNOWLEDGED BY<br><br></div>
                                
                            </div>
                            <div class="col-lg text-right">
                                <div class="text-label"><br><br>RECEIVED BY<br><br></div>
                            </div>
                        </div>

                        <div class="text-label">Notes</div>
                        <small class="text-muted note">One year warranty on parts. Accessories, software, virus and or consumables are NOT covered by this warranty. Warranty shall be void if warranty seal has been tampered or altered in anyway. If the items has been damaged brought about accident, misuse misapplication, abnormal causes or if the items has repaired or serviced by others. Inspect all items before signing this receipt.</small>

                    </div>
                </div>
            </div>  
        </div>

        @if ($service_order->back_job_notes)
            <div class="col">
                <div id="spaced-card" class="card card-body">
                    <h3>Back Job</h3>
                    <br>
                    <div class="row">
                        <div class="col-md-3">
                            <h6>Note:</h6>
                        </div>

                        <div class="col">
                            {{ $service_order->back_job_notes }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@include('layouts.auth.footer')