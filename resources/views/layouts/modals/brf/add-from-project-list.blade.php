@php
  use App\Models\Project;
  use App\Models\ProjectStatus;
  use App\Models\User;
  use App\Models\UserStatus;

  $users = User::where('status', '!=', UserStatus::INACTIVE)
                  ->orderBy('created_at', 'desc')
                  ->get();
@endphp

<form action="{{ route('internals.brf.create') }}" method="post">
  {{ csrf_field() }}
  <div class="modal fade" id="add-brf" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">Add BRF</h5><br>

              <label>Reference #</label><br>
              <input type="text" name="reference_number" class="form-control">
              <br>

              <label>Payment To</label><br>
              <select class="form-control" name="payment_for_user_id">
                <option value=""></option>
                @foreach ($users as $user)
                  <option value="{{ $user->id }}">{{ $user->firstname }} {{ $user->lastname }}</option>
                @endforeach
              </select>
              <br>

              <label>In Payment For</label><br>
              <input type="text" name="name" class="form-control" value="{{ old('name') }}">
              <br>

              <label>Needed Date</label><br>
              <input type="date" name="needed_date" class="form-control" value="{{ old('needed_date') }}">
              <br>
              
              <label>Remarks</label><br>
              <textarea name="remarks" class="form-control" placeholder="Remarks">{{ old('remarks') }}</textarea><br>

          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>
</form>