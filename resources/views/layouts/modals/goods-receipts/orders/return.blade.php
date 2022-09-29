@foreach ($orders as $order)
<form action="{{ route('internals.goods-receipts.orders.return', [$goods_receipt->id]) }}" method="post">
  {{ csrf_field() }}
  <input type="hidden" name="purchase_order_id" value="{{ $goods_receipt->purchase_order->id }}">
  <input type="hidden" name="item_id" value="{{ $order->item->id }}">
  <input type="hidden" name="order_id" value="{{ $order->id }}">
  <div class="modal fade" id="return-order-{{ $order->item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">Return Item</h5><br>

          <div class="row">
            <div class="col-md-4">
                @if ($order->item->image)
                    <img src="{{ url($order->item->image) }}" width="100%">
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
                  <h6>{{ $order->item->name }}</h6>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  Category:
                </div>
                <div class="col">
                  {{ $order->item->category->name }}
                </div>
              </div>
              <div class="row">
                <div class="col">
                  Brand:
                </div>
                <div class="col">
                  {{ $order->item->brand->name }}
                </div>
              </div>
              <hr>
              <label>Enter returning qty</label><br>
              <input type="number" name="returning_qty" class="form-control" placeholder="Enter returning qty" value="{{ $order->received_qty }}" min="0" required><br>
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