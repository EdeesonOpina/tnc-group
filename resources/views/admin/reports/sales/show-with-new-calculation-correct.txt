@include('layouts.auth.header')
@php
use Carbon\Carbon;
use App\Models\POSVat;
use App\Models\Payment;
use App\Models\POSDiscount;
use App\Models\PaymentStatus;
use App\Models\PaymentReceipt;
use App\Models\PaymentReceiptStatus;

use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\POSVatStatus;
use App\Models\POSDiscountStatus;
@endphp

<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Reports</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Sales</li>
                </ol>
            </nav>
            <h1 class="m-0">Sales</h1>
        </div>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.reports.sales')
    @include('layouts.partials.alerts')

    <form action="{{ route('admin.reports.sales.search') }}" method="post">
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
                        <label><a href="{{ route('admin.reports.sales') }}" id="no-underline">Clear Filters</a></label>
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
                    <div>
                        @if (request()->is('admin/reports/sales') || request()->is('admin/reports/sales/*'))
                            @if (str_contains(url()->current(), '/month'))
                                <a href="{{ route('admin.reports.sales.month', [Carbon::parse($month)->subMonth(1)->format('Y-m-d')]) }}" id="space-table">
                                <i class="material-icons icon-20pt">keyboard_arrow_left</i>
                                Last Month
                                </a>
                                <a href="{{ route('admin.reports.sales.month', [Carbon::parse($month)->addMonth(1)->format('Y-m-d')]) }}">
                                Next Month
                                <i class="material-icons icon-20pt">keyboard_arrow_right</i>
                                </a>
                            @else
                                <a href="{{ route('admin.reports.sales.month', [Carbon::now()->subMonth(1)->format('Y-m-d')]) }}" id="space-table">
                                <i class="material-icons icon-20pt">keyboard_arrow_left</i>
                                Last Month
                                </a>
                                <a href="{{ route('admin.reports.sales.month', [Carbon::now()->addMonth(1)->format('Y-m-d')]) }}">
                                Next Month
                                <i class="material-icons icon-20pt">keyboard_arrow_right</i>
                                </a>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Grand Total</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($period as $date)
                            @php
                                $grand_total = 0;
                                $overall_costing_total = 0;
                                $overall_profit_total = 0;

                                $formatted_date = $date->format('Y-m-d');

                                $payments = Payment::whereBetween('created_at', [$formatted_date . ' 00:00:00', $formatted_date  . ' 23:59:59'])
                                                ->where('status', '!=', PaymentStatus::INACTIVE)
                                                ->get();
                                
                            @endphp
                                @foreach($payments->unique('so_number') as $payment)
                                @php
                                    $payment_receipt = PaymentReceipt::where('so_number', $payment->so_number)->first();

                                    $payment_items = Payment::where('so_number', $payment->so_number)
                                                          ->where('status', '!=', PaymentStatus::INACTIVE)
                                                          ->whereBetween('created_at', [$formatted_date . ' 00:00:00', $formatted_date  . ' 23:59:59'])
                                                          ->get();

                                    $payments_count = Payment::where('so_number', $payment->so_number)
                                                            ->where('status', '!=', PaymentStatus::INACTIVE)
                                                            ->whereBetween('created_at', [$formatted_date . ' 00:00:00', $formatted_date  . ' 23:59:59'])
                                                            ->sum('qty');

                                    $payments_total = Payment::where('so_number', $payment->so_number)
                                                            ->where('status', '!=', PaymentStatus::INACTIVE)
                                                            ->whereBetween('created_at', [$formatted_date . ' 00:00:00', $formatted_date  . ' 23:59:59'])
                                                            ->sum('total');

                                    $payments_discount = POSDiscount::where('so_number', $payment->so_number)
                                                                ->where('status', POSDiscountStatus::ACTIVE)
                                                                ->whereBetween('created_at', [$formatted_date . ' 00:00:00', $formatted_date  . ' 23:59:59'])
                                                                ->first()
                                                                ->price ?? 0;

                                    $payments_vat = POSVat::where('so_number', $payment->so_number)
                                                                ->where('status', POSVatStatus::ACTIVE)
                                                                ->whereBetween('created_at', [$formatted_date . ' 00:00:00', $formatted_date  . ' 23:59:59'])
                                                                ->first()
                                                                ->price ?? 0;

                                    if ($payment->status == PaymentStatus::DELIVERED) {
                                        $total_with_deductions = (($payments_total + $payments_vat) - $payments_discount); /* to be calculated */
                                        $display_total = $total_with_deductions; /* display real total value either DELIVERED OR CANCELLED */

                                        $grand_total += $total_with_deductions;
                                        
                                    } 

                                    if ($payment->status == PaymentStatus::CANCELLED) {
                                      $display_total = (($payments_total + $payments_vat) - $payments_discount); /* display real total value either DELIVERED OR CANCELLED */
                                    }

                                    $profit_arr = [];
                                    $cost_arr = [];  

                                    $overall_profit_total = $grand_total;
                                @endphp
                                @endforeach
                                <tr>
                                    <td>{{ $date->format('F, d Y') }}</td>
                                    <td>₱ {{ number_format($grand_total, 2) }}</td>
                                    <td>
                                        <a href="{{ route('admin.reports.sales.filter', [$formatted_date, $formatted_date]) }}" target="_blank">
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