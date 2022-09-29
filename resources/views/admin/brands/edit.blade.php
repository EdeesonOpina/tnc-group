@include('layouts.auth.header')
<form action="{{ route('admin.brands.update') }}" method="post" id="form" enctype="multipart/form-data">
{{ csrf_field() }}
<input type="hidden" name="brand_id" value="{{ $brand->id }}">
<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.brands') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item">Brands</li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Brand</li>
                </ol>
            </nav>
            <h1 class="m-0">Edit Brand</h1>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')

    <div class="row">
        <div class="col-md-8">
            <div id="spaced-card" class="card card-body">
                <div class="row">
                    <div class="col">
                        <h3>Branch</h3>
                        <br>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Brand Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="Brand Name" value="{{ old('name') ?? $brand->name }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea id="tiny" name="description" placeholder="Enter your description here">{{ old('description') ?? $brand->description }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div id="semi-spaced-card" class="card card-body">
                <h3>Brand Image</h3>
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