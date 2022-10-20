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
                                            <strong>Client Name</strong><br>
                                            {{ $client->name }}
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <strong>Email Address</strong>
                                            {{ $contact->email }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <strong>Contact Person</strong><br>
                                            {{ $contact->person }}
                                        </div>
                                    </div>
                                    <div class="col">
                                        &nbsp;
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <strong>Phone</strong><br>
                                            {{ $contact->phone }}
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <strong>Mobile</strong><br>
                                            {{ $contact->mobile }}
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <strong>Line Address 1</strong><br>
                                            {{ $contact->line_address_1 }}
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <strong>Line Address 2</strong><br>
                                            {{ $contact->line_address_2 }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
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