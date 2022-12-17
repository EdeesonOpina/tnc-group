<form action="{{ route('internals.cv.details.create') }}" method="post" enctype="multipart/form-data">
  {{ csrf_field() }}
  <input type="hidden" name="check_voucher_id" value="{{ $cv->id }}">
  <div class="modal fade" id="add-cv-details-{{ $cv->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">Add Check Voucher Details</h5><br>

          <div class="row">
            <div class="col-md-4">
              <img src="{{ url(env('APP_ICON')) }}" width="100%" style="padding: 5%;">
            </div>
            <div class="col">
              <div class="row">
                <div class="col">
                  <strong>In Payment For:</strong>
                </div>
                <div class="col">
                  {{ $cv->name }}
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <strong>Pay To:</strong>
                </div>
                <div class="col">
                  {{ $cv->pay_to }}
                </div>
              </div>
              <hr>
              <label>CE #</label><br>
              <input type="text" name="reference_number" class="form-control" placeholder="CE #" value="{{ old('reference_number') }}"><br>
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
              <br>
              <label>Description</label><br>
              <input type="text" name="description" class="form-control" placeholder="Description" value="{{ old('description') }}"><br>
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
</form>\