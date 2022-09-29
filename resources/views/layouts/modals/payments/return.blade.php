@foreach ($payments as $payment)
<div class="modal fade" id="return-{{ $payment->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <h5 class="modal-title" id="exampleModalLabel">Return</h5><br>

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
            <label>Return Type</label><br>
            <select name="type"class="form-control">
              <!-- <option value="">Select Return Type</option> -->
              <option>Return To Inventory</option>
            </select>
            <br>

            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <a href="#" data-href="{{ route('accounting.payments.single.return', [$payment->id]) }}" class="text-warning" data-toggle="modal" data-target="#confirm-action">
              <button type="button" class="btn btn-primary">Save changes</button>
            </a>
          </div>
        </div>
        <br>

      </div>
    </div>
  </div>
</div>
@endforeach