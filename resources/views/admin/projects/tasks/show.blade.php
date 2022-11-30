@include('layouts.auth.header')
@php
    use Carbon\Carbon;
    use App\Models\ProjectStatus;
    use App\Models\ProjectTask;
    use App\Models\ProjectTaskStatus;
    use App\Models\BudgetRequestFormStatus;
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
        <!-- <a href="{{ route('internals.exports.projects.print.ce', [$project->reference_number]) }}">
            <button type="button" class="btn btn-light" id="margin-right"><i class="fa fa-print" id="margin-right"></i>Print CE</button>
        </a>

        <a href="{{ route('internals.exports.projects.print.internal-ce', [$project->reference_number]) }}">
            <button type="button" class="btn btn-light" id="margin-right"><i class="fa fa-print" id="margin-right"></i>Print Internal CE</button>
        </a> -->

        @if ($project->status == ProjectStatus::FOR_APPROVAL)
            <a href="#" data-href="{{ route('internals.projects.approve', [$project->id]) }}" data-toggle="modal" data-target="#confirm-action">
                <button type="button" class="btn btn-success" id="margin-right"><i class="fa fa-check" id="margin-right"></i>Approve</button>
            </a>

            <a href="#" data-href="{{ route('internals.projects.disapprove', [$project->id]) }}" data-toggle="modal" data-target="#confirm-action">
                <button type="button" class="btn btn-danger"><i class="fa fa-times" id="margin-right"></i>Disapprove</button>
            </a>
        @endif
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')

    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('internals.projects.manage', [$project->id]) }}" class="no-underline">
                <button class="btn btn-light">Project</button>
            </a>

            <a href="{{ route('internals.projects.tasks', [$project->id]) }}" class="no-underline">
                <button class="btn btn-primary">Tasks</button>
            </a>
            <br><br>

            <div id="spaced-card" class="card card-body">
                <div class="row">
                    <div class="col">
                        <h3>Tasks</h3>
                    </div>

                    <div class="col-md-2">
                        <!-- <a href="#" data-toggle="modal" data-target="#add-project-details-{{ $project->id }}">
                            <button type="button" class="btn btn-success form-control" id="table-letter-margin"><i class="material-icons">add</i> Add Details</button>
                        </a> -->
                        
                        <a href="#" data-toggle="modal" data-target="#add-task-{{ $project->id }}">
                            <button type="button" class="btn btn-success form-control" id="table-letter-margin"><i class="material-icons">add</i> Add Task</button>
                        </a>
                    </div>
                </div>
                <br>

                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table">Task</th>
                                <th id="compact-table">Description</th>
                                <th id="compact-table">File</th>
                                <th id="compact-table">Assigned To</th>
                                <th id="compact-table">Created By</th>
                                <th id="compact-table">Status</th>
                                <th id="compact-table">Created At</th>
                                <th id="compact-table">Last Updated At</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                            @foreach ($project_tasks as $project_task)  
                                <tr>
                                    <td id="compact-table"><a href="#" data-toggle="modal" data-target="#edit-task-{{ $project_task->id }}" id="table-clickable">{{ $project_task->name }}</a></td>
                                    <td id="compact-table"><a href="#" data-toggle="modal" data-target="#edit-task-{{ $project_task->id }}" id="table-clickable">{{ $project_task->description }}</a></td>
                                    <td id="compact-table">
                                        @if ($project_task->file)
                                            <a href="{{ url($project_task->file) }}" download>
                                                <button class="btn btn-sm btn-primary">Download File</button>
                                            </a>
                                        @endif
                                    </td>
                                    <td id="compact-table"><a href="#" data-toggle="modal" data-target="#edit-task-{{ $project_task->id }}" id="table-clickable">{{ $project_task->assigned_to_user->firstname }} {{ $project_task->assigned_to_user->lastname }}</a></td>
                                    <td id="compact-table"><a href="#" data-toggle="modal" data-target="#edit-task-{{ $project_task->id }}" id="table-clickable">{{ $project_task->created_by_user->firstname }} {{ $project_task->created_by_user->lastname }}</a></td>
                                    <td id="compact-table">
                                        <a href="#" data-toggle="modal" data-target="#edit-task-{{ $project_task->id }}" id="table-clickable">
                                            @if ($project_task->status == ProjectTaskStatus::PENDING)
                                                <div class="badge badge-warning ml-2">PENDING</div>
                                            @elseif ($project_task->status == ProjectTaskStatus::ON_PROGRESS)
                                                <div class="badge badge-primary ml-2">ON PROGRESS</div>
                                            @elseif ($project_task->status == ProjectTaskStatus::NEED_MORE_INFO)
                                                <div class="badge badge-info ml-2">NEED MORE INFO</div>
                                            @elseif ($project_task->status == ProjectTaskStatus::DONE)
                                                <div class="badge badge-success ml-2">DONE</div>
                                            @elseif ($project_task->status == ProjectTaskStatus::CANCELLED)
                                                <div class="badge badge-danger ml-2">CANCELLED</div>
                                            @elseif ($project_task->status == ProjectTaskStatus::TBD)
                                                <div class="badge badge-info ml-2">TBD</div>
                                            @endif
                                        </a>
                                    </td>
                                    <td id="compact-table"><a href="#" data-toggle="modal" data-target="#edit-task-{{ $project_task->id }}" id="table-clickable">{{ $project_task->created_at->format('M d Y') }}</a></td>
                                    <td id="compact-table"><a href="#" data-toggle="modal" data-target="#edit-task-{{ $project_task->id }}" id="table-clickable">{{ $project_task->updated_at->diffForHumans() }}</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if (count($project_tasks) <= 0)
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
