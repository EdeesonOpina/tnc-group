@php
    use Carbon\Carbon;
    use App\Models\Payable;
    use App\Models\PayableStatus;

    $from_date = Carbon::now()->firstOfMonth() . ' 00:00:00';
    $to_date = Carbon::now()->lastOfMonth() . ' 23:59:59';

    $pending_count = Payable::whereBetween('created_at', [
                            $from_date, $to_date
                        ])
                        ->where('status', PayableStatus::PENDING)
                        ->count();

    $paid_count = Payable::whereBetween('created_at', [
                            $from_date, $to_date
                        ])
                        ->where('status', PayableStatus::PAID)
                        ->count();

    $pending_total = Payable::whereBetween('created_at', [
                            $from_date, $to_date
                        ])
                        ->where('status', PayableStatus::PENDING)
                        ->sum('price');

    $paid_total = Payable::whereBetween('created_at', [
                            $from_date, $to_date
                        ])
                        ->where('status', PayableStatus::PAID)
                        ->sum('price');
@endphp

<div class="row card-group-row">
    <div class="col-lg-6 col-md-6 card-group-row__col">
        <div class="card bg-white card-group-row__card">
            <a href="{{ route('admin.reports.payables.filter', [PayableStatus::PENDING, Carbon::now()->firstOfMonth()->format('Y-m-d'), Carbon::now()->lastOfMonth()->format('Y-m-d')]) }}" target="_blank" class="no-underline">
                <div class="align-items-center container" id="space-step">
                    <h6 class=""><i class="material-icons">visibility</i></h6>
                    <h6 class="">Pending</h6>
                    <h6 class="">{{ $pending_count }} no. of item purchase</h6>
                    <h3 class="">₱ {{ number_format($pending_total, 2) }}</h3>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 card-group-row__col">
        <div class="card bg-success text-white card-group-row__card">
            <a href="{{ route('admin.reports.payables.filter', [PayableStatus::PAID, Carbon::now()->firstOfMonth()->format('Y-m-d'), Carbon::now()->lastOfMonth()->format('Y-m-d')]) }}" target="_blank" class="no-underline">
                <div class="align-items-center container" id="space-step">
                    <h6 class="text-white"><i class="material-icons">check_circle</i></h6>
                    <h6 class="text-white">Paid</h6>
                    <h6 class="text-white">{{ $paid_count }} no. of item purchase</h6>
                    <h3 class="text-white">₱ {{ number_format($paid_total, 2) }}</h3>
                </div>
            </a>
        </div>
    </div>
</div>