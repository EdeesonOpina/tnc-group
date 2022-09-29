@php
  use App\Models\ItemPhotoStatus;
@endphp

@foreach($item_photos as $item_photo)
  <form action="{{ route('admin.items.photos.update', [$item->id]) }}" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
    <input type="hidden" name="item_id" value="{{ $item->id }}">
    <input type="hidden" name="item_photo_id" value="{{ $item_photo->id }}">
    <div class="modal fade" id="edit-item-photo-{{ $item_photo->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <h5 class="modal-title" id="exampleModalLabel">Edit Item Photo</h5><br>

            <div class="row">
              <div class="col-md-4">
                  @if ($item_photo->image)
                      <img src="{{ url($item_photo->image) }}" width="100%">
                  @else
                      <img src="{{ url(env('APP_ICON')) }}" width="100%" style="padding: 5%;">
                  @endif
              </div>
              <div class="col">
                <div class="row">
                  <div class="col">
                    {!! QrCode::size(50)->generate(url(route('qr.items.view', [$item_photo->id]))) !!}
                  </div>
                  <div class="col">
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col">
                    <strong>ID:</strong>
                  </div>
                  <div class="col">
                    #{{ $item_photo->id }}
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <strong>Name:</strong>
                  </div>
                  <div class="col">
                    <strong>{{ $item_photo->name }}</strong>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <strong>Status:</strong>
                  </div>
                  <div class="col">
                    @if ($item_photo->status == ItemPhotoStatus::ACTIVE)
                      <div class="badge badge-success">ACTIVE</div>
                    @else
                      <div class="badge badge-danger">INACTIVE</div>
                    @endif
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <strong>Created At:</strong>
                  </div>
                  <div class="col">
                    {{ $item_photo->created_at->format('M d Y') }}
                  </div>
                </div>
                <hr>
                <label>Name</label><br>
                <input type="text" name="name" class="form-control" placeholder="Enter name" value="{{ $item_photo->name }}" required><br>
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
@endforeach