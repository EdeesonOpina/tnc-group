@foreach($users as $user)
<form action="{{ route('admin.users.set-password') }}" method="post" enctype="multipart/form-data">
  {{ csrf_field() }}
  <input type="hidden" name="user_id" value="{{ $user->id }}">
  <div class="modal fade" id="set-password-{{ $user->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">Set Password</h5><br>

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
                  <h6>Name:</h6>
                </div>
                <div class="col">
                  <h6>{{ $user->firstname }} {{ $user->lastname }}</h6>
                </div>
              </div>

              <div class="row">
                <div class="col">
                  Email:
                </div>
                <div class="col">
                  {{ $user->email }}
                </div>
              </div>

              <div class="row">
                <div class="col">
                  Role:
                </div>
                <div class="col">
                  {{ $user->role }}
                </div>
              </div>
              <hr>
              <label>New Password</label><br>
              <input type="password" name="password" class="form-control" placeholder="New Password"><br>
              <label>Confirm Password</label><br>
              <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password">
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