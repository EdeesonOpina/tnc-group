<form action="{{ route('internals.brf.details.create') }}" method="post">
  {{ csrf_field() }}
  <input type="hidden" name="budget_request_form_id" value="{{ $budget_request_form->id }}">
  <div class="modal fade" id="add-brf-{{ $budget_request_form->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">Add BRF</h5><br>

          <div class="row">
            <div class="col-md-4">
                @if ($budget_request_form->project->image)
                    <img src="{{ url($budget_request_form->project->image) }}" width="100%">
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
                  {{ $budget_request_form->project->name }}
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <h6>Client:</h6>
                </div>
                <div class="col">
                  {{ $budget_request_form->project->client->name }}
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <h6>Project Duration:</h6>
                </div>
                <div class="col">
                  {{ $budget_request_form->project->end_date }}
                </div>
              </div>
              <hr>
              <label>Name</label><br>
              <input type="text" name="name" class="form-control" placeholder="Name" value="{{ old('name') }}"><br>
              <div class="row">
                <div class="col">
                  <label>Qty</label><br>
                  <input type="text" name="qty" class="form-control" placeholder="Qty" value="{{ old('qty') ?? 1 }}">
                </div>
                <div class="col">
                  <label>Price</label><br>
                  <input type="text" name="price" class="form-control" placeholder="Price" value="{{ old('price') ?? '0.00' }}">
                </div>
              </div>
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