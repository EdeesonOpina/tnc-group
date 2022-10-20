@include('layouts.auth.header')
<form action="{{ route('accounting.cash-advances.create') }}" method="post" id="form" enctype="multipart/form-data">
{{ csrf_field() }}

<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('accounting.cash-advances') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item">Cash Advances</li>
                    <li class="breadcrumb-item active" aria-current="page">Add Cash Advance</li>
                </ol>
            </nav>
            <h1 class="m-0">Add Cash Advance</h1>
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
                        <h3>Cash Advance</h3>
                        <br>
                        <div class="row">
                            <!-- <div class="col">
                                <div class="form-group">
                                    <label>Employee</label>
                                    <select id="user" name="user_id" class="custom-select" data-toggle="select">
                                        <option value=""></option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->firstname }} {{ $user->lastname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> -->
                            <div class="col">
                                <div class="form-group">
                                    <label>Amount to be borrowed</label>
                                    <input type="text" name="price" class="form-control" placeholder="Enter amount to be borrowed" value="{{ old('price') }}">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Date Borrowed</label>
                                    <input type="date" name="date_borrowed" class="form-control" value="{{ old('date_borrowed') ?? date('Y-m-d') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Reason</label>
                                    <textarea name="reason" class="form-control" rows="7" placeholder="Enter reason here...">{{ old('reason') }}</textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            &nbsp;
        </div>
    </div>
</div>

</form>
@include('layouts.auth.footer')