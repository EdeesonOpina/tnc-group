<form action="{{ route('surveys.steps.create', [$survey->id]) }}" method="post">
  {{ csrf_field() }}
  <input type="hidden" name="survey_id" value="{{ $survey->id }}">
<div class="modal fade" id="add-step" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <h5 class="modal-title" id="exampleModalLabel">Add Step</h5><br>
        <label>Your Step Name</label><br>
        <input type="text" name="name" class="form-control" placeholder="Your Step Name" value="{{ old('name') }}"><br>

        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
</form>