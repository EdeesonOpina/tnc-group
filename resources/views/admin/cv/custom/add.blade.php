@include('layouts.auth.header')
<form action="{{ route('internals.cv.custom.create') }}" method="post" id="form" enctype="multipart/form-data">
{{ csrf_field() }}

<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('internals.cv') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item">Check Vouchers</li>
                    <li class="breadcrumb-item active" aria-current="page">Add Check Voucher</li>
                </ol>
            </nav>
            <h1 class="m-0">Add Check Voucher</h1>
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
                        <h3>Check Voucher</h3>
                        <br>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="company">Bank Account</label><br />
                                    <select id="company" name="account_id" class="custom-select" data-toggle="select">
                                        @foreach($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->bank }} ({{ $account->number }}) - {{ $account->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>In Payment For</label>
                                    <input type="text" name="name" class="form-control" placeholder="In Payment For" value="{{ old('name') }}">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Pay To</label>
                                    <input type="text" name="pay_to" class="form-control" placeholder="Pay To" value="{{ old('pay_to') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Check #</label><br>
                                    <input type="text" name="cheque_number" class="form-control" value="{{ old('cheque_number') }}" placeholder="Check #">
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label for="currency">Currency</label><br />
                                    <select id="currency" name="currency" class="custom-select" data-toggle="select">
                                        <option value="PHP">PHP</option>
                                        <option value="USD">USD</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Amount</label><br>
                                    <input type="text" name="amount" class="form-control" value="{{ old('amount') ?? '0.00' }}" placeholder="Amount">
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label>Needed Date</label>
                                    <input type="date" name="needed_date" class="form-control" value="{{ old('needed_date') ?? date('Y-m-d') }}">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</form>
@include('layouts.auth.footer')