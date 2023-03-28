@include('layouts.auth.header')
<form action="{{ route('internals.brf.suppliers.create') }}" method="post" id="form" enctype="multipart/form-data">
{{ csrf_field() }}

<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('internals.brf') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item">BRF</li>
                    <li class="breadcrumb-item active" aria-current="page">Add BRF</li>
                </ol>
            </nav>
            <h1 class="m-0">Add BRF</h1>
        </div>
        <button type="submit" class="btn btn-success" id="submitButton" onclick="submitForm(this);">Submit</button>
    </div>
</div>

<div class="container-fluid page__container">

    <div class="row">
        <div class="col-md-8">
            @include('layouts.partials.alerts')
            
            <div id="spaced-card" class="card card-body">
                <div class="row">
                    <div class="col">
                        <h3>BRF</h3>
                        <br>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Cost Estimate #</label>
                                    <input type="text" name="reference_number" class="form-control">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Needed Date</label>
                                    <input type="date" name="needed_date" class="form-control" value="{{ old('needed_date') ?? date('Y-m-d') }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Payment To</label>
                                    <select class="custom-select" data-toggle="select" name="payment_for_supplier_id" id="payment_for_supplier_id">
                                        <option value=""></option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>In Payment For</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Requested By</label>
                                    <select name="requested_by_user_id" id="requested_by_user_id" class="custom-select" data-toggle="select">
                                        <option value="{{ auth()->user()->id }}">{{ auth()->user()->firstname }} {{ auth()->user()->lastname }}</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->firstname }} {{ $user->lastname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Checked By</label>
                                    <select name="checked_by_user_id" id="checked_by_user_id" class="custom-select" data-toggle="select">
                                        <option value=""></option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->firstname }} {{ $user->lastname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Noted By</label>
                                    <select class="custom-select" name="noted_by_user_id" id="noted_by_user_id" data-toggle="select">
                                        <option value=""></option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->firstname }} {{ $user->lastname }}</option>
                                        @endforeach
                                    </select>
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
        <!-- <div class="col">
            <div id="semi-spaced-card" class="card card-body">
                <h3>Project Image</h3>
                <br>
                <div class="form-group">
                    <input type="file" name="image">
                </div>
            </div>
        </div> -->
    </div>
</div>

</form>
@include('layouts.auth.footer')