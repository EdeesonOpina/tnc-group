<form action="{{ route('accounting.payments.create.serial-number') }}" method="post">
  {{ csrf_field() }}
  <input type="hidden" name="so_number" value="{{ $payment_receipt->so_number }}">
  <input type="hidden" name="user_id" value="{{ $payment_receipt->user_id }}">
  <div class="modal fade" id="add-serial-number-{{ $payment_receipt->so_number }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">Add Item Serial Number</h5><br>

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
              <label>Item</label><br>
              <select name="payment_id" class="form-control">
                @foreach ($payments as $payment)
                  <option value="{{ $payment->id }}">{{ $payment->item->name }}</option>
                @endforeach
              </select>
              <br>
              <label>S/N Number</label><br>
              <input type="text" name="serial_number" class="form-control" placeholder="S/N Number" value="{{ old('serial_number') }}"><br>
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