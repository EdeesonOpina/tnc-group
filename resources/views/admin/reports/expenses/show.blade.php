@include('layouts.auth.header')
@php
    use Carbon\Carbon;
    use App\Models\Expense;
    use App\Models\ExpenseStatus;
@endphp

<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Reports</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Expenses</li>
                </ol>
            </nav>
            <h1 class="m-0">Expenses</h1>
        </div>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')
    @include('layouts.partials.top-tabs')

    <form action="{{ route('admin.reports.expenses.search') }}" method="post">
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
                        <label><a href="{{ route('admin.reports.expenses') }}" id="no-underline">Clear Filters</a></label>
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
                    <h4 class="card-header__title flex m-0">Credits</h4>
                    <div data-toggle="flatpickr" data-flatpickr-wrap="true" data-flatpickr-static="true" data-flatpickr-mode="range" data-flatpickr-alt-format="d/m/Y" data-flatpickr-date-format="d/m/Y">

                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Total Expenses Count</th>
                                <th>Total Expense</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($period as $date)
                                @php
                                    $start_date = Carbon::parse($date['month'])->firstOfMonth()->format('Y-m-d');
                                    $end_date = Carbon::parse($date['month'])->endOfMonth()->format('Y-m-d');

                                    $expense_count = Expense::whereBetween('date', [$start_date . ' 00:00:00', $end_date  . ' 23:59:59'])
                                                            ->where('status', '!=', ExpenseStatus::INACTIVE)
                                                            ->count();

                                    $expense_total = Expense::whereBetween('date', [$start_date . ' 00:00:00', $end_date  . ' 23:59:59'])
                                                            ->where('status', '!=', ExpenseStatus::INACTIVE)
                                                            ->sum('price') ?? 0;
                                @endphp
                                <tr>
                                    <td>{{ $date['month'] }} {{ $date['year'] }}</td>
                                    <td>{{ $expense_count }}</td>
                                    <td>₱ {{ number_format($expense_total, 2) }}</td>
                                    <td>
                                        <a href="{{ route('admin.reports.expenses.filter', [$start_date, $end_date]) }}">
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