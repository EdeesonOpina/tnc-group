<form action="{{ route('internals.projects.update.asf') }}" method="post">
  {{ csrf_field() }}
  <input type="hidden" name="project_id" value="{{ $project->id }}">
  <div class="modal fade" id="asf-{{ $project->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">ASF</h5><br>

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
                  <h6>Name:</h6>
                </div>
                <div class="col">
                  {{ $project->name }}
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <h6>Client:</h6>
                </div>
                <div class="col">
                  {{ $project->client->name }}
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <h6>Project Duration:</h6>
                </div>
                <div class="col">
                  {{ $project->end_date }}
                </div>
              </div>
              <hr>
              <label>ASF</label><br>
              <input type="text" name="asf" class="form-control" placeholder="ASF" value="{{ old('asf') ?? $project->asf  }}"><br>
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