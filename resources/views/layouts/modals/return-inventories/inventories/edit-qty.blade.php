@foreach ($return_inventory_items as $return_inventory_item)
  <form action="{{ route('internals.return-inventories.inventories.update.qty', [$return_inventory->id]) }}" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="return_inventory_item_id" value="{{ $return_inventory_item->id }}">
    <div class="modal fade" id="edit-return-inventory-item-qty-{{ $return_inventory_item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <h5 class="modal-title" id="exampleModalLabel">Edit Qty</h5><br>

            <div class="row">
              <div class="col-md-4">
                  @if ($return_inventory_item->inventory->item->image)
                      <img src="{{ url($return_inventory_item->inventory->item->image) }}" width="100%">
                  @else
                      <img src="{{ url(env('APP_ICON')) }}" width="100%" style="padding: 5%;">
                  @endif
              </div>
              <div class="col">
                <!-- START DISPLAY INFO -->
                <div class="row">
                  <div class="col">
                    <h6>Name:</h6>
                  </div>
                  <div class="col">
                    <h6>{{ $return_inventory_item->inventory->item->name }}</h6>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    Category:
                  </div>
                  <div class="col">
                    {{ $return_inventory_item->inventory->item->category->name }}
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    Brand:
                  </div>
                  <div class="col">
                    {{ $return_inventory_item->inventory->item->brand->name }}
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    In Stock:
                  </div>
                  <div class="col">
                    {{ $return_inventory_item->inventory->qty }}
                  </div>
                </div>
                <!-- END DISPLAY INFO -->
                <hr>
                <label>Qty</label><br>
                <input type="number" name="qty" class="form-control" placeholder="Qty" value="{{ $return_inventory_item->qty }}" min="0">
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