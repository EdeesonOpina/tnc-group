@if (Session::get('success'))
    <div class="alert alert-dismissible bg-success text-white border-0 fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <i class="material-icons icon-16pt" style="margin-right: 3px">check_circle</i>
        <strong>Success! </strong> {{ Session::get('success') }}!
    </div>
@endif

@if (Session::get('error'))
    <div class="alert alert-dismissible bg-danger text-white border-0 fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <i class="material-icons icon-16pt" style="margin-right: 3px">cancel</i>
        <strong>Oops! </strong> {{ Session::get('error') }}!
    </div>
@endif

@if (Session::get('warning'))
    <div class="alert alert-dismissible bg-warning text-white border-0 fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <i class="material-icons icon-16pt" style="margin-right: 3px">warning</i>
        <strong>Warning! </strong> {{ Session::get('warning') }}!
    </div>
@endif

@if (Session::get('item_serial_numbers_errors'))
    <div class="alert alert-dismissible bg-warning text-white border-0 fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <i class="material-icons icon-16pt" style="margin-right: 3px">warning</i>
        <strong>Warning! </strong> {{ Session::get('item_serial_numbers_errors') }}!
    </div>
@endif

@if($errors->any())
    <div class="alert alert-dismissible bg-danger text-white border-0 fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <i class="material-icons icon-16pt" style="margin-right: 3px">cancel</i>
        <strong>Oops! </strong> There seems to be a problem.<br><br>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </div>
@endif