@include('layouts.auth.header')
<form action="{{ route('admin.categories.update') }}" method="post" id="form" enctype="multipart/form-data">
{{ csrf_field() }}
<input type="hidden" name="category_id" value="{{ $category->id }}">
<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.categories') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item">Categories</li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Category</li>
                </ol>
            </nav>
            <h1 class="m-0">Edit Category</h1>
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
                        <h3>Category</h3>
                        <br>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Category Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="Category Name" value="{{ old('name') ?? $category->name }}">
                                </div>
                            </div>
                            <div class="col">
                                &nbsp;
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Is this a package category?</label>
                                    <select name="is_package" class="form-control">
                                        @if (old('sort_order'))
                                            @if (old('sort_order') == '1')
                                                <option value="1">Yes</option>   
                                            @else
                                                <option value="0">No</option>
                                            @endif
                                        @endif

                                        @if ($category->is_package)
                                            @if ($category->is_package == '1')
                                                <option value="1">Yes</option>   
                                            @else
                                                <option value="0">No</option>
                                            @endif
                                        @endif
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>         
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Sort Order #</label>
                                    <input type="number" name="sort_order" class="form-control" placeholder="Sort Order #" value="{{ old('sort_order') ?? $category->sort_order }}" min="1">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div id="semi-spaced-card" class="card card-body">
                <h3>Branch Image</h3>
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