@include('layouts.auth.header')
@php
    use Carbon\Carbon;
    use App\Models\CashAdvanceStatus;
    use App\Models\CashAdvancePayment;
    use App\Models\CashAdvancePaymentStatus;
@endphp

<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('accounting.cash-advances') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Cash Advances</li>
                </ol>
            </nav>
            <h1 class="m-0">Cash Advances</h1>
        </div>
        <a href="{{ route('accounting.cash-advances.add') }}" class="btn btn-primary"><i class="material-icons">add</i> Add</a>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')

    <form action="{{ route('accounting.cash-advances.search') }}" method="post">
        {{ csrf_field() }}
        <div class="card card-form d-flex flex-column flex-sm-row">
            <div class="card-form__body card-body-form-group flex">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>CA#</label>
                            <input name="ca_number" type="text" class="form-control" placeholder="Search by CA number" value="{{ old('ca_number') }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Employee</label>
                            <input name="name" type="text" class="form-control" placeholder="Search by name" value="{{ old('name') }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" data-toggle="select">
                                <option value="*">All</option>
                                <option value="{{ CashAdvanceStatus::UNPAID }}">UNPAID</option>
                                <option value="{{ CashAdvanceStatus::PARTIALLY_PAID }}">PARTIALLY PAID</option>
                                <option value="{{ CashAdvanceStatus::DISAPPROVED }}">DISAPPROVED</option>
                                <option value="{{ CashAdvanceStatus::FOR_APPROVAL }}">FOR APPROVAL</option>
                                <option value="{{ CashAdvanceStatus::FOR_FINAL_APPROVAL }}">FOR FINAL APPROVAL</option>
                                <option value="{{ CashAdvanceStatus::FULLY_PAID }}">FULLY PAID</option>
                            </select>
                        </div>
                    </div>
                </div>
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
                    <div class="col">
                        &nbsp;
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label><a href="{{ route('accounting.cash-advances') }}" id="no-underline">Clear Filters</a></label>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn bg-white border-left border-top border-top-sm-0 rounded-top-0 rounded-top-sm rounded-left-sm-0"><i class="material-icons text-primary icon-20pt">search</i></button>
        </div>
    </form>

    <div class="row">
        <div class="col">
            <div class="card">

                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table">CA#</th>
                                <th id="compact-table">Employee</th>
                                <th id="compact-table">Date Borrowed</th>
                                <th id="compact-table">Grand Total</th>
                                <th id="compact-table">Paid Balance</th>
                                <th id="compact-table">Remaining Balance</th>
                                <th id="compact-table">Status</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                            @foreach($cash_advances as $cash_advance)
                            @php
                                $paid_balance = CashAdvancePayment::where('cash_advance_id', $cash_advance->id)
                                        ->where('status', CashAdvancePaymentStatus::APPROVED)
                                        ->sum('price');

                                $remaining_balance = $cash_advance->price - $paid_balance;
                            @endphp
                                <tr>
                                    <td>
                                        <strong># {{ $cash_advance->reference_number }}</strong>

                                        <div class="d-flex">
                                            @if ($cash_advance->status == CashAdvanceStatus::CANCELLED)
                                                <a href="{{ route('accounting.cash-advances.view', [$cash_advance->reference_number]) }}">View</a>
                                            @endif

                                            @if ($cash_advance->status != CashAdvanceStatus::CANCELLED)
                                                <a href="{{ route('accounting.cash-advances.view', [$cash_advance->reference_number]) }}" id="margin-right">View</a> |  

                                                @if (auth()->user()->role == 'Super Admin' || auth()->user()->role == 'Admin' || auth()->user()->role == 'Accountant')
                                                    @if ($cash_advance->status == CashAdvanceStatus::FOR_APPROVAL)
                                                        <a href="#" data-href="{{ route('accounting.cash-advances.approve', [$cash_advance->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Approve</a> | 

                                                        <a href="#" data-href="{{ route('accounting.cash-advances.disapprove', [$cash_advance->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Disapprove</a>
                                                    @endif

                                                    <!-- FINAL APPROVAL SUPER ADMIN ONLY -->
                                                    @if (auth()->user()->role == 'Super Admin')
                                                        @if ($cash_advance->status == CashAdvanceStatus::FOR_FINAL_APPROVAL)
                                                            <a href="#" data-href="{{ route('accounting.cash-advances.final-approve', [$cash_advance->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Approve</a> | 

                                                            <a href="#" data-href="{{ route('accounting.cash-advances.disapprove', [$cash_advance->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Disapprove</a>
                                                        @endif
                                                    @endif
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                    <td id="compact-table">{{ $cash_advance->user->firstname }} {{ $cash_advance->user->lastname }}</td>
                                    <td>{{ Carbon::parse($cash_advance->date_borrowed)->format('M d Y') }}</td>
                                    <td>P{{ number_format($cash_advance->price, 2) }}</td>
                                    <td>P{{ number_format($paid_balance, 2) }}</td>
                                    <td>P{{ number_format($remaining_balance, 2) }}</td>
                                    <td>
                                        @if ($cash_advance->status == CashAdvanceStatus::FOR_APPROVAL)
                                          <div class="badge badge-warning">FOR APPROVAL</div>
                                        @elseif ($cash_advance->status == CashAdvanceStatus::FOR_FINAL_APPROVAL)
                                          <div class="badge badge-warning">FOR FINAL APPROVAL</div>
                                        @elseif ($cash_advance->status == CashAdvanceStatus::DISAPPROVED)
                                          <div class="badge badge-danger">DISAPPROVED</div>
                                        @elseif ($cash_advance->status == CashAdvanceStatus::UNPAID)
                                          <div class="badge badge-info">UNPAID</div>
                                        @elseif ($cash_advance->status == CashAdvanceStatus::PARTIALLY_PAID)
                                          <div class="badge badge-warning">PARTIALLY PAID</div>
                                        @elseif ($cash_advance->status == CashAdvanceStatus::FULLY_PAID)
                                          <div class="badge badge-success">FULLY PAID</div>
                                        @endif

                                        @if ($cash_advance->status == CashAdvanceStatus::CANCELLED)
                                            <div class="badge badge-danger ml-2">CANCELLED</div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if (count($cash_advances) <= 0)
                        <div style="padding: 20px">
                            <center><i class="material-icons icon-16pt mr-1 text-muted">assignment</i> No record/s found</center>
                        </div>
                    @endif
                </div>
            </div>
            {{ $cash_advances->links() }}
        </div>
    </div>
</div>


@include('layouts.auth.footer')