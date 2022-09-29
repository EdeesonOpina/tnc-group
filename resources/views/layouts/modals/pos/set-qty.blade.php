@foreach ($carts as $cart)
<form action="{{ route('pos.standard.set-qty') }}" method="post">
  {{ csrf_field() }}
  <input type="hidden" name="cart_id" value="{{ $cart->id }}">
  <div class="modal fade" id="set-qty-{{ $cart->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">Set Qty</h5><br>

          <div class="row">
            <div class="col-md-4">
                @if ($cart->inventory->item->image)
                    <img src="{{ url($cart->inventory->item->image) }}" width="100%">
                @else
                    <img src="{{ url(env('APP_ICON')) }}" width="100%" style="padding: 5%;">
                @endif
            </div>
            <div class="col">
              <div class="row">
                <div class="col">
                  {!! QrCode::size(50)->generate(url(route('qr.items.view', [$cart->inventory->item->id]))) !!}
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
                  <strong>{{ $cart->inventory->item->barcode }}</strong>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <strong>Name:</strong>
                </div>
                <div class="col">
                  <strong>{{ $cart->inventory->item->name }}</strong>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  Category:
                </div>
                <div class="col">
                  {{ $cart->inventory->item->category->name }}
                </div>
              </div>
              <div class="row">
                <div class="col">
                  Brand:
                </div>
                <div class="col">
                  {{ $cart->inventory->item->brand->name }}
                </div>
              </div>
              <div class="row">
                <div class="col">
                  Qty:
                </div>
                <div class="col">
                  {{ $cart->qty }}
                </div>
              </div>
              <div class="row">
                <div class="col">
                  Price:
                </div>
                <div class="col">
                  P{{ number_format($cart->price, 2) }}
                </div>
              </div>
              <hr>
              <label>Qty</label><br>
              <input type="text" name="qty" class="form-control" placeholder="Qty" value="{{ $cart->qty ?? $qty }}"><br>

            </div>
          </div>
          
          <br>

          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="submitButton" onclick="submitForm(this);">Save changes</button>
        </div>
      </div>
    </div>
  </div>
</form>
@endforeach