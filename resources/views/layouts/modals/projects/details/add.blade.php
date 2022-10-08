<form action="{{ route('internals.projects.details.create') }}" method="post">
  {{ csrf_field() }}
  <input type="hidden" name="project_id" value="{{ $project->id }}">
  <div class="modal fade" id="add-project-details-{{ $project->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">Add Project Details</h5><br>

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
              <label>Name</label><br>
              <input type="text" name="name" class="form-control" placeholder="Name" value="{{ old('name') }}"><br>
              <label>Qty</label><br>
              <input type="text" name="qty" class="form-control" placeholder="Qty" value="{{ old('qty') }}"><br>
              <label>Internal CE Price</label><br>
              <input type="text" name="internal_price" class="form-control" placeholder="Internal CE Price" value="{{ old('internal_price') }}"><br>
              <label>Price</label><br>
              <input type="text" name="price" class="form-control" placeholder="Price" value="{{ old('price') }}"><br>
            </div>
          </div>

          <label>Description</label><br>
          <textarea id="tiny" name="description" class="form-control" placeholder="Description">{{ old('description') }}</textarea><br>
          
          <br>

          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>
</form>