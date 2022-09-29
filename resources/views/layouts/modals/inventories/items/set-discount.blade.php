@foreach ($inventories as $inventory)
<form action="{{ route('internals.inventories.items.set-discount', [$inventory->branch_id]) }}" method="post">
  {{ csrf_field() }}
  <input type="hidden" name="inventory_id" value="{{ $inventory->id }}">
  <div class="modal fade" id="set-discount-{{ $inventory->item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">Set Discount</h5><br>

          <div class="row">
            <div class="col-md-4">
                @if ($inventory->item->image)
                    <img src="{{ url($inventory->item->image) }}" width="100%">
                @else
                    <img src="{{ url(env('APP_ICON')) }}" width="100%" style="padding: 5%;">
                @endif
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
              <hr>
              <label>Discount</label><br>
              <input type="text" name="discount" class="form-control" placeholder="Discount" value="{{ $inventory->discount }}"><br>
              <label>From Discount</label><br>
              <input type="date" name="discount_from_date" data-toggle="flatpickr" class="form-control" value="{{ $inventory->discount_from_date }}"><br>
              <label>To Discount</label><br>
              <input type="date" name="discount_to_date" data-toggle="flatpickr" class="form-control" value="{{ $inventory->discount_to_date }}"><br>
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