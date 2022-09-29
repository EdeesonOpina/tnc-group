@include('layouts.auth.header')

<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.clients') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item">Clients</li>
                    <li class="breadcrumb-item active" aria-current="page">View Client</li>
                </ol>
            </nav>
            <h1 class="m-0">View Client</h1>
        </div>
        <a href="{{ route('admin.clients.edit', [$client->id]) }}">
            <button type="button" class="btn btn-success" id="table-letter-margin"><i class="material-icons icon-16pt mr-1 text-white">edit</i> Edit</button>
        </a>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')

    <div class="row">
        <div class="col-md-8">
            <div id="spaced-card" class="card card-body">
                <div class="row">
                    <div class="col">
                        <div class="row">
                            <div class="col-md-4">
                                @if ($client->image)
                                    <img src="{{ url($client->image) }}" width="100%" class="img-thumbnail">
                                @else
                                    <img src="{{ url(env('APP_ICON')) }}" width="100%" style="padding: 5%;" class="img-thumbnail">
                                @endif
                            </div>
                            <div class="col">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <h6>Client Name</h6>
                                            {{ $client->name }}
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <h6>Email Address</h6>
                                            {{ $client->email }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <h6>Contact Person</h6>
                                            {{ $client->person }}
                                        </div>
                                    </div>
                                    <div class="col">
                                        &nbsp;
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <h6>Phone</h6>
                                            {{ $client->phone }}
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <h6>Mobile</h6>
                                            {{ $client->mobile }}
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <h6>Line Address 1</h6>
                                            {{ $client->line_address_1 }}
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <h6>Line Address 2</h6>
                                            {{ $client->line_address_2 }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <hr>
                <h6>Description</h6>
                {!! $client->description !!}
            </div>
        </div>
        <div class="col">
            <!-- <div id="semi-spaced-card" class="card card-body">
                <h3>Items</h3>
                <br>
                <table></table>
            </div> -->
        </div>
    </div>
</div>

@include('layouts.auth.footer')