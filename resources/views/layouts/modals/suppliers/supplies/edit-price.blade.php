@php
  use App\Models\Supply;
@endphp

@foreach ($items as $item)
  @php
      $supply = Supply::where('supplier_id', $supplier->id)
                  ->where('item_id', $item->id)
                  ->first();
  @endphp
  @if ($supply)
    <form action="{{ route('admin.supplies.update.price', [$supplier->id]) }}" method="post">
      {{ csrf_field() }}
      <input type="hidden" name="supply_id" value="{{ $supply->id }}">
      <div class="modal fade" id="edit-supply-price-{{ $supply->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-body">
              <h5 class="modal-title" id="exampleModalLabel">Add Item To List</h5><br>

              <div class="row">
                <div class="col-md-4">
                    @if ($item->image)
                        <img src="{{ url($item->image) }}" width="100%">
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
                      <h6>{{ $item->name }}</h6>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      Category:
                    </div>
                    <div class="col">
                      {{ $item->category_name }}
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      Brand:
                    </div>
                    <div class="col">
                      {{ $item->brand_name }}
                    </div>
                  </div>
                  <!-- END DISPLAY INFO -->
                  <hr>
                  <label>Price</label><br>
                  <input type="text" name="price" class="form-control" placeholder="Price" value="{{ old('price') ?? $supply->price }}">
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
  @endif
@endforeach