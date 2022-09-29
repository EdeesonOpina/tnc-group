<form action="{{ route('accounting.cash-advances.pay') }}" method="post" enctype="multipart/form-data">
  {{ csrf_field() }}
  <input type="hidden" name="cash_advance_id" value="{{ $cash_advance->id }}">
  <div class="modal fade" id="cash-advance-payment-{{ $cash_advance->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">Pay</h5><br>

          <div class="row">
            <div class="col-md-4">
              <img src="{{ url(env('APP_ICON')) }}" width="100%" style="padding: 5%;">
            </div>
            <div class="col">
              <div class="row">
                <div class="col">
                  <strong>CA #:</strong>
                </div>
                <div class="col">
                  <strong>{{ $cash_advance->reference_number }}</strong>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <strong>Employee:</strong>
                </div>
                <div class="col">
                  <strong>{{ $cash_advance->user->firstname }} {{ $cash_advance->user->lastname }}</strong>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col">
                  Grand Total:
                </div>
                <div class="col">
                  P{{ number_format($cash_advance->price, 2) }}
                </div>
              </div>
              <div class="row">
                <div class="col">
                  Paid Balance:
                </div>
                <div class="col">
                  P{{ number_format($paid_balance, 2) }}
                </div>
              </div>
              <div class="row">
                <div class="col">
                  Remaining Balance:
                </div>
                <div class="col">
                  P{{ number_format(($cash_advance->price - $paid_balance), 2) }}
                </div>
              </div>
              <hr>
              <label>Paid Amount</label><br>
              <input type="text" name="price" class="form-control" placeholder="Paid Amount" value="{{ old('price') ?? ($cash_advance->price - $paid_balance) }}"><br>
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