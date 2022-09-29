@include('layouts.auth.header')
<form action="{{ route('admin.accounts.update') }}" method="post" id="form" enctype="multipart/form-data">
{{ csrf_field() }}
<input type="hidden" name="account_id" value="{{ $account->id }}">
<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.accounts') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item">Accounts</li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Account</li>
                </ol>
            </nav>
            <h1 class="m-0">Edit Account</h1>
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
                        <h3>Account</h3>
                        <br>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Bank Name</label>
                                    <input type="text" name="bank" class="form-control" placeholder="Bank Name" value="{{ old('bank') ?? $account->bank }}">
                                </div>
                            </div>
                            <div class="col">
                                &nbsp;
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Account Number</label>
                                    <input type="text" name="number" class="form-control" placeholder="Account Number" value="{{ old('number') ?? $account->number }}">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Account Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="Account Name" value="{{ old('name') ?? $account->name }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div id="semi-spaced-card" class="card card-body">
                <h3>Account Image</h3>
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