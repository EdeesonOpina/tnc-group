@php
  use App\Models\User;
  use App\Models\UserStatus;
  use App\Models\ProjectTask;
  use App\Models\ProjectTaskStatus;

  $users = User::where('status', UserStatus::ACTIVE)
              ->get();
@endphp

<form action="{{ route('internals.projects.tasks.create', [$project->id]) }}" method="post" enctype="multipart/form-data">
  {{ csrf_field() }}
  <input type="hidden" name="project_id" value="{{ $project->id }}">
  <div class="modal fade" id="add-task-{{ $project->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">Add Task</h5><br>

          <div class="row">
            <div class="col-md-4">
                @if ($project->image)
                    <img src="{{ url($project->image) }}" width="100%">
                @else
                    <img src="{{ url(env('APP_ICON')) }}" width="100%" style="padding: 5%;">
                @endif
            </div>
            <div class="col">
              <div class="row">
                <div class="col">
                  <strong>Name:</strong>
                </div>
                <div class="col">
                  {{ $project->name }}
                </div>
              </div>
              <hr>
              <label>Task</label><br>
              <input type="text" name="name" class="form-control" placeholder="Task" value="{{ old('name') }}"><br>
              <label>Description (optional)</label><br>
              <input type="text" name="description" class="form-control" placeholder="Description" value="{{ old('description') }}"><br>
              <label>Priority</label><br>
              <select name="priority" class="custom-select" data-toggle="select">
                  <option value="Low">Low</option>
                  <option value="Normal">Normal</option>
                  <option value="High">High</option>
                  <option value="URGENT">URGENT</option>
              </select>
              <br><br>
              <label>Assigned To</label><br>
              <select name="assigned_to_user_id" class="custom-select" data-toggle="select">
                  <option value=""></option>
                  @foreach($users as $user)
                      <option value="{{ $user->id }}">{{ $user->firstname }} {{ $user->lastname }}</option>
                  @endforeach
              </select>
              <br><br>
              <label>Status</label><br>
              <select name="status" class="custom-select" data-toggle="select">
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