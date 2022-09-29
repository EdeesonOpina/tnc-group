@include('layouts.auth.header')
@php
use App\Models\UserStatus;
use App\Models\CashAdvance;
use App\Models\CashAdvanceStatus;
use App\Models\CashAdvancePayment;
use App\Models\CashAdvancePaymentStatus;
@endphp

<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Reports</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Cash Advances</li>
                </ol>
            </nav>
            <h1 class="m-0">Cash Advances</h1>
        </div>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')
    @include('layouts.partials.top-tabs')

    <form action="{{ route('admin.reports.cash-advances.search') }}" method="post">
        {{ csrf_field() }}
        <div class="card card-form d-flex flex-column flex-sm-row">
            <div class="card-form__body card-body-form-group flex">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>Name</label>
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
                        <label><a href="{{ route('admin.reports.cash-advances') }}" id="no-underline">Clear Filters</a></label>
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
                    <h4 class="card-header__title flex m-0">Employees</h4>
                    <div data-toggle="flatpickr" data-flatpickr-wrap="true" data-flatpickr-static="true" data-flatpickr-mode="range" data-flatpickr-alt-format="d/m/Y" data-flatpickr-date-format="d/m/Y">
                        
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table">#ID</th>
                                <th id="compact-table">Name</th>
                                <th id="compact-table">No. of Transactions</th>
                                <th id="compact-table">CA Total</th>
                                <th id="compact-table">Paid Balance Total</th>
                                <th id="compact-table">Created At</th>
                                <th id="compact-table">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                            @foreach($users as $user)
                            @php
                                $cash_advances_count = CashAdvance::where('user_id', $user->id)
                                                                ->where('status', '!=', CashAdvanceStatus::INACTIVE)
                                                                ->count();

                                $cash_advances_total = CashAdvance::where('user_id', $user->id)
                                                                ->where('status', '!=', CashAdvanceStatus::INACTIVE)
                                                                ->sum('price');

                                $paid_balance = CashAdvancePayment::leftJoin('cash_advances', 'cash_advance_payments.cash_advance_id', '=', 'cash_advances.id')
                                                        ->leftJoin('users', 'cash_advances.user_id', '=', 'users.id')
                                                        ->select('cash_advance_payments.*')
                                                        ->where('cash_advances.user_id', $user->id)
                                                        ->where('cash_advance_payments.status', CashAdvancePaymentStatus::APPROVED)
                                                        ->sum('cash_advance_payments.price');
                            @endphp
                                <tr>
                                    <td><div class="badge badge-light">#{{ $user->id }}</div></td>
                                    <td id="compact-table">
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex align-items-center">
                                                <b>
                                                @if ($user->avatar)
                                                    <img src="{{ url($user->avatar) }}" width="30px">
                                                    {{ $user->firstname }} {{ $user->lastname }}
                                                @else
                                                    <img src="{{ url(env('BIG_FOUR_ICON')) }}" width="30px" style="margin-right: 7px;">
                                                    {{ $user->firstname }} {{ $user->lastname }}
                                                @endif
                                                </b>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="compact-table">{{ $cash_advances_count }}</td>
                                    <td id="compact-table">{{ number_format($cash_advances_total, 2) }}</td>
                                    <td id="compact-table">{{ number_format($paid_balance, 2) }}</td>
                                    <td id="compact-table"><i class="material-icons icon-16pt text-muted mr-1">today</i> {{ $user->created_at->format('M d Y') }}</td>
                                    <td>
                                        <!-- <a href="{{ route('admin.reports.cash-advances.view', [$user->id, old('from_date') ?? '*', old('to_date') ?? '*']) }}">
                                            <button class="btn btn-sm btn-primary">View</button>
                                        </a> -->
                                        <a href="#" data-toggle="modal" data-target="#view-user-{{ $user->id }}">
                                            <button class="btn btn-sm btn-primary">View</button>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if (count($users) <= 0)
                        <div style="padding: 20px">
                            <center><i class="material-icons icon-16pt mr-1 text-muted">assignment</i> No record/s found</center>
                        </div>
                    @endif
                </div>
            </div>
            {{ $users->links() }}
        </div>
    </div>
</div>


@include('layouts.auth.footer')