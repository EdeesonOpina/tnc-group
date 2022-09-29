@foreach ($payments as $payment)
  <form action="{{ route('internals.rma.update.qty') }}" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="payment_receipt_id" value="{{ $payment_receipt->id }}">
    <input type="hidden" name="payment_id" value="{{ $payment->id }}">
    <div class="modal fade" id="edit-qty-{{ $payment->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <h5 class="modal-title" id="exampleModalLabel">Edit Qty</h5><br>

            <div class="row">
              <div class="col-md-4">
                  @if ($payment->inventory->item->image)
                      <img src="{{ url($payment->inventory->item->image) }}" width="100%">
                  @else
                      <img src="{{ url(env('APP_ICON')) }}" width="100%" style="padding: 5%;">
                  @endif
              </div>
              <div class="col">
                <!-- START DISPLAY INFO -->
                <div class="row">
                  <div class="col">
                    <h6>Name:</h6>
                  </div>
                  <div class="col">
                    <h6>{{ $payment->inventory->item->name }}</h6>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    Category:
                  </div>
                  <div class="col">
                    {{ $payment->inventory->item->category->name }}
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    Brand:
                  </div>
                  <div class="col">
                    {{ $payment->inventory->item->brand->name }}
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    In Stock:
                  </div>
                  <div class="col">
                    {{ $payment->inventory->qty }}
                  </div>
                </div>
                <!-- END DISPLAY INFO -->
                <hr>
                <label>Qty</label><br>
                <input type="number" name="qty" class="form-control" placeholder="Qty" value="{{ $payment->qty }}" min="0">
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