@foreach ($payments as $payment)
<div class="modal fade" id="return-{{ $payment->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <h5 class="modal-title" id="exampleModalLabel">RMA - Return</h5><br>

        <div class="row">
          <div class="col-md-4">
            @if ($payment->item->image)
              <img src="{{ url($payment->item->image) }}" width="100%" style="padding: 5%;">
            @else
              <img src="{{ url(env('APP_ICON')) }}" width="100%" style="padding: 5%;">
            @endif
          </div>
          <div class="col">
            <div class="row">
              <div class="col">
                <strong>ID:</strong>
              </div>
              <div class="col">
                <strong>{{ $payment->item->id }}</strong>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <strong>Item:</strong>
              </div>
              <div class="col">
                <strong>{{ $payment->item->name }}</strong>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <strong>Category:</strong>
              </div>
              <div class="col">
                {{ $payment->item->category->name }}
              </div>
            </div>
            <div class="row">
              <div class="col">
                <strong>Brand:</strong>
              </div>
              <div class="col">
                {{ $payment->item->brand->name }}
              </div>
            </div>
            <div class="row">
              <div class="col">
                <strong>Price:</strong>
              </div>
              <div class="col">
                P{{ number_format($payment->price, 2) }}
              </div>
            </div>
            <hr>
            <form action="{{ route('internals.rma.single.return', $payment_receipt->so_number) }}" method="post">
              {{ csrf_field() }}
              <input type="hidden" name="payment_receipt_id" value="{{ $payment_receipt->id }}">
              <input type="hidden" name="payment_id" value="{{ $payment->id }}">
              <label>Return Type</label><br>
              <select name="type" class="form-control">
                <option value="">Select Return Type</option>
                <option value="advanced-replacement">Advanced Replacement</option>
                <option value="for-warranty">For Warranty</option>
              </select>
              <br>
              <label>S/N (optional)</label><br>
              <input type="text" name="serial_number" placeholder="Enter S/N here" class="form-control" value="{{ old('serial_number') }}"><br>

              <label>Remarks</label><br>
              <textarea class="form-control" name="remarks" placeholder="Enter remarks here...">{{ old('remarks') }}</textarea><br>

              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Submit</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endforeach