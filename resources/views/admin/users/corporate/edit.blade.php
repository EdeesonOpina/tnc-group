@include('layouts.auth.header')

<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.users') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item">Corporate</li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Corporate</li>
                </ol>
            </nav>
            <h1 class="m-0">Edit Corporate</h1>
        </div>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')

    <div class="card card-form">
        <div class="row no-gutters">
            <div class="col-lg-4 card-body">
                <p><strong class="headings-color">Edit New Corporate</strong></p>
                <p class="text-muted mb-0">Please enter the details needed in order for you to proceed.</p>
            </div>
            <div class="col-lg-8 card-form__body card-body">
                <form action="{{ route('admin.users.corporate.update') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="corporate" class="form-control" placeholder="Name" value="{{ old('corporate') ?? $user->corporate }}">
                        </div>
                    </div>
                    <div class="col">

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
                            <input type="text" name="line_address_2" class="form-control" placeholder="Line Address 2" value="{{ old('line_address_2') ?? $user->line_address_2 }}">
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
                            <label for="role">Role</label><br />
                            <select id="role" name="role" class="custom-select" data-toggle="select">
                                <option value="Corporate">Corporate</option>
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