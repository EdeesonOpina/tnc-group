@include('layouts.auth.header')
@php
use App\Models\UserStatus;
@endphp

<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('jo') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('jo.customer.new') }}">Job Orders</a></li>
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
                                    <button class="btn btn-primary form-control">New</button>
                                </a>
                            </div>
                            <div class="col">
                                <a href="{{ route('jo.customer.existing') }}">
                                    <button class="btn btn-light form-control">Existing</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div id="spaced-card" class="card card-body">
                <form action="{{ route('jo.customer.new.create') }}" method="post">
                    {{ csrf_field() }}

                <h3>Create New User</h3>
                
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>Firstname</label>
                            <input type="text" name="firstname" class="form-control" placeholder="Firstname" value="{{ old('firstname') }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Lastname</label>
                            <input type="text" name="lastname" class="form-control" placeholder="Lastname" value="{{ old('lastname') }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="text" class="form-control" name="email" placeholder="Email Address" value="{{ old('email') }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="role">Role</label><br />
                            <select id="role" name="role" class="custom-select" data-toggle="select">
                                <option value="{{ old('role') }}">{{ old('role') }}</option>
                                <option value="Customer">Customer</option>
                                <option value="Agent">Agent</option>
                                <option value="Corporate">Corporate</option>
                                <option value="Customer">Customer</option>
                                <option value="Cashier">Cashier</option>
                                <option value="Encoder">Encoder</option>
                                <option value="Sales">Sales</option>
                                <option value="RMA">RMA</option>
                                <option value="Moderator">Moderator</option>
                                <option value="Editor">Editor</option>
                                <option value="Developer">Developer</option>
                                <option value="Admin">Admin</option>
                                <option value="Super Admin">Super Admin</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>Phone <i>(optional)</i></label>
                            <input type="text" name="phone" class="form-control" placeholder="Phone" value="{{ old('phone') }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Mobile</label>
                            <input type="text" name="mobile" class="form-control" placeholder="Mobile" value="{{ old('mobile') }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="country">Country</label><br />
                            <select id="country" name="country_id" class="custom-select" data-toggle="select">
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        &nbsp;
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>Line Address 1</label>
                            <input type="text" name="line_address_1" class="form-control" placeholder="Line Address 1" value="{{ old('line_address_1') }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Line Address 2</label>
                            <input type="text" name="line_address_2" class="form-control" placeholder="Line Address 2" value="{{ old('line_address_2') }}">
                        </div>
                    </div>
                </div>
                
                <div class="form-group m-0">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>

                </form>
            </div>
        </div>
    </div>
</div>


@include('layouts.auth.footer')