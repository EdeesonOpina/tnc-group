<form action="{{ route('internals.inventories.items.serial-numbers.create', [$item->id]) }}" method="post">
  {{ csrf_field() }}
  <input type="hidden" name="item_id" value="{{ $item->id }}">
  <div class="modal fade" id="add-serial-number" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">Add Item Serial Number</h5><br>

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
              <label>Delivery Receipt Number</label><br>
              <input type="text" name="delivery_receipt_number" class="form-control" placeholder="Enter delivery receipt number" value="{{ old('delivery_receipt_number') }}" required><hr>
              <label>Serial Number</label><br>
              <input type="text" name="code[]" class="form-control" placeholder="Scan or enter serial number" value=""><br>

              <div id="serial-div"></div>

              <a href="#" id="add-more" style="text-decoration: none" onclick="return false;">+ Add More</a>
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

<script type="text/javascript">
  $(function () {

    $("#add-more").click(function () {
      $("#serial-div").append("<input type='text' name='code[]' class='form-control' placeholder='Scan or enter serial number'  required><br>");
    });
  })
</script>