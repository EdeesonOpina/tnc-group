@php
  use App\Models\SurveyQuestionType;

  $question_types = SurveyQuestionType::where('status', 1)
                                  ->get();
@endphp

<form action="{{ route('surveys.questions.create', [$survey->id]) }}" method="post">
  {{ csrf_field() }}
  <input type="hidden" name="survey_id" value="{{ $survey->id }}">
  <input type="hidden" name="step_id" value="{{ $step->id }}">
<div class="modal fade" id="add-question" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <h5 class="modal-title" id="exampleModalLabel">Add Question</h5><br>
        <label>Your Question</label><br>
        <input type="text" name="description" class="form-control" placeholder="Your Question" value="{{ old('description') }}"><br>

        <label>Type</label><br>
        <select name="type_id" class="form-control" data-toggle="select" onchange="showHiddenOptions('hidden-options', this)">
          @foreach ($question_types as $question_type)
            <option value="{{ $question_type->id }}">{{ $question_type->name }}</option>
          @endforeach
        </select>
        <br>

        <div id="hidden-options">
          <label>Choices</label><br>
          <div class="row" id="bottom-spaced-field">
            <div class="col-md-10">
              <input type="text" name="choices[]" class="form-control" placeholder="Enter choice here..." value="{{ old('choices[]') }}">
            </div>
            <div class="col">
              <i class="material-icons icon-16pt mr-1 text-danger" data-toggle="tooltip" data-placement="top" title="Delete" onclick="remove(this)">delete</i>
            </div>
          </div>
          
          <div class="row" id="bottom-spaced-field">
            <div class="col-md-10">
              <input type="text" name="choices[]" class="form-control" placeholder="Enter choice here..." value="{{ old('choices[]') }}">
            </div>
            <div class="col">
              <i class="material-icons icon-16pt mr-1 text-danger" data-toggle="tooltip" data-placement="top" title="Delete" onclick="remove(this)">delete</i>
            </div>
          </div>
          
          <div class="row" id="bottom-spaced-field">
            <div class="col-md-10">
              <input type="text" name="choices[]" class="form-control" placeholder="Enter choice here..." value="{{ old('choices[]') }}">
            </div>
            <div class="col">
              <i class="material-icons icon-16pt mr-1 text-danger" data-toggle="tooltip" data-placement="top" title="Delete" onclick="remove(this)">delete</i>
            </div>
          </div>
          
          <div id="choice-field"></div>
          <button type="button" class="btn btn-primary" id="add-choice-field">Add Choice</button>
          <br><br>
        </div>

        <label>Placeholder</label><br>
        <input type="text" name="placeholder" class="form-control" placeholder="Placeholder" value="{{ old('placeholder') }}"><br>

        <label>Note</label><br>
        <input type="text" name="note" class="form-control" placeholder="Note" value="{{ old('note') }}"><br>

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