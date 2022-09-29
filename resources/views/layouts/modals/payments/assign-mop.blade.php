<form action="{{ route('accounting.payments.assign.mop') }}" method="post">
  {{ csrf_field() }}
  <input type="hidden" name="so_number" value="{{ $payment_receipt->so_number }}">
  <div class="modal fade" id="assign-mop-{{ $payment_receipt->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">Assign Mode of Payment</h5><br>

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
              <label>Mode of Payment</label><br>
              <select class="form-control" name="mop">
                @if ($payment_receipt->mop == 'cash')
                  <option value="{{ $payment_receipt->mop }}">Cash</option>
                @endif

                @if ($payment_receipt->mop == 'credit')
                  <option value="{{ $payment_receipt->mop }}">Credit</option>
                @endif

                @if ($payment_receipt->mop == 'credit-card')
                  <option value="{{ $payment_receipt->mop }}">Credit Card</option>
                @endif

                @if ($payment_receipt->mop == 'cheque')
                  <option value="{{ $payment_receipt->mop }}">Cheque</option>
                @endif

                @if ($payment_receipt->mop == 'bank-deposit')
                  <option value="{{ $payment_receipt->mop }}">Bank Deposit</option>
                @endif
                
                <option value="cash">Cash</option>
                <option value="credit">Credit</option>
                <!-- <option value="credit-card">Credit Card</option>
                <option value="cheque">Cheque</option>
                <option value="bank-deposit">Bank Deposit</option> -->
              </select>
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