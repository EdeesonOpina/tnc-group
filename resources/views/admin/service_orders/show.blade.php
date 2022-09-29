@include('layouts.auth.header')
@php
    use Carbon\Carbon;
    use App\Models\ServiceOrder;
    use App\Models\ServiceOrderDetail;
    use App\Models\ServiceOrderStatus;
    use App\Models\ServiceOrderDetailStatus;
@endphp

<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('operations.service-orders') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Job Orders</li>
                </ol>
            </nav>
            <h1 class="m-0">Job Orders</h1>
        </div>
        <a href="{{ route('jo') }}" class="btn btn-primary"><i class="material-icons">add</i> Create JO</a>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')
    @include('layouts.partials.service-orders.top-tabs')

    <form action="{{ route('operations.service-orders.search') }}" method="post">
        {{ csrf_field() }}
        <div class="card card-form d-flex flex-column flex-sm-row">
            <div class="card-form__body card-body-form-group flex">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>JO#</label>
                            <input name="jo_number" type="text" class="form-control" placeholder="Search by JO number" value="{{ old('jo_number') }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Customer</label>
                            <input name="name" type="text" class="form-control" placeholder="Search by name" value="{{ old('name') }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" data-toggle="select">
                                @if (old('status'))
                                    @if (old('status') != '*')
                                        @if (old('status') == ServiceOrderStatus::PENDING)
                                            <option value="{{ old('status') }}">Pending</option>
                                        @endif

                                        @if (old('status') == ServiceOrderStatus::BACK_JOB)
                                            <option value="{{ old('status') }}">Back Job</option>
                                        @endif

                                        @if (old('status') == ServiceOrderStatus::FOR_RELEASE)
                                            <option value="{{ old('status') }}">For Release</option>
                                        @endif

                                        @if (old('status') == ServiceOrderStatus::FOR_REPAIR)
                                            <option value="{{ old('status') }}">For Repair</option>
                                        @endif

                                        @if (old('status') == ServiceOrderStatus::COMPLETED)
                                            <option value="{{ old('status') }}">COMPLETED</option>
                                        @endif

                                        @if (old('status') == ServiceOrderStatus::CANCELLED)
                                            <option value="{{ old('status') }}">Cancelled</option>
                                        @endif
                                    @endif
                                @endif
                                <option value="*">All</option>
                                <option value="{{ ServiceOrderStatus::PENDING }}">Pending</option>
                                <option value="{{ ServiceOrderStatus::BACK_JOB }}">Back Job</option>
                                <option value="{{ ServiceOrderStatus::FOR_REPAIR }}">For Repair</option>
                                <option value="{{ ServiceOrderStatus::FOR_RELEASE }}">For Release</option>
                                <option value="{{ ServiceOrderStatus::COMPLETED }}">Completed</option>
                                <option value="{{ ServiceOrderStatus::CANCELLED }}">Cancelled</option>
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
                        <label><a href="{{ route('operations.service-orders') }}" id="no-underline">Clear Filters</a></label>
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
                    <h4 class="card-header__title flex m-0">Service Orders</h4>
                    <div data-toggle="flatpickr" data-flatpickr-wrap="true" data-flatpickr-static="true" data-flatpickr-mode="range" data-flatpickr-alt-format="d/m/Y" data-flatpickr-date-format="d/m/Y">
                        
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table">JO#</th>
                                <th id="compact-table">Customer</th>
                                <th id="compact-table">Technical</th>
                                <th id="compact-table">No. of Services</th>
                                <th id="compact-table">Grand Total</th>
                                <th id="compact-table">Status</th>
                                <th id="compact-table">Date In</th>
                                <th id="compact-table">Date Out</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                            @foreach($service_orders as $service_order)
                            @php
                                $service_order_details_count = ServiceOrderDetail::where('service_order_id', $service_order->id)
                                                        ->where('status', '!=', ServiceOrderDetailStatus::INACTIVE)
                                                        ->sum('qty');

                                $service_order_details_total = ServiceOrderDetail::where('service_order_id', $service_order->id)
                                                        ->where('status', '!=', ServiceOrderDetailStatus::INACTIVE)
                                                        ->sum('total');
                            @endphp
                                <tr>
                                    <td id="compact-table">
                                        <strong># {{ $service_order->jo_number }}</strong>
                                        <div class="d-flex">
                                            <a href="{{ route('operations.service-orders.view', [$service_order->jo_number]) }}" id="table-letter-margin">View</a> | 

                                            <!-- IF PENDING -->
                                            @if ($service_order->status == ServiceOrderStatus::PENDING)
                                                <a href="#" data-href="{{ route('operations.service-orders.cancel', [$service_order->jo_number]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Cancel</a> | 
                                            @endif

                                            <!-- IF FOR REPAIR -->
                                            @if ($service_order->status == ServiceOrderStatus::FOR_REPAIR)
                                                <a href="#" data-href="{{ route('operations.service-orders.for-release', [$service_order->jo_number]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">For Release</a> | 

                                                <a href="#" data-href="{{ route('operations.service-orders.cancel', [$service_order->jo_number]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Cancel</a> | 
                                            @endif

                                            <!-- IF FOR DELIVERY -->
                                            @if ($service_order->status == ServiceOrderStatus::FOR_RELEASE)
                                                <a href="#" data-toggle="modal" data-target="#date-out-{{ $service_order->id }}" id="space-table">Completed</a> | 

                                                <a href="#" data-href="{{ route('operations.service-orders.cancel', [$service_order->jo_number]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Cancel</a> | 
                                            @endif

                                            <!-- IF COMPLETED -->
                                            @if ($service_order->status == ServiceOrderStatus::COMPLETED)
                                                <!-- <a href="#" data-href="{{ route('operations.service-orders.cancel', [$service_order->jo_number]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Cancel</a> |  -->

                                                <a href="#" data-toggle="modal" data-target="#back-job-{{ $service_order->id }}" id="space-table">Back Job</a> | 
                                            @endif

                                            @if (auth()->user()->role == 'Super Admin')
                                                <!-- IF NOT INACTIVE -->
                                                @if ($service_order->status != ServiceOrderStatus::INACTIVE)
                                                    <a href="#" data-href="{{ route('operations.service-orders.delete', [$service_order->jo_number]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Delete</a>
                                                @else
                                                    <!-- <a href="#" data-href="{{ route('operations.service-orders.recover', [$service_order->jo_number]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Recover</a> -->
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                    <td id="compact-table"><i class="material-icons icon-16pt mr-1 text-muted">face</i> {{ $service_order->user->firstname }} {{ $service_order->user->lastname }}</td>
                                    <td id="compact-table">
                                        @if ($service_order->authorized_user)
                                            <i class="material-icons icon-16pt mr-1 text-muted">face</i> {{ $service_order->authorized_user->firstname }} {{ $service_order->authorized_user->lastname }} <a href="{{ route('operations.service-orders.technical', [$service_order->jo_number]) }}"><i class="material-icons icon-16pt text-success" data-toggle="tooltip" data-placement="top" title="Assign Technical">edit</i></a>
                                        @endif
                                    </td>
                                    <td id="compact-table">{{ $service_order_details_count }}</td>
                                    <td id="compact-table">P{{ number_format($service_order_details_total, 2) }}</td>
                                    <td>
                                        @if ($service_order->status == ServiceOrderStatus::PENDING)
                                            <div class="badge badge-warning ml-2">PENDING</div>
                                        @elseif ($service_order->status == ServiceOrderStatus::BACK_JOB)
                                            <div class="badge badge-warning ml-2">BACK JOB</div>
                                        @elseif ($service_order->status == ServiceOrderStatus::FOR_RELEASE)
                                            <div class="badge badge-success ml-2">FOR RELEASE</div>
                                        @elseif ($service_order->status == ServiceOrderStatus::COMPLETED)
                                            <div class="badge badge-success ml-2">COMPLETED</div>
                                        @elseif ($service_order->status == ServiceOrderStatus::FOR_REPAIR)
                                            <div class="badge badge-success ml-2">FOR REPAIR</div>
                                        @elseif ($service_order->status == ServiceOrderStatus::FOR_RELEASE)
                                            <div class="badge badge-success ml-2">FOR RELEASE</div>
                                        @elseif ($service_order->status == ServiceOrderStatus::CANCELLED)
                                            <div class="badge badge-danger ml-2">CANCELLED</div>
                                        @elseif ($service_order->status == ServiceOrderStatus::INACTIVE)
                                            <div class="badge badge-danger ml-2">INACTIVE</div>
                                        @endif
                                    </td>
                                    <td id="compact-table"><i class="material-icons icon-16pt text-muted mr-1">today</i> {{ $service_order->created_at->format('M d Y') }}</td>

                                    <td id="compact-table">
                                        @if ($service_order->date_out)
                                            {{ date("M d Y", strtotime($service_order->date_out)) }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if (count($service_orders) <= 0)
                        <div style="padding: 20px">
                            <center><i class="material-icons icon-16pt mr-1 text-muted">assignment</i> No record/s found</center>
                        </div>
                    @endif
                </div>
            </div>
            {{ $service_orders->links() }}
        </div>
    </div>
</div>

@include('layouts.auth.footer')
@include('layouts.modals.jo.all-date-out')
@include('layouts.modals.jo.all-back-job')