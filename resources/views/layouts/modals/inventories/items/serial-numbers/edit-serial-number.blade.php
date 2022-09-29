@foreach ($item_serial_numbers as $item_serial_number)
  <form action="{{ route('internals.inventories.items.serial-numbers.update', [$item->id]) }}" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="item_serial_number_id" value="{{ $item_serial_number->id }}">
    <input type="hidden" name="item_id" value="{{ $item->id }}">
    <div class="modal fade" id="edit-serial-number-{{ $item_serial_number->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <h5 class="modal-title" id="exampleModalLabel">Edit Item Serial Number</h5><br>

            <div class="row">
              <div class="col-md-4">
                  @if ($item->image)
                      <img src="{{ url($item->image) }}" width="100%">
                  @else
                      <img src="{{ url(env('APP_ICON')) }}" width="100%" style="padding: 5%;">
                  @endif
              </div>
              <div class="col">
                <div class="row">
                  <div class="col">
                    {!! QrCode::size(50)->generate(url(route('qr.items.view', [$item->id]))) !!}
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
                    <strong>{{ $item->barcode }}</strong>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <strong>Name:</strong>
                  </div>
                  <div class="col">
                    <strong>{{ $item->name }}</strong>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    Category:
                  </div>
                  <div class="col">
                    {{ $item->category->name }}
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    Brand:
                  </div>
                  <div class="col">
                    {{ $item->brand->name }}
                  </div>
                </div>
                <hr>
                <label>Serial Number</label><br>
                <input type="text" name="code" class="form-control" placeholder="Scan or enter serial number" value="{{ old('code') ?? $item_serial_number->code }}"><br>
                <label>Delivery Receipt Number</label><br>
                <input type="text" name="delivery_receipt_number" class="form-control" placeholder="Enter delivery receipt number" value="{{ old('delivery_receipt_number') ?? $item_serial_number->delivery_receipt->delivery_receipt_number ?? null }}" required><br>
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