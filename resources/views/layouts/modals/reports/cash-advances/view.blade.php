@foreach ($users as $user)
<form action="{{ route('admin.reports.cash-advances.view.filter') }}" method="post">
  {{ csrf_field() }}
  <input type="hidden" name="user_id" value="{{ $user->id }}">
  <div class="modal fade" id="view-user-{{ $user->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">View Employee Report</h5><br>

          <div class="row">
            <div class="col-md-4">
                @if ($user->avatar)
                    <img src="{{ url($user->avatar) }}" width="100%">
                @else
                    <img src="{{ url(env('APP_ICON')) }}" width="100%" style="padding: 5%;">
                @endif
            </div>
            <div class="col">
              <div class="row">
                <div class="col-md-4">
                  <strong>Name:</strong>
                </div>
                <div class="col">
                  <strong>{{ $user->firstname }} {{ $user->lastname }}</strong>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-4">
                  <strong>Email:</strong>
                </div>
                <div class="col">
                  {{ $user->email }}
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-4">
                  Address:
                </div>
                <div class="col">
                  {{ $user->line_address_1 }} {{ $user->line_address_2 }}
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-4">
                  Mobile:
                </div>
                <div class="col">
                  {{ $user->mobile }}
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-4">
                  Phone:
                </div>
                <div class="col">
                  {{ $user->phone }}
                </div>
              </div>
              <hr>
              <label>From Date</label><br>
              <input name="customer_from_date" type="date" class="form-control" data-toggle="flatpickr" max="{{ date('Y-m-d') }}" value="{{ old('customer_from_date') }}">
              <br>
              <label>To Date</label><br>
              <input name="customer_to_date" type="date" class="form-control" data-toggle="flatpickr" max="{{ date('Y-m-d') }}" value="{{ old('customer_to_date') }}">

            </div>
          </div>
          
          <br>

          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">View</button>
        </div>
      </div>
    </div>
  </div>
</form>
@endforeach