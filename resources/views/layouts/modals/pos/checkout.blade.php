@php
  use App\Models\Cart;
  use App\Models\CartStatus;

  $carts_total = Cart::where('authorized_user_id', auth()->user()->id)
                    ->where('user_id', $user->id)
                    ->where('status', CartStatus::ACTIVE)
                    ->orderBy('created_at', 'desc')
                    ->sum('total');

  $vat_total = ((12 / 100) * $carts_total) ?? '0.00';
@endphp

<form action="{{ route('pos.checkout') }}" method="post">
  {{ csrf_field() }}
  <input type="hidden" name="user_id" value="{{ $user->id }}">
  <div class="modal fade" id="checkout" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">Checkout</h5><br>

          <div class="row">
            <div class="col-md-4">
              <img src="{{ url(env('APP_ICON')) }}" width="100%" style="padding: 5%;">
            </div>
            <div class="col">
              <!-- START DISPLAY INFO -->
              <div class="row">
                <div class="col">
                  Customer:
                </div>
                <div class="col">
                  {{ $user->firstname }} {{ $user->lastname }}
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <strong>Total:</strong>
                </div>
                <div class="col">
                  <strong>₱{{ number_format($carts_total, 2) }}</strong>
                </div>
              </div>
              <!-- END DISPLAY INFO -->
              <hr>
              <label>Mode of Payment</label><br>
              <select class="form-control" name="mop">
                <option value="cash">Cash</option>
                <!-- <option value="credit">Credit</option>
                <option value="credit-card">Credit Card</option>
                <option value="cheque">Cheque</option>
                <option value="bank-deposit">Bank Deposit</option> -->
              </select>
              <br>
              <label>VAT</label><br>
              <input type="text" name="vat" class="form-control" placeholder="VAT" value="{{ old('vat') ?? $vat_total }}" min="0"><br>
              <label>Discount</label><br>
              <input type="text" name="discount" class="form-control" placeholder="Discount" value="{{ old('discount') ?? '0.00' }}" min="0"><br>
              <label>Note</label><br>
              <select class="form-control" name="note">
                <option value="none">none</option>
                <option value="completion">completion</option>
                <option value="free">free</option>
              </select>
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