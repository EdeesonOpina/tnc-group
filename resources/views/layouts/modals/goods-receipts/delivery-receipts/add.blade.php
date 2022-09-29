<form action="{{ route('internals.goods-receipts.delivery-receipts.create', [$goods_receipt->id]) }}" method="post">
  {{ csrf_field() }}
  <input type="hidden" name="goods_receipt_id" value="{{ $goods_receipt->id }}">
  <div class="modal fade" id="add-delivery-receipt" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">Add Delivery Receipt</h5><br>

          <div class="row">
            <div class="col-md-4">
                @if ($goods_receipt->purchase_order->supplier->image)
                    <img src="{{ url($goods_receipt->purchase_order->supplier->image) }}" width="100%">
                @else
                    <img src="{{ url(env('APP_ICON')) }}" width="100%" style="padding: 5%;">
                @endif
            </div>
            <div class="col">
              <div class="row">
                <div class="col">
                  <h6>GRPO #:</h6>
                </div>
                <div class="col">
                  <h6>{{ $goods_receipt->reference_number }}</h6>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <h6>PO #:</h6>
                </div>
                <div class="col">
                  <h6>{{ $goods_receipt->purchase_order->reference_number }}</h6>
                </div>
              </div>
              <hr>
              <label>Delivery Receipt #</label><br>
              <input type="text" name="delivery_receipt_number" class="form-control" placeholder="Delivery Receipt #" value="{{ old('delivery_receipt_number') }}"><br>
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
