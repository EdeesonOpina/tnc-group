@include('layouts.auth.header')
@php
    use App\Models\Country;
@endphp

<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.users') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item">Users</li>
                    <li class="breadcrumb-item active" aria-current="page">Edit User</li>
                </ol>
            </nav>
            <h1 class="m-0">Edit User</h1>
        </div>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')

    <div class="card card-form">
        <div class="row no-gutters">
            <div class="col-lg-4 card-body">
                <p><strong class="headings-color">Edit User</strong></p>
                <p class="text-muted mb-0">Please enter the details needed in order for you to proceed.</p>
            </div>
            <div class="col-lg-8 card-form__body card-body">
                <form action="{{ route('admin.users.update') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="user_id" value="{{ $user->id }}">

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>Firstname</label>
                            <input type="text" name="firstname" class="form-control" placeholder="Firstname" value="{{ old('firstname') ?? $user->firstname }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Lastname</label>
                            <input type="text" name="lastname" class="form-control" placeholder="Lastname" value="{{ old('lastname') ?? $user->lastname }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>Birthdate</label>
                            <input type="date" name="birthdate" class="form-control" max="{{ date('Y-m-d') }}" value="{{ old('birthdate') ?? $user->birthdate }}">
                        </div>
                    </div>
                    <div class="col">
                        &nbsp;
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="company">Company</label><br />
                            <select id="company" name="company_id" class="custom-select" data-toggle="select">
                                <option value="{{ $user->company->id }}">{{ $user->company->name }}</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Position</label>
                            <input type="text" name="position" class="form-control" placeholder="Position" value="{{ $user->position }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>Line Address 1</label>
                            <input type="text" name="line_address_1" class="form-control" placeholder="Line Address 1" value="{{ old('line_address_1') ?? $user->line_address_1 }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Line Address 2</label>
                            <input type="text" name="line_address_2" class="form-control" placeholder="Line Address 2" value="{{ old('line_address_1') ?? $user->line_address_2 }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>Phone <i>(optional)</i></label>
                            <input type="text" name="phone" class="form-control" placeholder="Phone" value="{{ old('phone') ?? $user->phone }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Mobile</label>
                            <input type="text" name="mobile" class="form-control" placeholder="Mobile" value="{{ old('mobile') ?? $user->mobile }}">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="desc">Biography <i>(optional)</i></label>
                    <textarea id="desc" name="biography" rows="4" class="form-control" placeholder="Enter biography">{{ old('biography') ?? $user->biography }}</textarea>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="country">Role</label><br />
                            <select id="country" name="role" class="custom-select" data-toggle="select">
                                <option value="{{ $user->role }}">{{ $user->role }}</option>
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
                            <input type="text" class="form-control" name="email" placeholder="Email Address" value="{{ old('email') ?? $user->email }}">
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