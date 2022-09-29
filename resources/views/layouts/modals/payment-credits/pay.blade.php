@php
    use App\Models\POSVat;
    use App\Models\Payment;
    use App\Models\Inventory;
    use App\Models\POSDiscount;
    use App\Models\POSVatStatus;
    use App\Models\PaymentCredit;
    use App\Models\PaymentStatus;
    use App\Models\InventoryStatus;
    use App\Models\POSDiscountStatus;
    use App\Models\PaymentCreditStatus;
@endphp

@foreach($payment_credits as $payment_credit)
  @php
      $payments_count = Payment::where('so_number', $payment_credit->so_number)
                              ->where('status', '!=', PaymentStatus::INACTIVE)
                              ->sum('qty');

      $payments_total = Payment::where('so_number', $payment_credit->so_number)
                              ->where('status', '!=', PaymentStatus::INACTIVE)
                              ->sum('total');

      $payments_discount = POSDiscount::where('so_number', $payment_credit->so_number)
                                  ->where('status', POSDiscountStatus::ACTIVE)
                                  ->first()
                                  ->price ?? 0;

      $payments_vat = POSVat::where('so_number', $payment_credit->so_number)
                                  ->where('status', POSVatStatus::ACTIVE)
                                  ->first()
                                  ->price ?? 0;


      $grand_total = ($payments_total + $payments_vat) - $payments_discount;
  @endphp
  <form action="{{ route('accounting.payment-credits.pay') }}" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
    <input type="hidden" name="so_number" value="{{ $payment_credit->so_number }}">
    <div class="modal fade" id="credit-payment-pay-{{ $payment_credit->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                <div class="row">
                  <div class="col">
                    Grand Total:
                  </div>
                  <div class="col">
                    P{{ number_format($grand_total, 2) }}
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    Paid Balance:
                  </div>
                  <div class="col">
                    P{{ number_format($payment_credit->price, 2) }}
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    Remaining Balance:
                  </div>
                  <div class="col">
                    P{{ number_format(($grand_total - $payment_credit->price), 2) }}
                  </div>
                </div>
                <hr>
                <label>Mode of Payment</label><br>
                <select class="form-control" name="mop">
                  <option value="cash">Cash</option>
                  <option value="partial-payment">Partial Payment</option>
                  <option value="credit">Credit</option>
                  <option value="credit-card">Credit Card</option>
                  <option value="cheque">Cheque</option>
                  <option value="online-deposit">Online Deposit</option>
                </select>
                <br>
                <label>Payment</label><br>
                <input type="text" name="price" class="form-control" placeholder="Payment" value="{{ old('price') ?? ($grand_total - $payment_credit->price) }}"><br>
                <label>OR#</label><br>
                <input type="text" name="bir_number" class="form-control" placeholder="OR Number" value="{{ old('bir_number') }}"><br>
                <label>Proof of Payment</label><br>
                <input type="file" name="image">
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