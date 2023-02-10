<form action="{{ route('hr.users.update.salary') }}" method="post">
  {{ csrf_field() }}
  <input type="hidden" name="user_id" value="{{ $user->id }}">
  <div class="modal fade" id="salary-{{ $user->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">Salary</h5><br>

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
                <div class="col">
                  <strong>Name:</strong>
                </div>
                <div class="col">
                  {{ $user->firstname }} {{ $user->lastname }}
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <strong>Position:</strong>
                </div>
                <div class="col">
                  {{ $user->position }}
                </div>
              </div>
              <hr>
              <label>Salary</label><br>
              <input type="text" name="salary" class="form-control" placeholder="Salary" value="{{ old('salary') ?? $user->salary ?? '0.00' }}"><br>
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