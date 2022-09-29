@include('layouts.auth.header')
<form action="{{ route('admin.suppliers.create') }}" method="post" id="form" enctype="multipart/form-data">
{{ csrf_field() }}

<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.suppliers') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item">Suppliers</li>
                    <li class="breadcrumb-item active" aria-current="page">Add Supplier</li>
                </ol>
            </nav>
            <h1 class="m-0">Add Supplier</h1>
        </div>
        <button type="submit" class="btn btn-success">Submit</button>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')

    <div class="row">
        <div class="col-md-8">
            <div id="spaced-card" class="card card-body">
                <div class="row">
                    <div class="col">
                        <h3>Supplier</h3>
                        <br>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Supplier Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="Supplier Name" value="{{ old('name') }}">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <input type="text" class="form-control" name="email" placeholder="Email Address" value="{{ old('email') }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Contact Person</label>
                                    <input type="text" class="form-control" name="person" placeholder="Contact Person" value="{{ old('person') }}">
                                </div>
                            </div>
                            <div class="col">
                                &nbsp;
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

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="tiny" name="description" placeholder="Enter your description here">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div id="semi-spaced-card" class="card card-body">
                <h3>Supplier Image</h3>
                <br>
                <div class="form-group">
                    <input type="file" name="image">
                </div>
            </div>
        </div>
    </div>
</div>

</form>
@include('layouts.auth.footer')