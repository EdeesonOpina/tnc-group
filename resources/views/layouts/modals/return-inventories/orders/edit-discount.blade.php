@php
  use App\Models\Order;
@endphp

@if (request()->is('admin/purchase-orders/manage/*'))
  @foreach ($supplies as $supply)
    @php
        $order = Order::where('purchase_order_id', $purchase_order->id)
                    ->where('supply_id', $supply->id)
                    ->first()
    @endphp
    @if ($order)
      <form action="{{ route('internals.orders.update.discount', [$purchase_order->supplier->id]) }}" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="order_id" value="{{ $order->id }}">
        <div class="modal fade" id="edit-order-discount-{{ $order->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-body">
                <h5 class="modal-title" id="exampleModalLabel">Edit Discount</h5><br>

                <div class="row">
                  <div class="col-md-4">
                      @if ($order->supply->item->image)
                          <img src="{{ url($order->supply->item->image) }}" width="100%">
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
                        <h6>{{ $order->supply->item->name }}</h6>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col">
                        Category:
                      </div>
                      <div class="col">
                        {{ $order->supply->item->category->name }}
                      </div>
                    </div>
                    <div class="row">
                      <div class="col">
                        Brand:
                      </div>
                      <div class="col">
                        {{ $order->supply->item->brand->name }}
                      </div>
                    </div>
                    <!-- END DISPLAY INFO -->
                    <hr>
                    <label>Discount</label><br>
                    <input type="text" name="discount" class="form-control" placeholder="Discount" value="{{ $order->discount }}">
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
    @endif
  @endforeach

@else
  @foreach ($orders as $order)
  <form action="{{ route('internals.orders.update.discount', [$purchase_order->supplier->id]) }}" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="order_id" value="{{ $order->id }}">
    <div class="modal fade" id="edit-order-discount-{{ $order->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <h5 class="modal-title" id="exampleModalLabel">Edit Discount</h5><br>

            <div class="row">
              <div class="col-md-4">
                  @if ($order->supply->item->image)
                      <img src="{{ url($order->supply->item->image) }}" width="100%">
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
                    <h6>{{ $order->supply->item->name }}</h6>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    Category:
                  </div>
                  <div class="col">
                    {{ $order->supply->item->category->name }}
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    Brand:
                  </div>
                  <div class="col">
                    {{ $order->supply->item->brand->name }}
                  </div>
                </div>
                <!-- END DISPLAY INFO -->
                <hr>
                <label>Discount</label><br>
                <input type="text" name="discount" class="form-control" placeholder="Discount" value="{{ $order->discount }}">
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
@endif