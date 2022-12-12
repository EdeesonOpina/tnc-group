@php
  $margin_price = ($project->total * ($project->margin / 100));
@endphp

<form action="{{ route('internals.projects.update.signed-ce') }}" method="post" enctype="multipart/form-data">
  {{ csrf_field() }}
  <input type="hidden" name="project_id" value="{{ $project->id }}">
  <div class="modal fade" id="signed-ce-{{ $project->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">Signed CE</h5><br>

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
              <div class="row">
                <div class="col">
                  <strong>Total Cost:</strong>
                </div>
                <div class="col">
                  P{{ number_format($project->total, 2) }}
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <strong>Margin Rate (%):</strong>
                </div>
                <div class="col">
                  {{ $project->margin }}%
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <strong>Suggested Rate:</strong>
                </div>
                <div class="col">
                  P{{ number_format($margin_price, 2) }}
                </div>
              </div>
              <hr>
              <label>Upload File</label><br>
              <input type="file" name="signed_ce"><br>
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