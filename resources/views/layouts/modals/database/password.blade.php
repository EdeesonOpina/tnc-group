<form action="{{ route('admin.sql.confirm') }}" method="post">
{{ csrf_field() }}
<div class="modal fade" id="exportSQL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Database</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <i>Note: In order to proceed backing it up please enter your password.</i>
        <br><br>
        <h6>Enter your password</h6>
        <input type="password" name="password" class="form-control">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success">Submit</button>
      </div>
    </div>
  </div>
</div>
</form>