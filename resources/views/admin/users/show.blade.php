@include('layouts.auth.header')
@php
use App\Models\UserStatus;
@endphp

<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Users</li>
                </ol>
            </nav>
            <h1 class="m-0">Users</h1>
        </div>
        <a href="{{ route('admin.users.add') }}" class="btn btn-primary" id="margin-right"><i class="material-icons">add</i> Add User</a>
        <a href="{{ route('admin.users.corporate.add') }}" class="btn btn-primary"><i class="material-icons">add</i> Add Supplier</a>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')

    <form action="{{ route('admin.users.search') }}" method="post">
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
                                <option value="Programs">Programs</option>
                                <option value="Sales">Sales</option>
                                <option value="Accountant">Accountant</option>
                                <option value="Admin">Admin</option>
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
                        <label><a href="{{ route('admin.users') }}" id="no-underline">Clear Filters</a></label>
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
                    <h4 class="card-header__title flex m-0">Users</h4>
                    <div data-toggle="flatpickr" data-flatpickr-wrap="true" data-flatpickr-static="true" data-flatpickr-mode="range" data-flatpickr-alt-format="d/m/Y" data-flatpickr-date-format="d/m/Y">
                        
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table">#ID</th>
                                <th id="compact-table">Name</th>
                                <th id="compact-table">Position</th>
                                <th id="compact-table">Company</th>
                                <th id="compact-table">Role</th>
                                <th id="compact-table">Email</th>
                                <th id="compact-table">Contact</th>
                                <th id="compact-table">Signature</th>
                                <th id="compact-table">Status</th>
                                <th id="compact-table">Created At</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                            @foreach($users as $user)
                                <tr>
                                    <td><div class="badge badge-light">#{{ $user->id }}</div></td>
                                    <td id="compact-table">
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex align-items-center">
                                                <a href="{{ route('auth.profile.view', [$user->id]) }}" style="text-decoration: none; color: #333;"><b>
                                                @if ($user->avatar)
                                                    <img src="{{ url($user->avatar) }}" width="30px">
                                                    @if ($user->role == 'Corporate')
                                                        {{ $user->corporate }}
                                                    @else
                                                        {{ $user->firstname }} {{ $user->lastname }}
                                                    @endif
                                                @else
                                                    <img src="{{ url(env('APP_ICON')) }}" width="30px" style="margin-right: 7px;">
                                                    @if ($user->role == 'Corporate')
                                                        {{ $user->corporate }}
                                                    @else
                                                        {{ $user->firstname }} {{ $user->lastname }}
                                                    @endif
                                                @endif
                                                </b></a>
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
                                            <a href="{{ route('admin.users.view', [$user->id]) }}" style="margin-right: 7px">View</a> | 
                                            @if ($user->role == 'Corporate')
                                                <a href="{{ route('admin.users.corporate.edit', [$user->id]) }}" id="space-table">Edit</a> | 
                                            @else
                                                <a href="{{ route('admin.users.edit', [$user->id]) }}" id="space-table">Edit</a> | 
                                            @endif
                                            <!-- <a href="#" data-href="{{ route('admin.users.resend.email', [$user->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Resend Email</a> |  -->
                                            <a href="#" data-toggle="modal" data-target="#set-password-{{ $user->id }}" id="space-table">Set Password</a> | 
                                            @if ($user->status == UserStatus::ACTIVE || $user->status == UserStatus::PENDING)
                                                <a href="#" data-href="{{ route('admin.users.delete', [$user->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Delete</a>
                                            @endif

                                            @if ($user->status == UserStatus::INACTIVE)
                                                <a href="#" data-href="{{ route('admin.users.recover', [$user->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Recover</a>
                                            @endif
                                        </div>
                                    </td>
                                    <td id="compact-table">
                                        @if ($user->position)
                                            {{ $user->position }}
                                        @else
                                            Not applicable
                                        @endif
                                    </td>
                                    <td id="compact-table">
                                        @if ($user->company)
                                            <i class="material-icons icon-16pt mr-1 text-muted">location_on</i> {{ $user->company->name }}
                                        @else
                                            Not applicable
                                        @endif
                                    </td>
                                    <td id="compact-table"><i class="material-icons icon-16pt mr-1 text-muted">face</i> {{ $user->role }}</td>
                                    <td id="compact-table"><i class="material-icons icon-16pt mr-1 text-muted">email</i> {{ $user->email }}</td>
                                    <td id="compact-table">
                                        @if ($user->mobile)
                                            <i class="material-icons icon-16pt mr-1 text-muted">phone_android</i> {{ $user->mobile }}<br>
                                        @endif
                                        
                                        @if ($user->phone)
                                            <i class="material-icons icon-16pt mr-1 text-muted">phone</i> {{ $user->phone }}
                                        @endif
                                    </td>
                                    <td id="compact-table">
                                        @if ($user->signature)
                                            <a href="{{ url($user->signature) }}" target="_blank">
                                                <img src="{{ url($user->signature) }}" width="60px">
                                            </a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if ($user->status == UserStatus::ACTIVE)
                                            <div class="badge badge-success ml-2">Active</div>
                                        @elseif ($user->status == UserStatus::PENDING)
                                            <div class="badge badge-warning ml-2">Pending</div>
                                        @elseif ($user->status == UserStatus::INACTIVE)
                                            <div class="badge badge-danger ml-2">Inactive</div>
                                        @endif
                                    </td>
                                    <td id="compact-table"><i class="material-icons icon-16pt text-muted mr-1">today</i> {{ $user->created_at->format('M d Y') }}</td>
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