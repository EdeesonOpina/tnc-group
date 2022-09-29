@php
    use Carbon\Carbon;
    use App\Models\Payment;
    use App\Models\ServiceOrder;
    use App\Models\ServiceOrderDetail;
    use App\Models\ServiceOrderStatus;
    use App\Models\ServiceOrderDetailStatus;

    $from_date = Carbon::now()->firstOfMonth() . ' 00:00:00';
    $to_date = Carbon::now()->lastOfMonth() . ' 23:59:59';

    $pending_count = ServiceOrder::where('status', ServiceOrderStatus::PENDING)
                        ->count();

    $for_repair_count = ServiceOrder::where('status', ServiceOrderStatus::FOR_REPAIR)
                        ->count();

    $for_release_count = ServiceOrder::where('status', ServiceOrderStatus::FOR_RELEASE)
                        ->count();

    $completed_count = ServiceOrder::where('status', ServiceOrderStatus::COMPLETED)
                        ->count();

    $back_job_count = ServiceOrder::where('status', ServiceOrderStatus::BACK_JOB)
                        ->count();

    $cancelled_count = ServiceOrder::where('status', ServiceOrderStatus::CANCELLED)
                        ->count();

    $pending_total = ServiceOrderDetail::leftJoin('service_orders', 'service_order_details.service_order_id', '=', 'service_orders.id')
                        ->select('service_orders.*')
                        ->where('service_orders.status', ServiceOrderStatus::PENDING)
                        ->sum('service_order_details.total');

    $for_repair_total = ServiceOrderDetail::leftJoin('service_orders', 'service_order_details.service_order_id', '=', 'service_orders.id')
                        ->select('service_orders.*')
                        ->where('service_orders.status', ServiceOrderStatus::FOR_REPAIR)
                        ->sum('service_order_details.total');

    $for_release_total = ServiceOrderDetail::leftJoin('service_orders', 'service_order_details.service_order_id', '=', 'service_orders.id')
                        ->select('service_orders.*')
                        ->where('service_orders.status', ServiceOrderStatus::FOR_RELEASE)
                        ->sum('service_order_details.total');

    $completed_total = ServiceOrderDetail::leftJoin('service_orders', 'service_order_details.service_order_id', '=', 'service_orders.id')
                        ->select('service_orders.*')
                        ->where('service_orders.status', ServiceOrderStatus::COMPLETED)
                        ->sum('service_order_details.total');

    $back_job_total = ServiceOrderDetail::leftJoin('service_orders', 'service_order_details.service_order_id', '=', 'service_orders.id')
                        ->select('service_orders.*')
                        ->where('service_orders.status', ServiceOrderStatus::BACK_JOB)
                        ->sum('service_order_details.total');

    $cancelled_total = ServiceOrderDetail::leftJoin('service_orders', 'service_order_details.service_order_id', '=', 'service_orders.id')
                        ->select('service_orders.*')
                        ->where('service_orders.status', ServiceOrderStatus::CANCELLED)
                        ->sum('service_order_details.total');
@endphp

<div class="row card-group-row">
    <div class="col-lg-3 col-md-3 card-group-row__col">
        <div class="card bg-white card-group-row__card">
            <div class="align-items-center container" id="space-step">
                <h6 class=""><i class="material-icons">insert_chart</i></h6>
                <h6 class="">For Repair</h6>
                <h6 class="">{{ $for_repair_count }} no. of service orders</h6>
                <h3 class="">₱ {{ number_format($for_repair_total, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 card-group-row__col">
        <div class="card bg-info text-white card-group-row__card">
            <div class="align-items-center container" id="space-step">
                <h6 class="text-white"><i class="material-icons">check_circle</i></h6>
                <h6 class="text-white">For Release</h6>
                <h6 class="text-white">{{ $for_release_count }} no. of service orders</h6>
                <h3 class="text-white">₱ {{ number_format($for_release_total, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 card-group-row__col">
        <div class="card bg-success text-white card-group-row__card">
            <div class="align-items-center container" id="space-step">
                <h6 class="text-white"><i class="material-icons">check_circle</i></h6>
                <h6 class="text-white">Completed</h6>
                <h6 class="text-white">{{ $completed_count }} no. of service orders</h6>
                <h3 class="text-white">₱ {{ number_format($completed_total, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 card-group-row__col">
        <div class="card bg-danger text-white card-group-row__card">
            <div class="align-items-center container" id="space-step">
                <h6 class="text-white"><i class="material-icons">date_range</i></h6>
                <h6 class="text-white">Cancelled</h6>
                <h6 class="text-white">{{ $cancelled_count }} no. of service orders</h6>
                <h3 class="text-white">₱ {{ number_format($cancelled_total, 2) }}</h3>
            </div>
        </div>
    </div>
</div>