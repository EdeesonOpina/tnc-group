@include('layouts.auth.header')
@php
    use Carbon\Carbon;
@endphp

<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.users') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.users') }}">Users</a></li>
                    <li class="breadcrumb-item active" aria-current="page">View</li>
                </ol>
            </nav>
            <h1 class="m-0">View</h1>
        </div>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')

    <div class="card card-form">
        <div class="row no-gutters">
            <div class="col-lg-4 card-body">
                <p><strong class="headings-color">User Information</strong></p>
                <p class="text-muted mb-0">Here are the details of this user.</p>
            </div>
            <div class="col-lg-8 card-form__body card-body">

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>Firstname</label><br />
                            {{ $user->firstname }}
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Lastname</label><br />
                            {{ $user->lastname }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>Line Address 1</label><br />
                            {{ $user->line_address_1 }}
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Line Address 2</label><br />
                            {{ $user->line_address_2 }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>Phone</label><br />
                            {{ $user->phone }}
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Mobile</label><br />
                            {{ $user->mobile }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="country">Role</label><br />
                            {{ $user->role }}
                        </div>
                    </div>
                    <div class="col">
                        &nbsp;
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>Email Address</label><br />
                            {{ $user->email }}
                        </div>
                    </div>
                    <div class="col">
                        @if ($user->resigned_date)
                            <label>Date of Resignation</label><br />
                            {{ Carbon::parse($user->resigned_date)->format('M d Y') }}
                        @else
                            &nbsp;
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>


@include('layouts.auth.footer')