@php
    use Carbon\Carbon;
    use App\Models\POSVat;
    use App\Models\Payment;
    use App\Models\POSDiscount; 
    use App\Models\PaymentCredit;
    use App\Models\PaymentStatus;
    use App\Models\PaymentReceipt;
    use App\Models\POSDiscountStatus;
    use App\Models\POSVatStatus;
    use App\Models\PaymentCreditRecord;
    use App\Models\PaymentCreditStatus;
    use App\Models\PaymentReceiptStatus;
    use App\Models\PaymentCreditRecordStatus;

    $overall_payments_total = 0;
    $overall_profit_total = 0;
    $overall_cost_total = 0;

    $overdue_overall_payments_total = 0;
    $overdue_overall_profit_total = 0;
    $overdue_overall_cost_total = 0;

    $unpaid_overall_payments_total = 0;
    $unpaid_overall_profit_total = 0;
    $unpaid_overall_cost_total = 0;

    $pending_overall_payments_total = 0;
    $pending_overall_profit_total = 0;
    $pending_overall_cost_total = 0;

    $partially_paid_overall_payments_total = 0;
    $partially_paid_overall_profit_total = 0;
    $partially_paid_overall_cost_total = 0;
    $partially_paid_balance = 0;
    $partially_paid_remaining_balance_overall_payments_total = 0;

    $fully_paid_overall_payments_total = 0;
    $fully_paid_overall_profit_total = 0;
    $fully_paid_overall_cost_total = 0;

    $paid_balance = 0;

    $from_date = Carbon::now()->firstOfMonth() . ' 00:00:00';
    $to_date = Carbon::now()->lastOfMonth() . ' 23:59:59';

    // $credit_count = PaymentCredit::where('status', PaymentCreditStatus::CREDIT)
    //                        ->count();

    // $partially_paid_count = PaymentCredit::where('status', PaymentCreditStatus::PARTIALLY_PAID)
    //                        ->count();

    // $pending_count = PaymentCredit::where('status', PaymentCreditStatus::PENDING)
    //                        ->count();

    // $fully_paid_count = PaymentCredit::where('status', PaymentCreditStatus::FULLY_PAID)
    //                        ->count();

    // $unpaid_count = PaymentCredit::where('status', PaymentCreditStatus::UNPAID)
    //                        ->count();

    // $overdue_count = PaymentCredit::where('status', PaymentCreditStatus::OVERDUE)
    //                        ->count();

    $payment_credits = PaymentCredit::where('status', '!=', PaymentCreditStatus::INACTIVE)
                            ->get();

    $credit_count = 0;
    $partially_paid_count = 0;
    $pending_count = 0;
    $fully_paid_count = 0;
    $unpaid_count = 0;
    $overdue_count = 0;

    foreach($payment_credits as $payment_credit) {
        $payment_receipt = PaymentReceipt::where('so_number', $payment_credit->so_number)->first();

        $pending_balance = PaymentCreditRecord::where('so_number', $payment_receipt->so_number)
                                          ->where('status', PaymentCreditRecordStatus::PENDING)
                                          ->sum('price');

        $paid_balance = PaymentCreditRecord::where('so_number', $payment_receipt->so_number)
                                          ->where('status', PaymentCreditRecordStatus::APPROVED)
                                          ->sum('price');

        $payments_total = Payment::where('so_number', $payment_receipt->so_number)
                                ->where('status', PaymentStatus::DELIVERED)
                                ->sum('total');

        $cost_total = Payment::where('so_number', $payment_receipt->so_number)
                                ->where('status', PaymentStatus::DELIVERED)
                                ->sum('cost');

        $pos_cash_discount = POSDiscount::where('so_number', $payment_receipt->so_number)
                                        ->where('status', POSDiscountStatus::ACTIVE)
                                        ->first()
                                        ->price ?? 0;

        $pos_cash_vat = POSVat::where('so_number', $payment_receipt->so_number)
                              ->where('status', POSDiscountStatus::ACTIVE)
                              ->first()
                              ->price ?? 0;

        if ($payment_receipt->status == PaymentReceiptStatus::DELIVERED) {
            if ($payment_credit->status == PaymentCreditStatus::OVERDUE) {
                $overdue_total_with_deductions = ($payments_total + $pos_cash_vat) - $pos_cash_discount;
                $overdue_profit_total = $overdue_total_with_deductions - $cost_total;

                $overdue_overall_payments_total += $overdue_total_with_deductions;
                $overdue_overall_cost_total += $cost_total;
                $overdue_overall_profit_total += $overdue_profit_total;

                $overdue_count += 1;
            }

            if ($payment_credit->status == PaymentCreditStatus::UNPAID) {
                $unpaid_total_with_deductions = ($payments_total + $pos_cash_vat) - $pos_cash_discount;
                $unpaid_profit_total = $unpaid_total_with_deductions - $cost_total;

                $unpaid_overall_payments_total += $unpaid_total_with_deductions;
                $unpaid_overall_cost_total += $cost_total;
                $unpaid_overall_profit_total += $unpaid_profit_total;

                $unpaid_count += 1;
            }

            if ($payment_credit->status == PaymentCreditStatus::PENDING) {
                $pending_total_with_deductions = ($payments_total + $pos_cash_vat) - $pos_cash_discount;
                $pending_profit_total = $pending_total_with_deductions - $cost_total;

                $pending_overall_payments_total += $pending_total_with_deductions;
                $pending_overall_cost_total += $cost_total;
                $pending_overall_profit_total += $pending_profit_total;

                $pending_count += 1;
            }

            if ($payment_credit->status == PaymentCreditStatus::PARTIALLY_PAID) {
                $partially_paid_total_with_deductions = ($payments_total + $pos_cash_vat) - $pos_cash_discount;
                $partially_paid_profit_total = $partially_paid_total_with_deductions - $cost_total;

                $partially_paid_overall_payments_total += $partially_paid_total_with_deductions;
                $partially_paid_overall_cost_total += $cost_total;
                $partially_paid_overall_profit_total += $partially_paid_profit_total;

                $partially_paid_balance = PaymentCreditRecord::where('so_number', $payment_receipt->so_number)
                                                  ->where('status', PaymentCreditRecordStatus::APPROVED)
                                                  ->sum('price');

                $partially_paid_remaining_balance_overall_payments_total += $partially_paid_total_with_deductions - $partially_paid_balance;

                $partially_paid_count += 1;
            }

            if ($payment_credit->status == PaymentCreditStatus::FULLY_PAID) {
                $fully_paid_total_with_deductions = ($payments_total + $pos_cash_vat) - $pos_cash_discount;
                $fully_paid_profit_total = $fully_paid_total_with_deductions - $cost_total;

                $fully_paid_overall_payments_total += $fully_paid_total_with_deductions;
                $fully_paid_overall_cost_total += $cost_total;
                $fully_paid_overall_profit_total += $fully_paid_profit_total;

                $fully_paid_count += 1;
            }

            $total_with_deductions = ($payments_total + $pos_cash_vat) - $pos_cash_discount;
            $profit_total = $total_with_deductions - $cost_total;

            $overall_payments_total += $total_with_deductions;
            $overall_cost_total += $cost_total;
            $overall_profit_total += $profit_total;
        }
    }
@endphp

<div class="row card-group-row">
    <div class="col-lg-4 col-md-4 card-group-row__col">
        <div class="card bg-white card-group-row__card">
            <a href="{{ route('admin.reports.payment-credits.not-in.filter', ['*', PaymentCreditStatus::FULLY_PAID, '*', '*']) }}" target="_blank" class="no-underline">
            <div class="align-items-center container" id="space-step">
                <h6 class=""><i class="material-icons">insert_chart</i></h6>
                <h6 class="">Credit</h6>
                <h6 class="">{{ $overdue_count + $unpaid_count + $pending_count + $partially_paid_count }} no. of payment credits</h6>
                <h3 class="">₱ {{ number_format($overdue_overall_payments_total + $unpaid_overall_payments_total + $partially_paid_remaining_balance_overall_payments_total + $pending_overall_payments_total, 2) }}</h3>
            </div>
            </a>
        </div>
    </div>

    <div class="col-lg-4 col-md-4 card-group-row__col">
        <div class="card bg-danger text-white card-group-row__card">
            <a href="{{ route('admin.reports.payment-credits.filter', ['*', PaymentCreditStatus::OVERDUE, '*', '*']) }}" target="_blank" class="no-underline">
            <div class="align-items-center container" id="space-step">
                <h6 class="text-white"><i class="material-icons">date_range</i></h6>
                <h6 class="text-white">Overdue</h6>
                <h6 class="text-white">{{ $overdue_count }} no. of payment credits</h6>
                <h3 class="text-white">₱ {{ number_format($overdue_overall_payments_total, 2) }}</h3>
            </div>
            </a>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-4 card-group-row__col">
        <div class="card bg-dark card-group-row__card">
            <a href="{{ route('admin.reports.payment-credits.filter', ['*', PaymentCreditStatus::UNPAID, '*', '*']) }}" target="_blank" class="no-underline">
            <div class="align-items-center container" id="space-step">
                <h6 class="text-white"><i class="material-icons">insert_chart</i></h6>
                <h6 class="text-white">Unpaid</h6>
                <h6 class="text-white">{{ $unpaid_count }} no. of payment credits</h6>
                <h3 class="text-white">₱ {{ number_format($unpaid_overall_payments_total, 2) }}</h3>
            </div>
            </a>
        </div>
    </div>
</div>

<div class="row card-group-row">
    <div class="col-lg-4 col-md-4 card-group-row__col">
        <div class="card bg-warning text-white card-group-row__card">
            <a href="{{ route('admin.reports.payment-credits.filter', ['*', PaymentCreditStatus::PENDING, '*', '*']) }}" target="_blank" class="no-underline">
            <div class="align-items-center container" id="space-step">
                <h6 class="text-white"><i class="material-icons">visibility</i></h6>
                <h6 class="text-white">Pending</h6>
                <h6 class="text-white">{{ $pending_count }} no. of payment credits</h6>
                <h3 class="text-white">₱ {{ number_format($pending_overall_payments_total, 2) }}</h3>
            </div>
            </a>
        </div>
    </div>

    <div class="col-lg-4 col-md-4 card-group-row__col">
        <div class="card bg-info text-white card-group-row__card">
            <a href="{{ route('admin.reports.payment-credits.filter', ['*', PaymentCreditStatus::PARTIALLY_PAID, '*', '*']) }}" target="_blank" class="no-underline">
            <div class="align-items-center container" id="space-step">
                <h6 class="text-white"><i class="material-icons">check_circle</i></h6>
                <h6 class="text-white">Partially Paid</h6>
                <h6 class="text-white">{{ $partially_paid_count }} no. of payment credits</h6>
                <h6 class="text-white">Remaining: ₱ {{ number_format($partially_paid_remaining_balance_overall_payments_total, 2) }}</h6>
                <h6 class="text-white">Total: ₱ {{ number_format($partially_paid_overall_payments_total, 2) }}</h6>
            </div>
            </a>
        </div>
    </div>

    <div class="col-lg-4 col-md-4 card-group-row__col">    
        <div class="card bg-success text-white card-group-row__card">
            <a href="{{ route('admin.reports.payment-credits.filter', ['*', PaymentCreditStatus::FULLY_PAID, '*', '*']) }}" target="_blank" class="no-underline">
            <div class="align-items-center container" id="space-step">
                <h6 class="text-white"><i class="material-icons">attach_money</i></h6>
                <h6 class="text-white">Fully Paid</h6>
                <h6 class="text-white">{{ $fully_paid_count }} no. of payment credits</h6>
                <h3 class="text-white">₱ {{ number_format($fully_paid_overall_payments_total, 2) }}</h3>
            </div>
            </a>
        </div>
    </div>
    
</div>