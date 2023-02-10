@include('layouts.auth.header')

<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('hr.payslips') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('hr.payslips.view', [$user->id]) }}">Payslip</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $user->firstname }} {{ $user->lastname }}</li>
                </ol>
            </nav>
            <h1 class="m-0">{{ $user->firstname }} {{ $user->lastname }} Attendance</h1>
        </div>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')

    <div class="row">
        <div class="col-md-8">
            <div id="spaced-card" class="card card-body">
                <div class="row">
                    <div class="col">
                        <h3>Time Record</h3>
                        <br>
                        <form action="{{ route('hr.payslips.time.create') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="type">Type</label><br />
                                    <select id="type" name="type" class="custom-select" data-toggle="select">
                                        <option value="Regular">Regular</option>
                                        <option value="Overtime">Overtime</option>
                                        <option value="Official Business Trip">Official Business Trip</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>From Date</label>
                                    <input type="date" name="from_date" class="form-control" value="{{ old('from_date') ?? date('Y-m-d') }}">
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label>To Date</label>
                                    <input type="date" name="to_date" class="form-control" value="{{ old('to_date') ?? date('Y-m-d') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Time In</label>
                                    <input type="time" name="time_in" class="form-control" value="{{ old('time_in') }}">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Time Out</label>
                                    <input type="time" name="time_out" class="form-control" value="{{ old('time_out') }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group m-0">
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


@include('layouts.auth.footer')