@php
    use Carbon\Carbon;
    use App\Models\ServiceOrder;
    use App\Models\ServiceOrderDetail;
    use App\Models\ServiceOrderStatus;
    use App\Models\ServiceOrderDetailStatus;
@endphp
@foreach($service_orders as $service_order)
<form action="{{ route('operations.service-orders.assign.back-job') }}" method="post">
  {{ csrf_field() }}
  <input type="hidden" name="service_order_id" value="{{ $service_order->id }}">
  <div class="modal fade" id="back-job-{{ $service_order->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">Back Job</h5><br>

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
                  <strong>JO:</strong>
                </div>
                <div class="col">
                  <strong>{{ $service_order->jo_number }}</strong>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <strong>Mode of Payment:</strong>
                </div>
                <div class="col">
                  <strong>{{ $service_order->mop }}</strong>
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
              <div class="row">
                <div class="col">
                  Price:
                </div>
                <div class="col">
                  P{{ number_format($service_order->total, 2) }}
                </div>
              </div>
              <hr>
              <label>Back Job Notes</label>
              <textarea name="back_job_notes" class="form-control" placeholder="Enter back job notes here"></textarea>
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