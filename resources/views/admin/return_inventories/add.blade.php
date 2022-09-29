@include('layouts.auth.header')
<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('internals.return-inventories') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item">Return Inventories</li>
                    <li class="breadcrumb-item active" aria-current="page">Add</li>
                </ol>
            </nav>
            <h1 class="m-0">Add Return Inventory</h1>
        </div>
        
    </div>
</div>

<div class="container-fluid page__container">
    <div class="row">
        <div class="col-md-8">
            @include('layouts.partials.alerts')
            <div id="spaced-card" class="card card-body">
                <div class="row">
                    <div class="col">
                        <form action="{{ route('internals.return-inventories.create') }}" method="post" id="form" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <h3>Return Inventory</h3>
                        <br>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="supplier">Supplier</label><br />
                                    <select id="supplier" name="supplier_id" class="custom-select" data-toggle="select">
                                        <option value=""></option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="branch">Branch</label><br />
                                    <select id="branch" name="branch_id" class="custom-select" data-toggle="select">
                                        <option value=""></option>
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            &nbsp;
        </div>
    </div>
</div>
@include('layouts.auth.footer')