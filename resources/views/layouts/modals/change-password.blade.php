<form action="{{ route('auth.profile.change-password') }}" method="post">
  {{ csrf_field() }}
  <div class="modal fade" id="change-password" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">Change Password</h5><br>

          <label>New Password</label><br>
          <input type="password" name="password" class="form-control" placeholder="New Password"><br>
          <label>Confirm Password</label><br>
          <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password">
          <br>

          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>
</form>