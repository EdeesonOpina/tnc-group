<form action="{{ route('operations.service-orders.assign.mop') }}" method="post">
  {{ csrf_field() }}
  <input type="hidden" name="service_order_id" value="{{ $service_order->id }}">
  <div class="modal fade" id="assign-mop-{{ $service_order->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">Mode of Payment</h5><br>

          <div class="row">
            <div class="col-md-4">
                <img src="{{ url(env('APP_ICON')) }}" width="100%" style="padding: 5%;">
            </div>
            <div class="col">
              <div class="row">
                <div class="col">
                  {!! QrCode::size(50)->generate(url(route('qr.items.view', [$service_order->id]))) !!}
                </div>
                <div class="col">
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col">
                  <strong>Service:</strong>
                </div>
                <div class="col">
                  <strong>{{ $service_order->name }}</strong>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <strong>Qty:</strong>
                </div>
                <div class="col">
                  <strong>{{ $service_order->qty }}</strong>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <strong>Price:</strong>
                </div>
                <div class="col">
                  <strong>P{{ number_format($service_order->price, 2) }}</strong>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  Technical:
                </div>
                <div class="col">
                  {{ $service_order->authorized_user->firstname }} {{ $service_order->authorized_user->lastname }}
                </div>
              </div>
              <hr>
              <label>Mode of Payment</label><br>
              <select class="form-control" name="mop">
                <option value="{{ $service_order->mop }}">{{ $service_order->mop }}</option>
                <option value="cash">Cash</option>
                <option value="credit">Credit</option>
                <option value="credit-card">Credit Card</option>
                <option value="cheque">Cheque</option>
                <option value="bank-deposit">Bank Deposit</option>
              </select>
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