@foreach ($return_inventory_items as $return_inventory_item)
<form action="{{ route('internals.rma.action-taken') }}" method="post">
  {{ csrf_field() }}
  <input type="hidden" name="return_inventory_item_id" value="{{ $return_inventory_item->id }}">
  <div class="modal fade" id="action-taken-{{ $return_inventory_item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">Action Taken</h5><br>

          <div class="row">
            <div class="col">
              <!-- START DISPLAY INFO -->
              <div class="row">
                <div class="col">
                  <h6>Name:</h6>
                </div>
                <div class="col">
                  <h6>{{ $return_inventory_item->inventory->item->name }}</h6>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  Type:
                </div>
                <div class="col">
                  {{ $return_inventory_item->type }}
                </div>
              </div>
              <div class="row">
                <div class="col">
                  Qty:
                </div>
                <div class="col">
                  {{ $return_inventory_item->qty }}
                </div>
              </div>
              <div class="row">
                <div class="col">
                  Remarks:
                </div>
                <div class="col">
                  {{ $return_inventory_item->remarks }}
                </div>
              </div>
              <!-- END DISPLAY INFO -->
              <hr>
              <label>Action Taken</label><br>
              <textarea type="text" name="action_taken" class="form-control" placeholder="Enter action here">{{ $return_inventory_item->action_taken }}</textarea>
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