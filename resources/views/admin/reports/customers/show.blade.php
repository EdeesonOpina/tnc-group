@include('layouts.auth.header')
@php
use App\Models\UserStatus;
use App\Models\PaymentReceipt;
use App\Models\PaymentReceiptStatus;
@endphp

<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Reports</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Customers</li>
                </ol>
            </nav>
            <h1 class="m-0">Customers</h1>
        </div>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')
    @include('layouts.partials.top-tabs')

    <form action="{{ route('admin.reports.customers.search') }}" method="post">
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
                            <label>Role</label>
                            <select name="role" class="form-control" data-toggle="select">
                                @if (old('role'))
                                    @if (old('role') != '*')
                                        <option value="{{ old('role') }}">{{ old('role') }}</option>
                                    @endif
                                @endif
                                <option value="*">All</option>
                                <option value="Super Admin">Super Admin</option>
                                <option value="Admin">Admin</option>
                                <option value="Business">Business</option>
                                <option value="Customer">Customer</option>
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" data-toggle="select">
                                @if (old('status'))
                                    @if (old('status') != '*')
                                        @if (old('status') == UserStatus::ACTIVE)
                                            <option value="{{ old('status') }}">Active</option>
                                        @endif

                                        @if (old('status') == UserStatus::PENDING)
                                            <option value="{{ old('status') }}">Pending</option>
                                        @endif

                                        @if (old('status') == UserStatus::INACTIVE)
                                            <option value="{{ old('status') }}">Inactive</option>
                                        @endif
                                    @endif
                                @endif
                                <option value="*">All</option>
                                <option value="2">Active</option>
                                <option value="1">Pending</option>
                                <option value="0">Inactive</option>
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
                        <label><a href="{{ route('admin.reports.customers') }}" id="no-underline">Clear Filters</a></label>
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
                    <h4 class="card-header__title flex m-0">Customers</h4>
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
                                <th id="compact-table">Created At</th>
                                <th id="compact-table">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                            @foreach($users as $user)
                            @php
                                $payment_receipts_count = PaymentReceipt::where('user_id', $user->id)->count()
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
                                    <td id="compact-table">{{ $payment_receipts_count }}</td>
                                    <td id="compact-table"><i class="material-icons icon-16pt text-muted mr-1">today</i> {{ $user->created_at->format('M d Y') }}</td>
                                    <td>
                                        
                                        <a href="#" data-toggle="modal" data-target="#view-customer-{{ $user->id }}">
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