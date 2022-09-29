@include('layouts.auth.header')

<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.users') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item">Users</li>
                    <li class="breadcrumb-item active" aria-current="page">Add User</li>
                </ol>
            </nav>
            <h1 class="m-0">Add User</h1>
        </div>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')

    <div class="card card-form">
        <div class="row no-gutters">
            <div class="col-lg-4 card-body">
                <p><strong class="headings-color">Add New User</strong></p>
                <p class="text-muted mb-0">Please enter the details needed in order for you to proceed.</p>
            </div>
            <div class="col-lg-8 card-form__body card-body">
                <form action="{{ route('admin.users.create') }}" method="post">
                    {{ csrf_field() }}
                
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
                            <label>Birthdate</label>
                            <input type="date" name="birthdate" class="form-control" data-toggle="flatpickr" max="{{ date('Y-m-d') }}" value="{{ old('birthdate') }}">
                        </div>
                    </div>
                    <div class="col">
                        &nbsp;
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
                            <input type="text" name="line_address_2" class="form-control" placeholder="Line Address 2" value="{{ old('line_address_1') }}">
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

                <div class="form-group">
                    <label for="desc">Biography <i>(optional)</i></label>
                    <textarea id="desc" name="biography" rows="4" class="form-control" placeholder="Enter biography">{{ old('biography') }}</textarea>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="role">Role</label><br />
                            <select id="role" name="role" class="custom-select" data-toggle="select">
                                <option value="Customer">Customer</option>
                                <option value="Agent">Agent</option>
                                <option value="Corporate">Corporate</option>
                                <option value="Customer">Customer</option>
                                <option value="Stockman">Stockman</option>
                                <option value="Cashier">Cashier</option>
                                <option value="Technical">Technical</option>
                                <option value="Cashier / Technical">Cashier / Technical</option>
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
                    <div class="col">
                        &nbsp;
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
                        &nbsp;
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