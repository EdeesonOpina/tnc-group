<form action="{{ route('internals.projects.update.usd-rate') }}" method="post">
  {{ csrf_field() }}
  <input type="hidden" name="project_id" value="{{ $project->id }}">
  <div class="modal fade" id="usd-rate-{{ $project->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">USD Rate</h5><br>

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
              <div class="row">
                <div class="col">
                  <strong>Client:</strong>
                </div>
                <div class="col">
                  {{ $project->client->name }}
                </div>
              </div>
              <hr>
              <label>USD Rate</label><br>
              <input type="text" name="usd_rate" class="form-control" placeholder="USD Rate" value="{{ old('usd_rate') ?? $project->usd_rate }}"><br>
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