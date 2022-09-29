@include('layouts.auth.header')
@php
    use Carbon\Carbon;
@endphp

<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('internals.projects') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item">Projects</li>
                    <li class="breadcrumb-item active" aria-current="page">Manage Project</li>
                </ol>
            </nav>
            <h1 class="m-0">Manage Project</h1>
        </div>
        <a href="{{ route('internals.projects.edit', [$project->id]) }}">
            <button type="button" class="btn btn-success" id="table-letter-margin"><i class="material-icons icon-16pt mr-1 text-white">edit</i> Edit</button>
        </a>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')

    <div class="row">
        <div class="col-md-12">
            <div id="spaced-card" class="card card-body">
                <div class="row">
                    <div class="col">
                        <div class="row">
                            <div class="col">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <h6>Client Name</h6>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            {{ $project->client->name }}
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <h6>Date</h6>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            {{ $project->created_at->format('M d Y') }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <h6>Project Name</h6>
                                        </div>
                                    </div>
                                    <div class="col">
                                        {{ $project->name }}
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <h6>Project Duration</h6>
                                        </div>
                                    </div>
                                    <div class="col">
                                        {{ Carbon::parse($project->end_date)->format('M d Y') }}
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
                <br>

                <h4 class="card-header__title flex m-0">Project Details</h4>
                <div data-toggle="flatpickr" data-flatpickr-wrap="true" data-flatpickr-static="true" data-flatpickr-mode="range" data-flatpickr-alt-format="d/m/Y" data-flatpickr-date-format="d/m/Y">
                    
                </div>

                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table">#ID</th>
                                <th id="compact-table"></th>
                                <th id="compact-table">Quantity</th>
                                <th id="compact-table">Description</th>
                                <th id="compact-table">Unit Price</th>
                                <th id="compact-table">Total Price</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">

                        </tbody>
                    </table>

                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <tbody class="list" id="companies">
                            <tr>
                                <th id="compact-table" colspan="5">Total Cost</th>
                                <th id="compact-table">0.00</th>
                            </tr>
                        </tbody>
                    </table>

                    @if (count($details) <= 0)
                        <div style="padding: 20px">
                            <center><i class="material-icons icon-16pt mr-1 text-muted">assignment</i> No record/s found</center>
                        </div>
                    @endif
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