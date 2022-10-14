<div class="modal fade" id="share-project-{{ $project->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <h5 class="modal-title" id="exampleModalLabel">Project</h5><br>

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
                <h6>CE #:</h6>
              </div>
              <div class="col">
                {{ $project->reference_number }}
              </div>
            </div>
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
            <hr>
            <label>Link</label><br>
            <input type="text" name="link" class="form-control" value="{{ route('share.projects.view', [$project->slug, $project->reference_number]) }}"><br>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>