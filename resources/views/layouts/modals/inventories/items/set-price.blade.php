@php
  use App\Models\Order;
  use App\Models\OrderStatus;
  use App\Models\InventoryReceiveRecord;
@endphp

@foreach ($inventories as $inventory)
@php
  $inventory_receive_record = InventoryReceiveRecord::where('inventory_id', $inventory->id)
                                                ->latest()
                                                ->first();

  $order = Order::where('item_id', $inventory->item->id)
              ->where('status', OrderStatus::ACTIVE)
              ->latest()
              ->first();

  $price = $order->price ?? '0.00';
  $agent_price = ($price + ($price * 0.02)) ?? '0.00';
@endphp

<form action="{{ route('internals.inventories.items.set-price', [$inventory->branch_id]) }}" method="post">
  {{ csrf_field() }}
  <input type="hidden" name="inventory_id" value="{{ $inventory->id }}">
  <div class="modal fade" id="set-price-{{ $inventory->item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">Set Price</h5><br>

          <div class="row">
            <div class="col-md-4">
                @if ($inventory->item->image)
                    <img src="{{ url($inventory->item->image) }}" width="100%">
                @else
                    <img src="{{ url(env('APP_ICON')) }}" width="100%" style="padding: 5%;">
                @endif
                <br><br>
                <strong class="text-success">Suggested Agent Price: </strong><strong>(2%)</strong><br>
                <!-- <strong>P{{ number_format($agent_price, 2) }}</strong><br> -->
                <strong>P{{ number_format($agent_price, 2) }}</strong><br>
                <strong class="text-success">Landed Price:</strong><br><strong>P{{ number_format($price, 2) }}</strong><br><br>
                <small>Check history landing prices? <a href="{{ route('internals.inventories.landing-price.masterlist', [$inventory->branch_id, $inventory->id]) }}">Click Here</a></small>
                <br><br>
                <a href="#" data-toggle="modal" data-target="#add-landing-price-{{ $inventory->item->id }}" >
                  <button class="btn btn-success btn-sm form-control">Add Landing Price</button>
                </a>
            </div>
            <div class="col">
              <div class="row">
                <div class="col">
                  {!! QrCode::size(50)->generate(url(route('qr.items.view', [$inventory->item->id]))) !!}
                </div>
                <div class="col">
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col">
                  <strong>Barcode:</strong>
                </div>
                <div class="col">
                  <strong>{{ $inventory->item->barcode }}</strong>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <strong>Name:</strong>
                </div>
                <div class="col">
                  <strong>{{ $inventory->item->name }}</strong>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  Category:
                </div>
                <div class="col">
                  {{ $inventory->item->category->name }}
                </div>
              </div>
              <div class="row">
                <div class="col">
                  Brand:
                </div>
                <div class="col">
                  {{ $inventory->item->brand->name }}
                </div>
              </div>
              <div class="row">
                <div class="col">
                  Price:
                </div>
                <div class="col">
                  P{{ number_format($inventory->price, 2) }}
                </div>
              </div>
              <div class="row">
                <div class="col">
                  Agent Price:
                </div>
                <div class="col">
                  P{{ number_format($inventory->agent_price, 2) }}
                </div>
              </div>
              <hr>
              <label>Agent Price</label><br>
              @if ($inventory->agent_price > 0)
                <input type="text" name="agent_price" class="form-control" placeholder="Agent Price" value="{{ $inventory->agent_price }}"><br>
              @else
                <input type="text" name="agent_price" class="form-control" placeholder="Agent Price" value="{{ $agent_price }}"><br> 
              @endif

              <label>Price</label><br>
              {{-- <input type="text" name="price" class="form-control" placeholder="Price" value="{{ $inventory->price ?? $price }}"><br> --}}
              <input type="text" name="price" class="form-control" placeholder="Price" value="{{ $inventory->price ?? '0.00' }}"><br>

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