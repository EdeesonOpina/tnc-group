@include('layouts.auth.header')
@php
use App\Models\ServiceOrder;
use App\Models\ServiceOrderStatus;
use App\Models\ServiceOrderDetail;
use App\Models\ServiceOrderDetailStatus;
@endphp

<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Reports</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Job Orders</li>
                </ol>
            </nav>
            <h1 class="m-0">Job Orders</h1>
        </div>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')
    @include('layouts.partials.service-orders.top-tabs')

    <form action="{{ route('admin.reports.service-orders.search') }}" method="post">
        {{ csrf_field() }}
        <div class="card card-form d-flex flex-column flex-sm-row">
            <div class="card-form__body card-body-form-group flex">
                <div class="row">
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
                        <label><a href="{{ route('admin.reports.service-orders') }}" id="no-underline">Clear Filters</a></label>
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
                    <h4 class="card-header__title flex m-0">Sales</h4>
                    <div data-toggle="flatpickr" data-flatpickr-wrap="true" data-flatpickr-static="true" data-flatpickr-mode="range" data-flatpickr-alt-format="d/m/Y" data-flatpickr-date-format="d/m/Y">

                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Total Transactions</th>
                                <th>Total Sales</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($period as $date)
                                @php
                                    $formatted_date = $date->format('Y-m-d');

                                    $service_order_count = ServiceOrder::whereBetween('date_out', [$formatted_date . ' 00:00:00', $formatted_date  . ' 23:59:59'])
                                                        ->where('status', '!=', ServiceOrderStatus::INACTIVE)
                                                        ->count();
                                    $service_order_total = ServiceOrder::whereBetween('date_out', [$formatted_date . ' 00:00:00', $formatted_date  . ' 23:59:59'])
                                                        ->where('status', '!=', ServiceOrderStatus::INACTIVE)
                                                        ->sum('total');
                                @endphp
                                <tr>
                                    <td>{{ $date->format('F, d Y') }}</td>
                                    <td>{{ $service_order_count }}</td>
                                    <td>₱ {{ number_format($service_order_total, 2) }}</td>
                                    <td>
                                        <a href="{{ route('admin.reports.service-orders.filter', [$formatted_date, $formatted_date]) }}">
                                            <button class="btn btn-sm btn-primary">View</button>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.auth.footer')