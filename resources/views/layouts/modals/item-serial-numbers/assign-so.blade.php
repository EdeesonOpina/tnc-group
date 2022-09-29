@foreach($item_serial_numbers as $item_serial_number)
  <form action="{{ route('internals.inventories.items.serial-numbers.assign-so', [$item_serial_number->item->id]) }}" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="item_serial_number_id" value="{{ $item_serial_number->id }}">
    <div class="modal fade" id="assign-so-{{ $item_serial_number->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <h5 class="modal-title" id="exampleModalLabel">Assign SO #</h5><br>

            <div class="row">
              <div class="col-md-4">
                  @if ($item_serial_number->item->image)
                      <img src="{{ url($item_serial_number->item->image) }}" width="100%">
                  @else
                      <img src="{{ url(env('APP_ICON')) }}" width="100%" style="padding: 5%;">
                  @endif
              </div>
              <div class="col">
                <div class="row">
                  <div class="col">
                    <h6>Barcode:</h6>
                  </div>
                  <div class="col">
                    <h6>{{ $item_serial_number->item->barcode }}</h6>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <h6>Name:</h6>
                  </div>
                  <div class="col">
                    <h6>{{ $item_serial_number->item->name }}</h6>
                  </div>
                </div>
                <hr>
                <label>Enter SO #</label><br>
                <input type="text" name="so_number" class="form-control" placeholder="Enter SO # here" value="{{ old('so_number') ?? $item_serial_number->so_number }}"><br>
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