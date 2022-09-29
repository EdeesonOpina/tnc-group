<form action="{{ route('internals.rma.delivery-receipt.find') }}" method="post">
  {{ csrf_field() }}
  <input type="hidden" name="return_inventory_id" value="{{ $return_inventory->id }}">
  <div class="modal fade" id="delivery-receipt-{{ $return_inventory->reference_number }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">Delivery Receipt Number</h5><br>

          <div class="row">
            <div class="col-md-4">
              <img src="{{ url(env('APP_ICON')) }}" width="100%" style="padding: 5%;">
            </div>
            <div class="col">
              <div class="row">
                <div class="col">
                  <strong>SO #:</strong>
                </div>
                <div class="col">
                  <strong>{{ $return_inventory->payment_receipt->so_number }}</strong>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <strong>Customer:</strong>
                </div>
                <div class="col">
                  <strong>{{ $return_inventory->payment_receipt->user->firstname }} {{ $return_inventory->payment_receipt->user->lastname }}</strong>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  Cashier:
                </div>
                <div class="col">
                  {{ $return_inventory->payment_receipt->authorized_user->firstname }} {{ $return_inventory->payment_receipt->authorized_user->lastname }}
                </div>
              </div>
              <hr>
              <label>DR #</label><br>
              <input type="text" name="delivery_receipt_number" class="form-control" placeholder="Enter DR number" value="{{ old('delivery_receipt_number') }}"><br>
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