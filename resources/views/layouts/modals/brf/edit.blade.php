@foreach ($budget_request_form_details as $budget_request_form_detail)
<form action="{{ route('internals.brf.details.update') }}" method="post" enctype="multipart/form-data">
  {{ csrf_field() }}
  <input type="hidden" name="budget_request_form_detail_id" value="{{ $budget_request_form_detail->id }}">
    <div class="modal fade" id="edit-brf-detail-{{ $budget_request_form_detail->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <h5 class="modal-title" id="exampleModalLabel">Edit BRF</h5><br>

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
                    <label>Name:</label>
                  </div>
                  <div class="col">
                    {{ $budget_request_form->project->name }}
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <label>Client:</label>
                  </div>
                  <div class="col">
                    {{ $budget_request_form->project->client->name }}
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <label>Project Duration:</label>
                  </div>
                  <div class="col">
                    {{ $budget_request_form->project->end_date }}
                  </div>
                </div>
                <hr>
                <label>Name</label><br>
                <input type="text" name="name" class="form-control" placeholder="Name" value="{{ old('name') ?? $budget_request_form_detail->name }}"><br>
                <div class="row">
                  <div class="col">
                    <label>Qty</label><br>
                    <input type="text" name="qty" class="form-control" placeholder="Qty" value="{{ old('qty') ?? $budget_request_form_detail->qty }}">
                  </div>
                  <div class="col">
                    <label>Price</label><br>
                    <input type="text" name="price" class="form-control" placeholder="Price" value="{{ old('price') ?? $budget_request_form_detail->price }}">
                  </div>
                </div>
                <br>
                <label>Description</label><br>
                <input type="text" name="description" class="form-control" placeholder="Description" value="{{ old('description') ?? $budget_request_form_detail->description }}"><br>
                <label>File</label><br>
                <input type="file" name="file"><br>
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