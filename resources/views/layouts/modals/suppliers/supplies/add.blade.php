@foreach ($items as $item)
<form action="{{ route('admin.supplies.create', [$supplier->id]) }}" method="post">
  {{ csrf_field() }}
  <input type="hidden" name="item_id" value="{{ $item->id }}">
  <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
  <div class="modal fade" id="add-supply-{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">Add Item To List</h5><br>

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
                  <h6>Name:</h6>
                </div>
                <div class="col">
                  <h6>{{ $item->name }}</h6>
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
              <label>Price</label><br>
              <input type="text" name="price" class="form-control" placeholder="Price" value="{{ old('price') }}">
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