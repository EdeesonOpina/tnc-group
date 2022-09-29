@include('layouts.auth.header')
<form action="{{ route('surveys.create') }}" method="post" id="form" method="multipart/form-data">
{{ csrf_field() }}

<div class="container page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('surveys') }}"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item">Surveys</li>
                    <li class="breadcrumb-item active" aria-current="page">Add Survey</li>
                </ol>
            </nav>
            <h1 class="m-0">Add Survey</h1>
        </div>
        <button type="submit" class="btn btn-success">Submit</button>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')

    <div class="row">
        <div class="col-md-8">
            <div id="spaced-card" class="card card-body">
                <div class="row">
                    <div class="col">
                        <h3>Survey</h3>
                        <br>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>URL Slug</label>
                                    <input type="text" class="form-control" placeholder="" disabled>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="Name" value="{{ old('name') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="survey_category">Category</label><br />
                                    <select id="survey_category" name="category_id" class="custom-select" data-toggle="select">
                                        @foreach($survey_categories as $survey_category)
                                            <option value="{{ $survey_category->id }}">{{ $survey_category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="survey_sub_category">Sub Category</label><br />
                                    <select id="survey_sub_category" class="custom-select" data-toggle="select">
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">About this survey</label>
                            <textarea id="tiny" name="description" placeholder="Enter your description here">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div id="semi-spaced-card" class="card card-body">
                <h3>Privacy Options</h3>
                <br>
                <div class="form-group">
                    <input type="radio" name="privacy" value="public" id="spaced-radio" checked>Public
                </div>
                <div class="form-group">
                    <input type="radio" name="privacy" value="shared" id="spaced-radio">Only to shared people
                </div>
                <div class="form-group">
                    <input type="radio" name="privacy" value="private" id="spaced-radio">Private
                </div>
                <div class="form-group">
                    <label for="description">Password</label>
                    <input type="text" name="password" class="form-control" placeholder="Enter survey password">
                </div>
            </div>
            <br>
            <div id="semi-spaced-card" class="card card-body">
                <h3>Survey Image</h3>
                <br>
                <div class="form-group">
                    <input type="file" name="image">
                </div>
            </div>
        </div>
    </div>
</div>

</form>
@include('layouts.auth.footer')