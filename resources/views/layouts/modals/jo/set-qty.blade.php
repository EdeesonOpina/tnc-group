@foreach ($service_order_details as $service_order_detail)
<form action="{{ route('jo.set-qty') }}" method="post">
  {{ csrf_field() }}
  <input type="hidden" name="service_order_detail_id" value="{{ $service_order_detail->id }}">
  <div class="modal fade" id="set-qty-{{ $service_order_detail->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">Set Qty</h5><br>

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
                  Qty:
                </div>
                <div class="col">
                  {{ $service_order_detail->qty }}
                </div>
              </div>
              <div class="row">
                <div class="col">
                  Price:
                </div>
                <div class="col">
                  P{{ number_format($service_order_detail->price, 2) }}
                </div>
              </div>
              <hr>
              <label>Qty</label><br>
              <input type="text" name="qty" class="form-control" placeholder="Qty" value="{{ $service_order_detail->qty ?? $qty }}"><br>

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