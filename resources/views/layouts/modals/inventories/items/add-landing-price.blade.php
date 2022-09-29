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

<form action="{{ route('internals.inventories.landing-price.create', [$inventory->company_id, $inventory->id]) }}" method="post">
  {{ csrf_field() }}
  <input type="hidden" name="inventory_id" value="{{ $inventory->id }}">
  <input type="hidden" name="item_id" value="{{ $inventory->item->id }}">
  <div class="modal fade" id="add-landing-price-{{ $inventory->item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">Add Landing Price</h5><br>
              <label>Price</label><br>
              <input type="text" name="price" class="form-control" placeholder="Price" value="{{ $inventory->price ?? '0.00' }}"><br>
          <br>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>
</form>
@endforeach