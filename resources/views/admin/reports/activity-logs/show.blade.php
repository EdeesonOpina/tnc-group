@include('layouts.auth.header')

<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Reports</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Activity Logs</li>
                </ol>
            </nav>
            <h1 class="m-0">Activity Logs</h1>
        </div>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')
    @include('layouts.partials.top-tabs')

    <form action="{{ route('admin.reports.activity-logs.search') }}" method="post">
        {{ csrf_field() }}
        <div class="card card-form d-flex flex-column flex-sm-row">
            <div class="card-form__body card-body-form-group flex">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>User</label>
                            <input name="name" type="text" class="form-control" placeholder="Search by name" value="{{ old('name') }}">
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
                        <label><a href="{{ route('admin.reports.activity-logs') }}" id="no-underline">Clear Filters</a></label>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn bg-white border-left border-top border-top-sm-0 rounded-top-0 rounded-top-sm rounded-left-sm-0"><i class="material-icons text-primary icon-20pt">search</i></button>
        </div>
    </form>

    <p class="text-dark-gray d-flex align-items-center mt-3">
        <i class="material-icons icon-muted mr-2">event</i>
        <strong>Authentication</strong>
    </p>

    @foreach ($activity_log_auth as $al_auth)
        <div class="row align-items-center projects-item mb-1">
            <div class="col-sm-auto mb-1 mb-sm-0">
                <div class="text-dark-gray">{{ $al_auth->created_at->format('g:i a') }}</div>
            </div>
            <div class="col-sm">
                <div class="card m-0">
                    <div class="px-4 py-3">
                        <div class="row align-items-center">
                            <div class="col" style="min-width: 300px">
                                <div class="d-flex align-items-center">
                                    <a href="#" class="text-body"><strong class="text-15pt mr-2">{{ $al_auth->description }}</strong></a>
                                    <span class="badge badge-success">{{ $al_auth->uri }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <small class="text-dark mr-2">{{ $al_auth->device }}</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <small class="text-dark-gray mr-2">User</small>
                                    <a href="#" class="d-flex align-items-middle">
                                        <span class="avatar avatar-xxs avatar-online mr-2">
                                            @if ($al_auth->auth)
                                                @if ($al_auth->auth->avatar)
                                                    <img src="{{ url($al_auth->auth->avatar) }}" alt="{{ $al_auth->auth->firstname }} {{ $al_auth->auth->lastname }}" class="avatar-img rounded-circle">
                                                @else
                                                    <img src="{{ url(env('BIG_FOUR_ICON')) }}" alt="{{ $al_auth->auth->firstname }} {{ $al_auth->auth->lastname }}" class="avatar-img rounded-circle">
                                                @endif
                                            @else
                                                <img src="{{ url(env('BIG_FOUR_ICON')) }}" alt="anon" class="avatar-img rounded-circle">
                                            @endif
                                        </span>
                                        @if ($al_auth->auth)
                                            {{ $al_auth->auth->firstname }} {{ $al_auth->auth->lastname }}
                                        @else
                                            Anonymous
                                        @endif
                                    </a>
                                </div>
                            </div>
                            <div class="col-auto d-flex align-items-center">
                                <i class="material-icons icon-muted mr-2">access_time</i>
                                {{ $al_auth->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <div style="padding-top: 20px;">
        {{ $activity_log_auth->links() }}
    </div>

    <p class="text-dark-gray d-flex align-items-center mt-3">
        <i class="material-icons icon-muted mr-2">event</i>
        <strong>Purchase Orders</strong>
    </p>

    @foreach ($activity_log_purchase_orders as $al_purchase_order)
        <div class="row align-items-center projects-item mb-1">
            <div class="col-sm-auto mb-1 mb-sm-0">
                <div class="text-dark-gray">{{ $al_purchase_order->created_at->format('g:i a') }}</div>
            </div>
            <div class="col-sm">
                <div class="card m-0">
                    <div class="px-4 py-3">
                        <div class="row align-items-center">
                            <div class="col" style="min-width: 300px">
                                <div class="d-flex align-items-center">
                                    <a href="#" class="text-body"><strong class="text-15pt mr-2">
                                        @if ($al_purchase_order->description == 'create')
                                            Created Purchase Order {{ $al_purchase_order->purchase_order->reference_number }}
                                        @elseif ($al_purchase_order->description == 'update')
                                            Updated Purchase Order {{ $al_purchase_order->purchase_order->reference_number }}
                                        @elseif ($al_purchase_order->description == 'cancel')
                                            Cancelled Purchase Order {{ $al_purchase_order->purchase_order->reference_number }}
                                        @elseif ($al_purchase_order->description == 'approve')
                                            Approved Purchase Order {{ $al_purchase_order->purchase_order->reference_number }}
                                        @elseif ($al_purchase_order->description == 'disapprove')
                                            Disapproved Purchase Order {{ $al_purchase_order->purchase_order->reference_number }}
                                        @elseif ($al_purchase_order->description == 'finalize')
                                            Finalized Purchase Order {{ $al_purchase_order->purchase_order->reference_number }}
                                        @elseif ($al_purchase_order->description == 'delete')
                                            Deleteed Purchase Order {{ $al_purchase_order->purchase_order->reference_number }}
                                        @elseif ($al_purchase_order->description == 'recover')
                                            Recovered Purchase Order {{ $al_purchase_order->purchase_order->reference_number }}
                                        @endif
                                    </strong></a>
                                    <span class="badge badge-success">{{ $al_purchase_order->uri }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <small class="text-dark mr-2">{{ $al_purchase_order->device }}</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <small class="text-dark-gray mr-2">User</small>
                                    <a href="#" class="d-flex align-items-middle">
                                        <span class="avatar avatar-xxs avatar-online mr-2">
                                            @if ($al_purchase_order->auth)
                                                @if ($al_purchase_order->auth->avatar)
                                                    <img src="{{ url($al_purchase_order->auth->avatar) }}" alt="{{ $al_purchase_order->auth->firstname }} {{ $al_purchase_order->auth->lastname }}" class="avatar-img rounded-circle">
                                                @else
                                                    <img src="{{ url(env('BIG_FOUR_ICON')) }}" alt="{{ $al_purchase_order->auth->firstname }} {{ $al_purchase_order->auth->lastname }}" class="avatar-img rounded-circle">
                                                @endif
                                            @else
                                                <img src="{{ url(env('BIG_FOUR_ICON')) }}" alt="anon" class="avatar-img rounded-circle">
                                            @endif
                                        </span>
                                        @if ($al_purchase_order->auth)
                                            {{ $al_purchase_order->auth->firstname }} {{ $al_purchase_order->auth->lastname }}
                                        @else
                                            Anonymous
                                        @endif
                                    </a>
                                </div>
                            </div>
                            <div class="col-auto d-flex align-items-center">
                                <i class="material-icons icon-muted mr-2">access_time</i>
                                {{ $al_purchase_order->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <div style="padding-top: 20px;">
        {{ $activity_log_purchase_orders->links() }}
    </div>

    <p class="text-dark-gray d-flex align-items-center mt-3">
        <i class="material-icons icon-muted mr-2">event</i>
        <strong>Goods Receipts</strong>
    </p>

    @foreach ($activity_log_goods_receipts as $al_goods_receipt)
        <div class="row align-items-center projects-item mb-1">
            <div class="col-sm-auto mb-1 mb-sm-0">
                <div class="text-dark-gray">{{ $al_goods_receipt->goods_receipt->created_at->format('g:i a') }}</div>
            </div>
            <div class="col-sm">
                <div class="card m-0">
                    <div class="px-4 py-3">
                        <div class="row align-items-center">
                            <div class="col" style="min-width: 300px">
                                <div class="d-flex align-items-center">
                                    <a href="#" class="text-body"><strong class="text-15pt mr-2">
                                        @if ($al_goods_receipt->description == 'create')
                                            Created Goods Receipt {{ $al_goods_receipt->goods_receipt->reference_number }}
                                        @elseif ($al_goods_receipt->description == 'update')
                                            Updated Goods Receipt {{ $al_goods_receipt->goods_receipt->reference_number }}
                                        @elseif ($al_goods_receipt->description == 'cancel')
                                            Cancelled Goods Receipt {{ $al_goods_receipt->goods_receipt->reference_number }}
                                        @elseif ($al_goods_receipt->description == 'approve')
                                            Approved Goods Receipt {{ $al_goods_receipt->goods_receipt->reference_number }}
                                        @elseif ($al_goods_receipt->description == 'disapprove')
                                            Disapproved Goods Receipt {{ $al_goods_receipt->goods_receipt->reference_number }}
                                        @elseif ($al_goods_receipt->description == 'finalize')
                                            Finalized Goods Receipt {{ $al_goods_receipt->goods_receipt->reference_number }}
                                        @elseif ($al_goods_receipt->description == 'clear')
                                            Cleared Goods Receipt {{ $al_goods_receipt->goods_receipt->reference_number }}
                                        @elseif ($al_goods_receipt->description == 'delete')
                                            Deleteed Goods Receipt {{ $al_goods_receipt->goods_receipt->reference_number }}
                                        @elseif ($al_goods_receipt->description == 'recover')
                                            Recovered Goods Receipt {{ $al_goods_receipt->goods_receipt->reference_number }}
                                        @endif
                                    </strong></a>
                                    <span class="badge badge-success">{{ $al_goods_receipt->uri }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <small class="text-dark mr-2">{{ $al_goods_receipt->device }}</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <small class="text-dark-gray mr-2">User</small>
                                    <a href="#" class="d-flex align-items-middle">
                                        <span class="avatar avatar-xxs avatar-online mr-2">
                                            @if ($al_goods_receipt->auth)
                                                @if ($al_goods_receipt->auth->avatar)
                                                    <img src="{{ url($al_goods_receipt->auth->avatar) }}" alt="{{ $al_goods_receipt->auth->firstname }} {{ $al_goods_receipt->auth->lastname }}" class="avatar-img rounded-circle">
                                                @else
                                                    <img src="{{ url(env('BIG_FOUR_ICON')) }}" alt="{{ $al_goods_receipt->auth->firstname }} {{ $al_goods_receipt->auth->lastname }}" class="avatar-img rounded-circle">
                                                @endif
                                            @else
                                                <img src="{{ url(env('BIG_FOUR_ICON')) }}" alt="anon" class="avatar-img rounded-circle">
                                            @endif
                                        </span>
                                        @if ($al_goods_receipt->auth)
                                            {{ $al_goods_receipt->auth->firstname }} {{ $al_goods_receipt->auth->lastname }}
                                        @else
                                            Anonymous
                                        @endif
                                    </a>
                                </div>
                            </div>
                            <div class="col-auto d-flex align-items-center">
                                <i class="material-icons icon-muted mr-2">access_time</i>
                                {{ $al_goods_receipt->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <div style="padding-top: 20px;">
        {{ $activity_log_goods_receipts->links() }}
    </div>

    <p class="text-dark-gray d-flex align-items-center mt-3">
        <i class="material-icons icon-muted mr-2">event</i>
        <strong>Inventory Receive</strong>
    </p>

    @foreach ($inventory_receive_records as $inventory_receive_record)
        <div class="row align-items-center projects-item mb-1">
            <div class="col-sm-auto mb-1 mb-sm-0">
                <div class="text-dark-gray">{{ $inventory_receive_record->created_at->format('g:i a') }}</div>
            </div>
            <div class="col-sm">
                <div class="card m-0">
                    <div class="px-4 py-3">
                        <div class="row align-items-center">
                            <div class="col" style="min-width: 300px">
                                <div class="d-flex align-items-center">
                                    <a href="#" class="text-body"><strong class="text-15pt mr-2">
                                        Goods Receipt {{ $inventory_receive_record->goods_receipt->reference_number }}<br>
                                        Received x{{ $inventory_receive_record->qty }} {{ $inventory_receive_record->inventory->item->name }} 
                                    </strong></a>
                                    <span class="badge badge-success">{{ $inventory_receive_record->uri }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <small class="text-dark mr-2">{{ $inventory_receive_record->device }}</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <small class="text-dark-gray mr-2">User</small>
                                    <a href="#" class="d-flex align-items-middle">
                                        <span class="avatar avatar-xxs avatar-online mr-2">
                                            @if ($inventory_receive_record->auth)
                                                @if ($inventory_receive_record->auth->avatar)
                                                    <img src="{{ url($inventory_receive_record->auth->avatar) }}" alt="{{ $inventory_receive_record->auth->firstname }} {{ $inventory_receive_record->auth->lastname }}" class="avatar-img rounded-circle">
                                                @else
                                                    <img src="{{ url(env('BIG_FOUR_ICON')) }}" alt="{{ $inventory_receive_record->auth->firstname }} {{ $inventory_receive_record->auth->lastname }}" class="avatar-img rounded-circle">
                                                @endif
                                            @else
                                                <img src="{{ url(env('BIG_FOUR_ICON')) }}" alt="anon" class="avatar-img rounded-circle">
                                            @endif
                                        </span>
                                        @if ($inventory_receive_record->auth)
                                            {{ $inventory_receive_record->auth->firstname }} {{ $inventory_receive_record->auth->lastname }}
                                        @else
                                            Anonymous
                                        @endif
                                    </a>
                                </div>
                            </div>
                            <div class="col-auto d-flex align-items-center">
                                <i class="material-icons icon-muted mr-2">access_time</i>
                                {{ $inventory_receive_record->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <div style="padding-top: 20px;">
        {{ $inventory_receive_records->links() }}
    </div>

    <p class="text-dark-gray d-flex align-items-center mt-3">
        <i class="material-icons icon-muted mr-2">event</i>
        <strong>Inventory Return</strong>
    </p>

    @foreach ($inventory_return_records as $inventory_return_record)
        <div class="row align-items-center projects-item mb-1">
            <div class="col-sm-auto mb-1 mb-sm-0">
                <div class="text-dark-gray">{{ $inventory_return_record->created_at->format('g:i a') }}</div>
            </div>
            <div class="col-sm">
                <div class="card m-0">
                    <div class="px-4 py-3">
                        <div class="row align-items-center">
                            <div class="col" style="min-width: 300px">
                                <div class="d-flex align-items-center">
                                    <a href="#" class="text-body"><strong class="text-15pt mr-2">
                                        Goods Receipt {{ $inventory_return_record->goods_receipt->reference_number }}<br>
                                        Returned x{{ $inventory_return_record->qty }} {{ $inventory_return_record->inventory->item->name }} 
                                    </strong></a>
                                    <span class="badge badge-success">{{ $inventory_return_record->uri }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <small class="text-dark mr-2">{{ $inventory_return_record->device }}</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <small class="text-dark-gray mr-2">User</small>
                                    <a href="#" class="d-flex align-items-middle">
                                        <span class="avatar avatar-xxs avatar-online mr-2">
                                            @if ($inventory_return_record->auth)
                                                @if ($inventory_return_record->auth->avatar)
                                                    <img src="{{ url($inventory_return_record->auth->avatar) }}" alt="{{ $inventory_return_record->auth->firstname }} {{ $inventory_return_record->auth->lastname }}" class="avatar-img rounded-circle">
                                                @else
                                                    <img src="{{ url(env('BIG_FOUR_ICON')) }}" alt="{{ $inventory_return_record->auth->firstname }} {{ $inventory_return_record->auth->lastname }}" class="avatar-img rounded-circle">
                                                @endif
                                            @else
                                                <img src="{{ url(env('BIG_FOUR_ICON')) }}" alt="anon" class="avatar-img rounded-circle">
                                            @endif
                                        </span>
                                        @if ($inventory_return_record->auth)
                                            {{ $inventory_return_record->auth->firstname }} {{ $inventory_return_record->auth->lastname }}
                                        @else
                                            Anonymous
                                        @endif
                                    </a>
                                </div>
                            </div>
                            <div class="col-auto d-flex align-items-center">
                                <i class="material-icons icon-muted mr-2">access_time</i>
                                {{ $inventory_return_record->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <div style="padding-top: 20px;">
        {{ $inventory_return_records->links() }}
    </div>
</div>


@include('layouts.auth.footer')