@include('layouts.auth.header')
@php
use App\Models\ServiceOrder;
use App\Models\UserStatus;
@endphp

<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('jo') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('jo.customer.existing') }}">Job Orders</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </nav>
            <h1 class="m-0">Job Orders - Dashboard</h1>
        </div>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')

    <div class="row">
        <div class="col-md-5">
            <div id="spaced-card" class="card card-body">
                <div class="row">
                    <div class="col">
                        <h3>Transaction Details</h3>
                        <br>
                        Please Select Customer<br><br>
                        <div class="row">
                            <div class="col">
                                <a href="{{ route('jo.customer.new') }}">
                                    <button class="btn btn-light form-control">New</button>
                                </a>
                            </div>
                            <div class="col">
                                <a href="{{ route('jo.customer.existing') }}">
                                    <button class="btn btn-primary form-control">Existing</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <form action="{{ route('jo.customer.existing.search') }}" method="post">
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
                                        <option value="UserStatus::ACTIVE">Active</option>
                                        <option value="UserStatus::PENDING">Pending</option>
                                        <option value="UserStatus::INACTIVE">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label><a href="{{ route('jo.customer.existing') }}" id="no-underline">Clear Filters</a></label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn bg-white border-left border-top border-top-sm-0 rounded-top-0 rounded-top-sm rounded-left-sm-0"><i class="material-icons text-primary icon-20pt">search</i></button>
                </div>
            </form>

            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h4 class="card-header__title flex m-0">Existing Users</h4>
                    <div data-toggle="flatpickr" data-flatpickr-wrap="true" data-flatpickr-static="true" data-flatpickr-mode="range" data-flatpickr-alt-format="d/m/Y" data-flatpickr-date-format="d/m/Y">
                        
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table">Name</th>
                                <th id="compact-table">No. of Transactions</th>
                                <th id="compact-table"></th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                            @foreach($users as $user)
                            @php
                                $service_order_count = ServiceOrder::where('user_id', $user->id)->count();
                            @endphp
                                <tr>
                                    <td id="compact-table">
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex align-items-center">
                                                <strong>
                                                    {{ $user->firstname }} {{ $user->lastname }}
                                                </strong>
                                            </div>

                                            <div class="d-flex align-items-center">
                                                @if ($user->email_verified_at != null)
                                                    <div class="badge badge-success ml-2">Verified</div>
                                                @else
                                                    <div class="badge badge-warning ml-2">Not Verified</div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="d-flex">
                                            <i class="material-icons icon-16pt mr-1 text-muted">face</i> {{ $user->role }}
                                        </div>
                                        <div class="d-flex">
                                            <i class="material-icons icon-16pt mr-1 text-muted">email</i> {{ $user->email }}<br>
                                        </div>

                                        @if ($user->mobile)
                                            <div class="d-flex">
                                                <i class="material-icons icon-16pt mr-1 text-muted">phone_android</i> {{ $user->mobile }}<br>
                                            </div>
                                        @endif

                                        @if ($user->phone)
                                            <div class="d-flex">
                                                <i class="material-icons icon-16pt mr-1 text-muted">phone</i> {{ $user->phone }}
                                            </div>
                                        @endif
                                    </td>
                                    <td id="compact-table">
                                        {{ $service_order_count }}
                                    </td>
                                    <td id="compact-table">
                                        <a href="{{ route('jo.service-order', [$user->id]) }}">
                                            <button class="btn btn-success">Select</button>
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