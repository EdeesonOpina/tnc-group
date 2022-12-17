@php
  use App\Account;
  use App\AccountStatus;
@endphp

@foreach ($budget_request_forms as $budget_request_form)
  <form action="{{ route('internals.cv.create') }}" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="budget_request_form_id" value="{{ $budget_request_form->id }}">

    <div class="modal fade" id="create-cv-{{ $budget_request_form->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <h5 class="modal-title" id="exampleModalLabel">Create CV</h5><br>

            <div class="row">
              <div class="col-md-4">
                  @if ($budget_request_form->image)
                      <img src="{{ url($budget_request_form->image) }}" width="100%">
                  @else
                      <img src="{{ url(env('APP_ICON')) }}" width="100%" style="padding: 5%;">
                  @endif
              </div>
              <div class="col">
                <div class="row">
                  <div class="col">
                    <h6>BRF #:</h6>
                  </div>
                  <div class="col">
                    {{ $budget_request_form->reference_number }}
                  </div>
                </div>
                <hr>
                <label>Check #</label><br>
                <label>Check #</label><br>
                <input type="text" name="cheque_number" class="form-control" value="{{ old('cheque_number') }}" placeholder="Check #">
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