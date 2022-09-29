@php
    use Carbon\Carbon;
    use App\Models\ServiceOrder;
    use App\Models\ServiceOrderDetail;
    use App\Models\ServiceOrderStatus;
    use App\Models\ServiceOrderDetailStatus;
@endphp

@foreach($service_orders as $service_order)
  @php
      $service_order_details_count = ServiceOrderDetail::where('service_order_id', $service_order->id)
                              ->where('status', '!=', ServiceOrderDetailStatus::INACTIVE)
                              ->sum('qty');

      $service_order_details_total = ServiceOrderDetail::where('service_order_id', $service_order->id)
                              ->where('status', '!=', ServiceOrderDetailStatus::INACTIVE)
                              ->sum('total');
  @endphp
<form action="{{ route('operations.service-orders.assign.date-out') }}" method="post">
  {{ csrf_field() }}
  <input type="hidden" name="service_order_id" value="{{ $service_order->id }}">
  <div class="modal fade" id="assign-date-out-{{ $service_order->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">Date Out</h5><br>

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
                  <strong>Name:</strong>
                </div>
                <div class="col">
                  <strong>{{ $service_order->name }}</strong>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  Qty:
                </div>
                <div class="col">
                  {{ $service_order->qty }}
                </div>
              </div>
              <div class="row">
                <div class="col">
                  Price:
                </div>
                <div class="col">
                  P{{ number_format($service_order->price, 2) }}
                </div>
              </div>
              <hr>
              <label>Date Out</label>
              <input type="date" name="date_out" class="form-control" value="{{ old('date_out') ?? Carbon::now() }}">

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