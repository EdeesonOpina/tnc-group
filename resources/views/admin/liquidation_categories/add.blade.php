@include('layouts.auth.header')
<form action="{{ route('admin.liquidation-categories.create') }}" method="post" id="form" enctype="multipart/form-data">
{{ csrf_field() }}

<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.liquidation-categories') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item">Liquidation Categories</li>
                    <li class="breadcrumb-item active" aria-current="page">Add Liquidation Category</li>
                </ol>
            </nav>
            <h1 class="m-0">Add Liquidation Category</h1>
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
                        <h3>Category</h3>
                        <br>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Liquidation Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="Liquidation Name" value="{{ old('name') }}">
                                </div>
                            </div>
                            <div class="col">
                                &nbsp;
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div id="semi-spaced-card" class="card card-body">
                <h3>Liquidation Image</h3>
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