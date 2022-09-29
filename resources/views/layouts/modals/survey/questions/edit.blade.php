@php
  use App\Models\SurveyQuestionType;

  $question_types = SurveyQuestionType::where('status', 1)
                                  ->get();
@endphp

@foreach($questions as $question)
<form action="{{ route('surveys.questions.update', [$survey->id]) }}" method="post">
  {{ csrf_field() }}
  <input type="hidden" name="survey_id" value="{{ $survey->id }}">
  <input type="hidden" name="step_id" value="{{ $step->id }}">
  <input type="hidden" name="question_id" value="{{ $question->id }}">
<div class="modal fade" id="edit-question-{{ $question->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <h5 class="modal-title" id="exampleModalLabel">Edit Question</h5><br>
        <label>Your Question</label><br>
        <input type="text" name="description" class="form-control" placeholder="Your Question" value="{{ old('description') ?? $question->description }}"><br>

        <label>Note</label><br>
        <select name="type_id" class="form-control" data-toggle="select">
          @foreach ($question_types as $question_type)
            <option value="{{ $question_type->id }}">{{ $question_type->name }}</option>
          @endforeach
        </select>
        <br>

        <label>Placeholder</label><br>
        <input type="text" name="placeholder" class="form-control" placeholder="Placeholder" value="{{ old('placeholder') ?? $question->placeholder }}"><br>

        <label>Note</label><br>
        <input type="text" name="note" class="form-control" placeholder="Note" value="{{ old('note') ?? $question->note }}"><br>

        <label>Order</label><br>
        <input type="number" name="order" class="form-control" placeholder="Order" value="{{ old('order') ?? $question->order }}"><br>

        <label>Is this optional</label><br>
        <select name="is_optional" class="form-control" data-toggle="select">
          <option value="0">No</option>
          <option value="1">Yes</option>
        </select>
        <br>

        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
</form>
@endforeach