@foreach ($payment_credits as $payment_credit)
<form action="{{ route('accounting.payments.assign.bir-number') }}" method="post">
  {{ csrf_field() }}
  <input type="hidden" name="so_number" value="{{ $payment_credit->payment->so_number }}">
  <div class="modal fade" id="assign-bir-number-{{ $payment_credit->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">Assign OR Number</h5><br>

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
                  <strong>{{ $payment_credit->payment->so_number }}</strong>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <strong>Customer:</strong>
                </div>
                <div class="col">
                  <strong>{{ $payment_credit->payment->user->firstname }} {{ $payment_credit->payment->user->lastname }}</strong>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  Cashier:
                </div>
                <div class="col">
                  {{ $payment_credit->payment->authorized_user->firstname }} {{ $payment_credit->payment->authorized_user->lastname }}
                </div>
              </div>
              <hr>
              <label>OR Number</label><br>
              <input type="text" name="bir_number" class="form-control" placeholder="OR Number" value="{{ old('bir_number') ?? $payment_credit->payment->bir_number }}"><br>
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