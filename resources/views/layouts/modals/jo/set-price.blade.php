@foreach ($service_order_details as $service_order_detail)
<form action="{{ route('operations.service-orders.update.price') }}" method="post">
  {{ csrf_field() }}
  <input type="hidden" name="service_order_detail_id" value="{{ $service_order_detail->id }}">
  <div class="modal fade" id="set-price-{{ $service_order_detail->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">Set Price</h5><br>

          <div class="row">
            <div class="col-md-4">
                <img src="{{ url(env('APP_ICON')) }}" width="100%" style="padding: 5%;">
            </div>
            <div class="col">
              <div class="row">
                <div class="col">
                  {!! QrCode::size(50)->generate(url(route('qr.items.view', [$service_order_detail->id]))) !!}
                </div>
                <div class="col">
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col">
                  <strong>Name:</strong>
                </div>
                <div class="col">
                  <strong>{{ $service_order_detail->name }}</strong>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <strong>Qty:</strong>
                </div>
                <div class="col">
                  <strong>{{ $service_order_detail->qty }}</strong>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <strong>Price:</strong>
                </div>
                <div class="col">
                  <strong>P{{ number_format($service_order_detail->price, 2) }}</strong>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  Technical:
                </div>
                <div class="col">
                  {{ $service_order_detail->service_order->authorized_user->firstname }} {{ $service_order_detail->service_order->authorized_user->lastname }}
                </div>
              </div>
              <hr>
              <label>Price</label><br>
              <input type="text" name="price" class="form-control" placeholder="Price" value="{{ $service_order_detail->price }}"><br>

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