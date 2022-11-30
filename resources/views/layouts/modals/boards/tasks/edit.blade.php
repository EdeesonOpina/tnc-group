@php
  use App\Models\User;
  use App\Models\UserStatus;
  use App\Models\ProjectTask;
  use App\Models\ProjectTaskStatus;

  $users = User::where('status', UserStatus::ACTIVE)
              ->get();
@endphp

@foreach ($board_tasks as $board_task)
  <form action="{{ route('internals.boards.tasks.update') }}" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
    <input type="hidden" name="board_task_id" value="{{ $board_task->id }}">
    <div class="modal fade" id="edit-task-{{ $board_task->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <h5 class="modal-title" id="exampleModalLabel">Edit Task</h5><br>

              <div class="row">
              <div class="col-md-4">
                <img src="{{ url(env('APP_ICON')) }}" width="100%" style="padding: 5%;">
              </div>
              <div class="col">
                <label>Task</label><br>
                <input type="text" name="name" class="form-control" placeholder="Task" value="{{ old('name') ?? $board_task->name }}"><br>
                <label>Description (optional)</label><br>
                <input type="text" name="description" class="form-control" placeholder="Description" value="{{ old('description') ?? $board_task->description }}"><br>
                <label>Deadline Date</label><br>
                <input type="date" name="deadline_date" class="form-control" value="{{ old('deadline_date') ?? $board_task->deadline_date }}"><br>
                <label>Priority</label><br>
                <select name="priority" class="custom-select" data-toggle="select">
                    <option value="{{ $board_task->priority }}">{{ $board_task->priority }}</option>
                    <option value="Low">Low</option>
                    <option value="Normal">Normal</option>
                    <option value="High">High</option>
                    <option value="URGENT">URGENT</option>
                </select>
                <br><br>
                <label>Assigned To</label><br>
                <select name="assigned_to_user_id" class="custom-select" data-toggle="select">
                    <option value="{{ $board_task->assigned_to_user->id }}">{{ $board_task->assigned_to_user->firstname }} {{ $board_task->assigned_to_user->lastname }}</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->firstname }} {{ $user->lastname }}</option>
                    @endforeach
                </select>
                <br><br>
                <label>Status</label><br>
                <select name="status" class="custom-select" data-toggle="select">
                    <option value="{{ $board_task->status }}">
                      @if ($board_task->status == ProjectTaskStatus::PENDING)
                        Pending
                      @endif

                      @if ($board_task->status == ProjectTaskStatus::ON_PROGRESS)
                        On Progress
                      @endif

                      @if ($board_task->status == ProjectTaskStatus::NEED_MORE_INFO)
                        Need More Info
                      @endif

                      @if ($board_task->status == ProjectTaskStatus::DONE)
                        Done
                      @endif

                      @if ($board_task->status == ProjectTaskStatus::CANCELLED)
                        Cancelled
                      @endif

                      @if ($board_task->status == ProjectTaskStatus::TBD)
                        TBD
                      @endif
                    </option>
                    <option value="{{ ProjectTaskStatus::PENDING }}">Pending</option>
                    <option value="{{ ProjectTaskStatus::ON_PROGRESS }}">On Progress</option>
                    <option value="{{ ProjectTaskStatus::NEED_MORE_INFO }}">Need More Info</option>
                    <option value="{{ ProjectTaskStatus::DONE }}">Done</option>
                    <option value="{{ ProjectTaskStatus::CANCELLED }}">Cancelled</option>
                    <option value="{{ ProjectTaskStatus::TBD }}">TBD</option>
                </select>
                <br><br>
                <label>File (optional)</label><br>
                <input type="file" name="file">
              </div>
            </div>
            
            <br>

            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>
  </form>
@endforeach