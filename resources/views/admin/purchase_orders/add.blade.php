@include('layouts.auth.header')
<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('internals.purchase-orders') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item">Purchase Orders</li>
                    <li class="breadcrumb-item active" aria-current="page">Add</li>
                </ol>
            </nav>
            <h1 class="m-0">Add Purchase Order</h1>
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
                        <form action="{{ route('internals.purchase-orders.create') }}" method="post" id="form" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <h3>Purchase Order</h3>
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
                                    <label for="company">Company</label><br />
                                    <select id="company" name="company_id" class="custom-select" data-toggle="select">
                                        <option value=""></option>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success" id="submitButton" onclick="submitForm(this);">Submit</button>
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