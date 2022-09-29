@include('layouts.auth.header')
@php
use Carbon\Carbon;
use App\Models\ProjectStatus;
@endphp

<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Projects</li>
                </ol>
            </nav>
            <h1 class="m-0">Projects</h1>
        </div>
        <a href="{{ route('internals.projects.add') }}" class="btn btn-primary"><i class="material-icons">add</i> Add</a>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')

    <form action="{{ route('internals.projects.search') }}" method="post">
        {{ csrf_field() }}
        <div class="card card-form d-flex flex-column flex-sm-row">
            <div class="card-form__body card-body-form-group flex">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>Name</label>
                            <input name="name" type="text" class="form-control" placeholder="Search by name" value="{{ old('name') }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" data-toggle="select">
                                @if (old('status'))
                                    @if (old('status') != '*')
                                        @if (old('status') == ProjectStatus::FOR_APPROVAL)
                                            <option value="{{ old('status') }}">Active</option>
                                        @endif

                                        @if (old('status') == ProjectStatus::INACTIVE)
                                            <option value="{{ old('status') }}">Inactive</option>
                                        @endif
                                    @endif
                                @endif
                                <option value="*">All</option>
                                <option value="{{ ProjectStatus::FOR_APPROVAL }}">Active</option>
                                <option value="{{ ProjectStatus::INACTIVE }}">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>From</label>
                            <input name="from_date" type="date" class="form-control" data-toggle="flatpickr" max="{{ date('Y-m-d') }}" value="{{ old('from_date') }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>To</label>
                            <input name="to_date" type="date" class="form-control" data-toggle="flatpickr" max="{{ date('Y-m-d') }}" value="{{ old('to_date') }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label><a href="{{ route('internals.projects') }}" id="no-underline">Clear Filters</a></label>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn bg-white border-left border-top border-top-sm-0 rounded-top-0 rounded-top-sm rounded-left-sm-0"><i class="material-icons text-primary icon-20pt">search</i></button>
        </div>
    </form>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h4 class="card-header__title flex m-0">Clients</h4>
                    <div data-toggle="flatpickr" data-flatpickr-wrap="true" data-flatpickr-static="true" data-flatpickr-mode="range" data-flatpickr-alt-format="d/m/Y" data-flatpickr-date-format="d/m/Y">
                        
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table">#ID</th>
                                <th id="compact-table"></th>
                                <th id="compact-table">Company</th>
                                <th id="compact-table">Name</th>
                                <th id="compact-table">Client</th>
                                <th id="compact-table">Cost</th>
                                <th id="compact-table">End Date</th>
                                <th id="compact-table">Status</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                            @foreach($projects as $project)
                                <tr>
                                    <td><div class="badge badge-light">#{{ $project->id }}</div></td>
                                    <td id="compact-table">
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex align-items-center">
                                                @if ($project->image)
                                                    <img src="{{ url($project->image) }}" width="100px">
                                                @else
                                                    <img src="{{ url(env('BIG_FOUR_ICON')) }}" width="40px" style="margin-right: 7px;">
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td id="compact-table">
                                        <b>{{ $project->name }}</b>
                                        <div class="d-flex">
                                            <a href="{{ route('internals.projects.view', [$project->id]) }}" id="table-letter-margin">View</a> | 
                                            <a href="{{ route('internals.projects.edit', [$project->id]) }}" id="space-table">Edit</a> | 
                                            <a href="{{ route('internals.projects.manage', [$project->id]) }}" id="space-table">Manage</a> | 
                                            @if ($project->status == ProjectStatus::FOR_APPROVAL)
                                                <a href="#" data-href="{{ route('internals.projects.delete', [$project->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Delete</a>
                                            @endif

                                            @if ($project->status == ProjectStatus::INACTIVE)
                                                <a href="#" data-href="{{ route('internals.projects.recover', [$project->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Recover</a>
                                            @endif
                                        </div>
                                    </td>
                                    <td id="compact-table"><i class="material-icons icon-16pt mr-1 text-muted">face</i> {{ $project->company->name }}</td>
                                    <td id="compact-table"><i class="material-icons icon-16pt mr-1 text-muted">email</i> {{ $project->client->name }}</td>
                                    <td id="compact-table">P{{ number_format($project->cost, 2) }}</td>
                                    <td id="compact-table"><i class="material-icons icon-16pt text-muted mr-1">today</i> {{ Carbon::parse($project->end_date)->format('M d Y') }}</td>
                                    <td>
                                        @if ($project->status == ProjectStatus::FOR_APPROVAL)
                                            <div class="badge badge-success ml-2">For Approval</div>
                                        @elseif ($project->status == ProjectStatus::INACTIVE)
                                            <div class="badge badge-danger ml-2">Inactive</div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if (count($projects) <= 0)
                        <div style="padding: 20px">
                            <center><i class="material-icons icon-16pt mr-1 text-muted">assignment</i> No record/s found</center>
                        </div>
                    @endif
                </div>
            </div>
            {{ $projects->links() }}
        </div>
    </div>
</div>


@include('layouts.auth.footer')