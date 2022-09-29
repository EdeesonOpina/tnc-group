@foreach($other_steps as $other_step)
<form action="{{ route('surveys.steps.update', [$survey->id]) }}" method="post">
  {{ csrf_field() }}
  <input type="hidden" name="survey_id" value="{{ $survey->id }}">
  <input type="hidden" name="step_id" value="{{ $other_step->id }}">
  <div class="modal fade" id="edit-step-{{ $other_step->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h5 class="modal-title" id="exampleModalLabel">Edit Step</h5><br>
          <label>Your Step Name</label><br>
          <input type="text" name="name" class="form-control" placeholder="Your Step Name" value="{{ old('name') ?? $other_step->name }}"><br>
          <label>Order</label><br>
          <input type="number" name="order" class="form-control" placeholder="Order" value="{{ old('order') ?? $other_step->order }}" min="1"><br>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>
</form>
@endforeach