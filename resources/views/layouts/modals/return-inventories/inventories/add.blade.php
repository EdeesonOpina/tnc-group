@foreach ($inventories as $inventory)
<form action="{{ route('internals.return-inventories.inventories.create', [$return_inventory->supplier->id]) }}" method="post">
  {{ csrf_field() }}
  <input type="hidden" name="return_inventory_id" value="{{ $return_inventory->id }}">
  <input type="hidden" name="inventory_id" value="{{ $inventory->id }}">
  <div class="modal fade" id="add-return-inventory-item-{{ $inventory->item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">Add Item To List</h5><br>

          <div class="row">
            <div class="col-md-4">
                @if ($inventory->item->image)
                    <img src="{{ url($inventory->item->image) }}" width="100%">
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
                  <h6>{{ $inventory->item->name }}</h6>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  Category:
                </div>
                <div class="col">
                  {{ $inventory->item->category->name }}
                </div>
              </div>
              <div class="row">
                <div class="col">
                  Brand:
                </div>
                <div class="col">
                  {{ $inventory->item->brand->name }}
                </div>
              </div>
              <div class="row">
                <div class="col">
                  In Stock:
                </div>
                <div class="col">
                  {{ $inventory->qty }}
                </div>
              </div>
              <hr>
              <label>Qty</label><br>
              <input type="number" name="qty" class="form-control" placeholder="Qty" value="{{ 1 }}" min="1"><br>

              <label>Note</label><br>
              <input type="text" name="note" class="form-control" placeholder="Enter note here">
            </div>
          </div>
          
          <br>

          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="submitButton" onclick="submitForm(this);">Save changes</button>
        </div>
      </div>
    </div>
  </div>
</form>
@endforeach