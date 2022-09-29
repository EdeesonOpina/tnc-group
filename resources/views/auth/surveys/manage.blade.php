@include('layouts.auth.header')
@php
    use App\Models\SurveyQuestionType;
    use App\Models\SurveyQuestionChoice;
@endphp

<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Surveys</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $survey->name }}</li>
                </ol>
            </nav>
            <h1 class="m-0">{{ $survey->name }}</h1>
        </div>
        <a href="{{ route('surveys.edit', [$survey->id]) }}" class="btn btn-success ml-3"><i class="material-icons icon-16pt text-white mr-1">edit</i> Edit</a>
        <a href="{{ route('surveys.manage', [$survey->id]) }}" class="btn btn-success ml-3"><i class="material-icons icon-16pt text-white mr-1">settings</i> Manage</a>
    </div>
</div>

<div class="container page__container">

    <div class="row">
        <div class="col-md-8 offset-md-2">
            @include('layouts.partials.alerts')

            <div class="row">
                @foreach ($other_steps as $other_step)
                    @if ($step->id == $other_step->id)
                        <div class="col-md-2 text-center">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <strong>{{ $other_step->order }}</strong>
                                </div>
                            </div>
                            <strong>
                                {{ $other_step->name }}
                                <br>
                                <a href="#" data-toggle="modal" data-target="#edit-step-{{ $other_step->id }}"><i class="material-icons icon-16pt mr-1 text-success" data-toggle="tooltip" data-placement="top" title="Edit">edit</i></a> 
                                <a href="#" data-href="{{ route('surveys.steps.delete', [$survey->id, $step->id]) }}" data-toggle="modal" data-target="#confirm-action">
                                    <i class="material-icons icon-16pt mr-1 text-danger" data-toggle="tooltip" data-placement="top" title="Delete">delete</i>
                                </a>
                            </strong>
                        </div>
                    @else 
                        <div class="col-md-2 text-center">
                            <div class="card">
                                <div class="card-body">
                                    <a href="{{ route('surveys.steps.manage', [$survey->id, $other_step->id]) }}" class="text-secondary" style="text-decoration: none;">
                                    <strong>{{ $other_step->order }}</strong>
                                    </a>
                                </div>
                            </div>
                            <strong>
                                {{ $other_step->name }} 
                                <br>
                                <a href="#" data-toggle="modal" data-target="#edit-step-{{ $other_step->id }}"><i class="material-icons icon-16pt mr-1 text-success" data-toggle="tooltip" data-placement="top" title="Edit">edit</i></a> 
                                <a href="#" data-href="{{ route('surveys.steps.delete', [$survey->id, $step->id]) }}" data-toggle="modal" data-target="#confirm-action">
                                    <i class="material-icons icon-16pt mr-1 text-danger" data-toggle="tooltip" data-placement="top" title="Delete">delete</i>
                                </a>
                            </strong>
                        </div>
                    @endif
                @endforeach

                <div class="col-md-2 text-center">
                    <a href="#" style="text-decoration: none" data-toggle="modal" data-target="#add-step">
                    <div class="card">
                        <div class="card-body">
                            <strong><i class="material-icons icon-16pt mr-1 text-muted">add</i></strong>
                        </div>
                    </div>
                    <strong>Add Step</strong>
                    </a>
                </div>
            </div>
            <br>
            <div class="card">
                <div class="card-body">
                    <div id="spaced">
                        <div class="row">
                            <div class="col">
                                <div class="badge badge-success">ACTIVE</div>
                            </div>
                            <div class="col-md-8">
                                &nbsp;
                            </div>
                            <div class="col">
                                <a href="#" data-href="{{ route('surveys.questions.clear', [$survey->id, $step->id]) }}" data-toggle="modal" data-target="#confirm-action">
                                    <button type="button" class="btn btn-sm btn-danger form-control"><i class="material-icons icon-16pt mr-1 text-white">delete</i> Clear All</button>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="px-3">
                        <!-- <div class="d-flex justify-content-center flex-column text-center my-5 navbar-light">
                            <a href="{{ route('surveys') }}" class="navbar-brand d-flex flex-column m-0" style="min-width: 0">
                                @if ($survey->image)
                                    <img class="navbar-brand-icon mb-2" src="{{ url(env('APP_ICON')) }}" width="25" alt="{{ env('APP_NAME') }}">
                                @else
                                    <img class="navbar-brand-icon mb-2" src="{{ url(env('APP_ICON')) }}" width="25" alt="{{ env('APP_NAME') }}">
                                @endif
                                <span>{{ $survey->name }}</span>
                            </a>
                        </div> -->
                        <div class="row mb-5">
                            @if (count($questions) > 0)
                                @foreach($questions as $question)
                                @php
                                    $choices = SurveyQuestionChoice::where('question_id', $question->id)
                                                                ->where('survey_id', $survey->id)
                                                                ->get();
                                @endphp
                                    <div class="col-lg-12">
                                        <div class="text-label">
                                            {{ $question->description }} 
                                            <a href="#" data-toggle="modal" data-target="#edit-question-{{ $question->id }}"><i class="material-icons icon-16pt mr-1 text-success" data-toggle="tooltip" data-placement="top" title="Edit">edit</i></a>
                                            <a href="#" data-href="{{ route('surveys.questions.delete', [$survey->id, $question->id]) }}" data-toggle="modal" data-target="#confirm-action" ><i class="material-icons icon-16pt mr-1 text-danger" data-toggle="tooltip" data-placement="top" title="Delete">delete</i></a>
                                        </div>
                                        @if ($question->type_id == SurveyQuestionType::TEXT)
                                            <input type="text" name="answer[]" class="form-control" placeholder="{{ $question->placeholder }}">
                                        @endif

                                        @if ($question->type_id == SurveyQuestionType::EMAIL)
                                            <input type="email" name="answer[]" class="form-control" placeholder="{{ $question->placeholder }}">
                                        @endif

                                        @if ($question->type_id == SurveyQuestionType::TEXTAREA)
                                            <textarea name="answer[]" class="form-control" placeholder="{{ $question->placeholder }}"></textarea>
                                        @endif

                                        @if ($question->type_id == SurveyQuestionType::NUMBER)
                                            <input type="number" name="answer[]" class="form-control" placeholder="{{ $question->placeholder }}">
                                        @endif

                                        @if ($question->type_id == SurveyQuestionType::DATE)
                                            <input type="date" name="answer[]" class="form-control" data-toggle="flatpickr">
                                        @endif

                                        @if ($question->type_id == SurveyQuestionType::CHECKBOX || $question->type_id == SurveyQuestionType::RADIO)
                                            @if ($choices)
                                                @foreach ($choices as $choice)
                                                    @if ($question->type_id == SurveyQuestionType::CHECKBOX)
                                                        <input type="checkbox" name="answer[][{{ $question->id }}]" value="{{ $choice->name }}" id="spaced-radio">{{ $choice->name }}<br>
                                                    @endif

                                                    @if ($question->type_id == SurveyQuestionType::RADIO)
                                                        <div class="form-group">
                                                            <input type="radio" name="answer[][{{ $question->id }}]" value="{{ $choice->name }}" id="spaced-radio">{{ $choice->name }}<br>
                                                        </div>
                                                    @endif 
                                                @endforeach
                                            @endif
                                        @endif

                                        @if ($question->type_id == SurveyQuestionType::DROPDOWN)
                                            <select  class="custom-select" data-toggle="select">
                                                <option value="">{{ $question->placeholder }}</option>
                                                @if ($choices)
                                                    @foreach ($choices as $choice)
                                                        <option value="{{ $choice->id }}">{{ $choice->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        @endif

                                        @if ($question->note)
                                            <br>
                                            <i>Note: {{ $question->note }}</i>
                                        @endif
                                    </div>

                                    <div class="col-lg-12">
                                        &nbsp;
                                    </div>
                                @endforeach
                            @else
                                <div class="col-lg-12">
                                    No records found
                                </div>

                                <div class="col-lg-12">
                                    &nbsp;
                                </div>
                            @endif
                            
                            <div class="col-lg-12">
                                <div class="card bg-primary" id="spaced">
                                    <a href="#" data-toggle="modal" data-target="#add-question" class="text-white no-underline"><i class="material-icons icon-16pt mr-1 text-white">add</i> Add Question</a>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                @if ($previous_step)
                                    <a href="{{ route('surveys.steps.manage', [$survey->id, $previous_step]) }}" class="text-white no-underline">
                                        <button type="button" class="btn btn-light form-control"><i class="material-icons icon-16pt mr-1 text-muted">arrow_back</i> Back</button>
                                    </a>
                                @endif
                            </div>
                            <div class="col-md-8">
                                &nbsp;
                            </div>
                            <div class="col">
                                @if ($next_step)
                                    <a href="{{ route('surveys.steps.manage', [$survey->id, $next_step]) }}" class="text-white no-underline">
                                        <button type="button" class="btn btn-success form-control">Next <i class="material-icons icon-16pt mr-1 text-white">arrow_forward</i></button>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@include('layouts.auth.footer')