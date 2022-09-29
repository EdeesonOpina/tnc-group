<form action="{{ route('accounting.payments.update.discount') }}" method="post">
  {{ csrf_field() }}
  <input type="hidden" name="so_number" value="{{ $payment_receipt->so_number }}">
  <div class="modal fade" id="edit-discount-{{ $payment_receipt->so_number }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">Edit Discount</h5><br>

          <div class="row">
            <div class="col-md-4">
              <img src="{{ url(env('APP_ICON')) }}" width="100%" style="padding: 5%;">
            </div>
            <div class="col">
              <div class="row">
                <div class="col">
                  <strong>SO #:</strong>
                </div>
                <div class="col">
                  <strong>{{ $payment_receipt->so_number }}</strong>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <strong>Customer:</strong>
                </div>
                <div class="col">
                  <strong>{{ $payment_receipt->user->firstname }} {{ $payment_receipt->user->lastname }}</strong>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  Cashier:
                </div>
                <div class="col">
                  {{ $payment_receipt->authorized_user->firstname }} {{ $payment_receipt->authorized_user->lastname }}
                </div>
              </div>
              <hr>
              <label>Discount</label><br>
              <input type="text" name="price" class="form-control" placeholder="OR Number" value="{{ old('bir_number') ?? $payments_discount }}"><br>
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