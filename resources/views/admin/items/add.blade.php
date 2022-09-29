@include('layouts.auth.header')
<form action="{{ route('admin.items.create') }}" method="post" id="form" enctype="multipart/form-data">
{{ csrf_field() }}

<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.items') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.items') }}">Items</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add Item</li>
                </ol>
            </nav>
            <h1 class="m-0">Add Item</h1>
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
                        <h3>Item</h3>
                        <br>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Item Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="Item Name" value="{{ old('name') }}">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Item Code <i>(optional)</i></label>
                                    <input type="text" name="item_code" class="form-control" placeholder="Item Code" value="{{ old('item_code') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Brand</label>
                                    <select id="brand" name="brand_id" class="custom-select" data-toggle="select">
                                        <option value=""></option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
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
                                    <label>Category</label>
                                    <select id="category" name="category_id" class="custom-select" data-toggle="select">
                                        <option value=""></option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Sub Category</label>
                                    <select id="sub_category" name="sub_category_id" class="custom-select" data-toggle="select">
                                        <option value=""></option>
                                    </select>
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
                <h3>Item Image</h3>
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
<script type="text/javascript">
    $('#category').on('change', function(e) {
        var category_id = this.value;
        var url = "{{ route('ajax.sub-categories', [':id']) }}";
        var url_get = url.replace(':id', category_id);

        $.ajax({
            type: "get",
            url: url_get,
            success: function(data)
            {
              $('#sub_category').empty();
              $('#sub_category').append("<option selected></option>");

              $.each(data, function(key, value) {
                  $('#sub_category').append("<option value='" + value.id + "'>" + value.name + "</option>");
              });
            }
        });
    });
</script>