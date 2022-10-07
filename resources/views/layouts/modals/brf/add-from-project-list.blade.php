@php
  use App\Models\Project;
  use App\Models\ProjectStatus;

  $projects = Project::where('status', '!=', ProjectStatus::INACTIVE)
                  ->where('status', ProjectStatus::FOR_APPROVAL)
                  ->orderBy('created_at', 'desc')
                  ->get();
@endphp

<form action="{{ route('internals.brf.create') }}" method="post">
  {{ csrf_field() }}
  <div class="modal fade" id="add-brf" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">Add BRF</h5><br>

              <label>Project</label><br>
              <select class="form-control" name="project_id">
                <option value=""></option>
                @foreach ($projects as $project)
                  <option value="{{ $project->id }}">{{ $project->name }}</option>
                @endforeach
              </select>
              <br>

              <label>Name</label><br>
              <input type="text" name="name" class="form-control" placeholder="Name" value="{{ old('name') }}"><br>

              <div class="row">
                <div class="col">
                  <label>Qty</label><br>
                  <input type="text" name="qty" class="form-control" placeholder="Qty" value="{{ old('qty') }}">
                </div>
                <div class="col">
                  <label>Price</label><br>
                  <input type="text" name="price" class="form-control" placeholder="Price" value="{{ old('price') }}">
                </div>
              </div>
              <br>
              
              <label>Description</label><br>
              <textarea name="description" class="form-control" placeholder="Description">{{ old('description') }}</textarea><br>

          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>
</form>