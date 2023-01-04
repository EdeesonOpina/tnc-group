@php
  use App\Models\BudgetRequestFormStatus;
@endphp

@foreach ($budget_request_forms as $budget_request_form)
<form action="{{ route('internals.brf.mark-as-released') }}" method="post" enctype="multipart/form-data">
  {{ csrf_field() }}
  <input type="hidden" name="budget_request_form_id" value="{{ $budget_request_form->id }}">
  <div class="modal fade" id="mark-as-released-{{ $budget_request_form->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">Mark As Released</h5><br>

          <div class="row">
            <div class="col-md-4">
                @if ($budget_request_form->project->image)
                    <img src="{{ url($budget_request_form->project->image) }}" width="100%">
                @else
                    <img src="{{ url(env('APP_ICON')) }}" width="100%" style="padding: 5%;">
                @endif
            </div>
            <div class="col">
              <div class="row">
                <div class="col">
                  <label>Name:</label>
                </div>
                <div class="col">
                  {{ $budget_request_form->project->name }}
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <label>Client:</label>
                </div>
                <div class="col">
                  {{ $budget_request_form->project->client->name }}
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <label>Project Duration:</label>
                </div>
                <div class="col">
                  {{ $budget_request_form->project->end_date }}
                </div>
              </div>
              <hr>

              <label>Payment To</label><br>
              <select class="form-control" name="status">
                <option value=""></option>
                <option value="{{ BudgetRequestFormStatus::FOR_BANK_DEPOSIT_SLIP }}">For Bank Deposit Slip</option>
                <option value="{{ BudgetRequestFormStatus::FOR_LIQUIDATION }}">For Liquidation</option>
                <option value="{{ BudgetRequestFormStatus::FOR_LIQUIDATION_BANK_DEPOSIT_SLIP }}">For Liquidation Bank Deposit Slip</option>
              </select>

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