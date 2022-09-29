@php
    use Carbon\Carbon;
    use App\Models\POSVat;
    use App\Models\Payment;
    use App\Models\POSDiscount;
    use App\Models\PaymentStatus;
    use App\Models\PaymentReceipt;
    use App\Models\PaymentReceiptStatus;

    $from_date = Carbon::now()->firstOfMonth() . ' 00:00:00';
    $to_date = Carbon::now()->lastOfMonth() . ' 23:59:59';

    $confirmed_discounts_total = PaymentReceipt::where('status', PaymentReceiptStatus::CONFIRMED)
                        ->sum('discount');

    $confirmed_vat_total = PaymentReceipt::where('status', PaymentReceiptStatus::CONFIRMED)
                        ->sum('vat');

    $for_delivery_discounts_total = PaymentReceipt::where('status', PaymentReceiptStatus::FOR_DELIVERY)
                        ->sum('discount');

    $for_delivery_vat_total = PaymentReceipt::where('status', PaymentReceiptStatus::FOR_DELIVERY)
                        ->sum('vat');

    $delivered_discounts_total = PaymentReceipt::where('status', PaymentReceiptStatus::DELIVERED)
                        ->sum('discount');

    $delivered_vat_total = PaymentReceipt::where('status', PaymentReceiptStatus::DELIVERED)
                        ->sum('vat');

    $cancelled_discounts_total = PaymentReceipt::where('status', PaymentReceiptStatus::CANCELLED)
                        ->sum('discount');

    $cancelled_vat_total = PaymentReceipt::where('status', PaymentReceiptStatus::CANCELLED)
                        ->sum('vat');

    $confirmed_count = Payment::where('status', PaymentStatus::CONFIRMED)
                        ->count();

    $for_delivery_count = Payment::where('status', PaymentStatus::FOR_DELIVERY)
                        ->count();

    $delivered_count = Payment::where('status', PaymentStatus::DELIVERED)
                        ->count();

    $cancelled_count = Payment::where('status', PaymentStatus::CANCELLED)
                        ->count();

    $confirmed_total = Payment::where('status', PaymentStatus::CONFIRMED)
                        ->sum('total');

    $for_delivery_total = Payment::where('status', PaymentStatus::FOR_DELIVERY)
                        ->sum('total');

    $delivered_total = Payment::where('status', PaymentStatus::DELIVERED)
                        ->sum('total');

    $cancelled_total = Payment::where('status', PaymentStatus::CANCELLED)
                        ->sum('total');
@endphp

<div class="row card-group-row">
    <div class="col-lg-3 col-md-4 card-group-row__col">
        <div class="card bg-white card-group-row__card">
            <div class="align-items-center container" id="space-step">
                <h6 class=""><i class="material-icons">visibility</i></h6>
                <h6 class="">Confirmed</h6>
                <h6 class="">{{ $confirmed_count }} no. of item purchase</h6>
                <h3 class="">₱ {{ number_format((($confirmed_total + $confirmed_vat_total) - $confirmed_discounts_total), 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-4 card-group-row__col">
        <div class="card bg-info text-white card-group-row__card">
            <div class="align-items-center container" id="space-step">
                <h6 class="text-white"><i class="material-icons">airport_shuttle</i></h6>
                <h6 class="text-white">For Delivery</h6>
                <h6 class="text-white">{{ $for_delivery_count }} no. of item purchase</h6>
                <h3 class="text-white">₱ {{ number_format((($for_delivery_total + $for_delivery_vat_total) - $for_delivery_discounts_total), 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-4 card-group-row__col">
        <div class="card bg-success text-white card-group-row__card">
            <div class="align-items-center container" id="space-step">
                <h6 class="text-white"><i class="material-icons">check_circle</i></h6>
                <h6 class="text-white">Delivered</h6>
                <h6 class="text-white">{{ $delivered_count }} no. of item purchase</h6>
                <h3 class="text-white">₱ {{ number_format((($delivered_total + $delivered_vat_total) - $delivered_discounts_total), 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-4 card-group-row__col">
        <div class="card bg-danger text-white card-group-row__card">
            <div class="align-items-center container" id="space-step">
                <h6 class="text-white"><i class="material-icons">cancel</i></h6>
                <h6 class="text-white">Cancelled</h6>
                <h6 class="text-white">{{ $cancelled_count }} no. of item purchase</h6>
                <h3 class="text-white">₱ {{ number_format((($cancelled_total + $cancelled_vat_total) - $cancelled_discounts_total), 2) }}</h3>
            </div>
        </div>
    </div>
</div>