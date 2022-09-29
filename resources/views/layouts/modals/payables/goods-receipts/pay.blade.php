@php
    use App\Models\Order;
    use App\Models\Payable;
    use App\Models\OrderStatus;
    use App\Models\PayableStatus;
@endphp

@foreach($goods_receipts as $goods_receipt)
  @php
      $orders_total = Order::where('goods_receipt_id', $goods_receipt->id)
                          ->where('status', OrderStatus::ACTIVE)
                          ->sum('total');
      $paid_total = Payable::where('goods_receipt_id', $goods_receipt->id)
                          ->where('status', PayableStatus::PAID)
                          ->sum('price');
      $remaining_balance = $orders_total - $paid_total;
  @endphp
  <form action="{{ route('accounting.payables.create') }}" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
    <input type="hidden" name="purchase_order_id" value="{{ $goods_receipt->purchase_order->id }}">
    <input type="hidden" name="goods_receipt_id" value="{{ $goods_receipt->id }}">
    <div class="modal fade" id="payables-goods-receipt-{{ $goods_receipt->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    <strong>GRPO #:</strong>
                  </div>
                  <div class="col">
                    <strong>{{ $goods_receipt->reference_number }}</strong>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <strong>PO #:</strong>
                  </div>
                  <div class="col">
                    <strong>{{ $goods_receipt->purchase_order->reference_number }}</strong>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <strong>Supplier:</strong>
                  </div>
                  <div class="col">
                    <strong>{{ $goods_receipt->purchase_order->supplier->name }}</strong>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col">
                    Grand Total:
                  </div>
                  <div class="col">
                    P{{ number_format($orders_total, 2) }}
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    Remaining Total:
                  </div>
                  <div class="col">
                    P{{ number_format($remaining_balance, 2) }}
                  </div>
                </div>
                <hr>
                <label>Mode of Payment</label><br>
                <select class="form-control" name="mop">
                  <option value="cash">Cash</option>
                  <option value="credit-card">Credit Card</option>
                  <option value="cheque">Cheque</option>
                  <option value="bank-deposit">Bank Deposit</option>
                </select>
                <br>
                <label>Date Issued</label><br>
                <input type="date" name="date_issued" class="form-control" value="{{ old('date_issued') }}"><br>
                <label>Date Released</label><br>
                <input type="date" name="date_released" class="form-control" value="{{ old('date_released') }}"><br>
                <label>Date Due</label><br>
                <input type="date" name="due_date" class="form-control" value="{{ old('due_date') }}"><br>
                <label>Check #</label><br>
                <input type="text" name="check_number" class="form-control" placeholder="Check #" value="{{ old('check_number') }}"><br>
                <label>Price</label><br>
                <input type="text" name="price" class="form-control" placeholder="Price" value="{{ old('price') ?? '0.00' }}"><br>
                <label>Proof of Payment</label>
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