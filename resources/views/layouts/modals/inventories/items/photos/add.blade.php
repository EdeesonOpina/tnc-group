<form action="{{ route('admin.items.photos.create', [$item->id]) }}" method="post" enctype="multipart/form-data">
  {{ csrf_field() }}
  <input type="hidden" name="item_id" value="{{ $item->id }}">
  <div class="modal fade" id="add-item-photo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">Add Item Photo</h5><br>

          <div class="row">
            <div class="col-md-4">
                @if ($item->image)
                    <img src="{{ url($item->image) }}" width="100%">
                @else
                    <img src="{{ url(env('APP_ICON')) }}" width="100%" style="padding: 5%;">
                @endif
            </div>
            <div class="col">
              <div class="row">
                <div class="col">
                  {!! QrCode::size(50)->generate(url(route('qr.items.view', [$item->id]))) !!}
                </div>
                <div class="col">
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col">
                  <strong>Barcode:</strong>
                </div>
                <div class="col">
                  <strong>{{ $item->barcode }}</strong>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <strong>Name:</strong>
                </div>
                <div class="col">
                  <strong>{{ $item->name }}</strong>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  Category:
                </div>
                <div class="col">
                  {{ $item->category->name }}
                </div>
              </div>
              <div class="row">
                <div class="col">
                  Brand:
                </div>
                <div class="col">
                  {{ $item->brand->name }}
                </div>
              </div>
              <hr>
              <label>Name</label><br>
              <input type="text" name="name" class="form-control" placeholder="Enter name" required><br>
              <label>Upload File</label><br>
              <input type="file" name="image">
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