@foreach ($supplies as $supply)
<form action="{{ route('internals.orders.create', [$purchase_order->supplier->id]) }}" method="post">
  {{ csrf_field() }}
  <input type="hidden" name="purchase_order_id" value="{{ $purchase_order->id }}">
  <input type="hidden" name="item_id" value="{{ $supply->item->id }}">
  <input type="hidden" name="supply_id" value="{{ $supply->id }}">
  <div class="modal fade" id="add-order-{{ $supply->item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">Add Item To List</h5><br>

          <div class="row">
            <div class="col-md-4">
                @if ($supply->item->image)
                    <img src="{{ url($supply->item->image) }}" width="100%">
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
                  <h6>{{ $supply->item->name }}</h6>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  Category:
                </div>
                <div class="col">
                  {{ $supply->item->category->name }}
                </div>
              </div>
              <div class="row">
                <div class="col">
                  Brand:
                </div>
                <div class="col">
                  {{ $supply->item->brand->name }}
                </div>
              </div>
              <hr>
              <label>Price</label><br>
              <input type="text" name="price" class="form-control" placeholder="Price" value="{{ $supply->price }}"><br>

              <label>Discount</label><br>
              <input type="text" name="discount" class="form-control" placeholder="Discount" value="{{ '0.00' }}"><br>

              <label>Qty</label><br>
              <input type="number" name="qty" class="form-control" placeholder="Qty" value="{{ 1 }}" min="1"><br>

              <label>Free Qty</label><br>
              <input type="number" name="free_qty" class="form-control" placeholder="Free Qty" value="{{ 0 }}" min="0">
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