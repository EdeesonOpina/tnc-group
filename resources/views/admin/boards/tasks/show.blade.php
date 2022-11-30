@include('layouts.auth.header')
@php
    use Carbon\Carbon;
    use App\Models\ProjectTask;
    use App\Models\ProjectTaskStatus;
@endphp

<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('internals.boards.tasks') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item">Boards</li>
                </ol>
            </nav>
            <h1 class="m-0">Boards</h1>
        </div>

        <a href="#" data-toggle="modal" data-target="#graph-activities">
            <button type="button" class="btn btn-light" id="margin-right">Activity Graph</button>
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
                        <h3>Tasks</h3>
                    </div>

                    <div class="col-md-2">
                        <a href="#" data-toggle="modal" data-target="#add-task">
                            <button type="button" class="btn btn-success form-control" id="table-letter-margin"><i class="material-icons">add</i> Add Task</button>
                        </a>
                    </div>
                </div>
                <br>
                <strong>{{ $completed }} out of {{ $total }} tasks completed</strong>
                {{ $percentage }}% progress
                <div class="progress">
                  <div class="progress-bar bg-success" role="progressbar" aria-label="Success example" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
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
                                <th id="compact-table">Priority</th>
                                <th id="compact-table">Deadline</th>
                                <th id="compact-table">Completed At</th>
                                <th id="compact-table">Created At</th>
                                <th id="compact-table">Last Updated At</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                            @foreach ($board_tasks as $board_task)  
                                <tr>
                                    <td id="compact-table"><a href="#" data-toggle="modal" data-target="#edit-task-{{ $board_task->id }}" id="table-clickable">{{ $board_task->name }}</a> 
                                        @if ($board_task->created_by_user_id == auth()->user()->id)
                                            <a href="#" data-href="{{ route('internals.boards.tasks.delete', [$board_task->id]) }}" class="text-danger" data-toggle="modal" data-target="#confirm-action" ><i class="fa fa-trash"></i></a>
                                        @endif
                                    </td>
                                    <td id="compact-table"><a href="#" data-toggle="modal" data-target="#edit-task-{{ $board_task->id }}" id="table-clickable">{{ $board_task->description }}</a></td>
                                    <td id="compact-table">
                                        @if ($board_task->file)
                                            <a href="{{ url($board_task->file) }}" download>
                                                <button class="btn btn-sm btn-primary">Download File</button>
                                            </a>
                                        @endif
                                    </td>
                                    <td id="compact-table"><a href="#" data-toggle="modal" data-target="#edit-task-{{ $board_task->id }}" id="table-clickable">{{ $board_task->assigned_to_user->firstname }} {{ $board_task->assigned_to_user->lastname }}</a></td>
                                    <td id="compact-table"><a href="#" data-toggle="modal" data-target="#edit-task-{{ $board_task->id }}" id="table-clickable">{{ $board_task->created_by_user->firstname }} {{ $board_task->created_by_user->lastname }}</a></td>
                                    <td id="compact-table">
                                        <a href="#" data-toggle="modal" data-target="#edit-task-{{ $board_task->id }}" id="table-clickable">
                                            @if ($board_task->status == ProjectTaskStatus::PENDING)
                                                <div class="badge badge-warning ml-2">PENDING</div>
                                            @elseif ($board_task->status == ProjectTaskStatus::ON_PROGRESS)
                                                <div class="badge badge-primary ml-2">ON PROGRESS</div>
                                            @elseif ($board_task->status == ProjectTaskStatus::NEED_MORE_INFO)
                                                <div class="badge badge-info ml-2">NEED MORE INFO</div>
                                            @elseif ($board_task->status == ProjectTaskStatus::DONE)
                                                <div class="badge badge-success ml-2">DONE</div>
                                            @elseif ($board_task->status == ProjectTaskStatus::CANCELLED)
                                                <div class="badge badge-danger ml-2">CANCELLED</div>
                                            @elseif ($board_task->status == ProjectTaskStatus::TBD)
                                                <div class="badge badge-info ml-2">TBD</div>
                                            @endif
                                        </a>
                                    </td>
                                    <td><a href="#" data-toggle="modal" data-target="#edit-task-{{ $board_task->id }}" id="table-clickable">{{ $board_task->priority }}</a></td>
                                    <td id="compact-table">
                                        @if ($board_task->deadline_date)
                                            <a href="#" data-toggle="modal" data-target="#edit-task-{{ $board_task->id }}" id="table-clickable">{{ Carbon::parse($board_task->deadline_date)->format('M d Y') }}</a>
                                        @endif
                                    </td>
                                    <td id="compact-table">
                                        @if ($board_task->completed_at)
                                            <a href="#" data-toggle="modal" data-target="#edit-task-{{ $board_task->id }}" id="table-clickable">{{ Carbon::parse($board_task->completed_at)->format('M d Y') }}</a>
                                        @endif
                                    </td>
                                    <td id="compact-table"><a href="#" data-toggle="modal" data-target="#edit-task-{{ $board_task->id }}" id="table-clickable">{{ $board_task->created_at->format('M d Y') }}</a></td>
                                    <td id="compact-table"><a href="#" data-toggle="modal" data-target="#edit-task-{{ $board_task->id }}" id="table-clickable">{{ $board_task->updated_at->diffForHumans() }}</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if (count($board_tasks) <= 0)
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

<script>
  $(function(){
      //get the line chart canvas
      var cData = JSON.parse(`<?php echo $last_30_days_chart['chart_data']; ?>`);
      var ctx = $("#line-chart-30-days");
 
      //pie chart data
      var data = {
        labels: cData.label,
        datasets: [
          {
            label: "Task Management",
            data: cData.data,
            backgroundColor: [
               "#51D016", 
            ],
            borderColor: [
              "#51D016",
            ],
            borderWidth: [1]
          }
        ]
      };
 
      //options
      var options = {
        responsive: true,
        title: {
          display: true,
          position: "top",
          text: "Last 30 Days",
          fontColor: "#111"
        },
        legend: {
          display: true,
          position: "bottom",
          labels: {
            fontColor: "#333"
          }
        }
      };
 
      //create Pie Chart class object
      var chart1 = new Chart(ctx, {
        type: "line",
        data: data,
        options: options
      });
 
  });
</script>