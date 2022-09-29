@include('layouts.auth.header')
@php
use App\Models\SurveyStatus;
use App\Models\SurveyCategory;
@endphp

<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Users</li>
                </ol>
            </nav>
            <h1 class="m-0">Users</h1>
        </div>
        <a href="{{ route('surveys.add') }}" class="btn btn-primary"><i class="material-icons">add</i> Add</a>
    </div>
</div>

<div class="container-fluid page__container">
    @include('layouts.partials.alerts')
    @include('layouts.partials.top-tabs')

    <form action="{{ route('surveys.search') }}" method="post">
        {{ csrf_field() }}
        <div class="card card-form d-flex flex-column flex-sm-row">
            <div class="card-form__body card-body-form-group flex">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>Name</label>
                            <input name="name" type="text" class="form-control" placeholder="Search by name" value="{{ old('name') }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Category</label>
                            <select name="category_id" class="form-control" data-toggle="select">
                                @if (old('category_id'))
                                    @if (old('category_id') != '*')
                                        <option value="{{ old('category_id') }}">{{ SurveyCategory::find(old('category_id'))->name }}</option>
                                    @endif
                                @endif
                                <option value="*">All</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" data-toggle="select">
                                @if (old('status'))
                                    @if (old('status') != '*')
                                        @if (old('status') == SurveyStatus::ACTIVE)
                                            <option value="{{ old('status') }}">Active</option>
                                        @endif

                                        @if (old('status') == SurveyStatus::PENDING)
                                            <option value="{{ old('status') }}">Pending</option>
                                        @endif

                                        @if (old('status') == SurveyStatus::INACTIVE)
                                            <option value="{{ old('status') }}">Inactive</option>
                                        @endif
                                    @endif
                                @endif
                                <option value="*">All</option>
                                <option value="2">Active</option>
                                <option value="1">Pending</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>From</label>
                            <input name="from_date" type="date" class="form-control" data-toggle="flatpickr" max="{{ date('Y-m-d') }}" value="{{ old('from_date') }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>To</label>
                            <input name="to_date" type="date" class="form-control" data-toggle="flatpickr" max="{{ date('Y-m-d') }}" value="{{ old('to_date') }}">
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn bg-white border-left border-top border-top-sm-0 rounded-top-0 rounded-top-sm rounded-left-sm-0"><i class="material-icons text-primary icon-20pt">search</i></button>
        </div>
    </form>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h4 class="card-header__title flex m-0">Surveys</h4>
                    <div data-toggle="flatpickr" data-flatpickr-wrap="true" data-flatpickr-static="true" data-flatpickr-mode="range" data-flatpickr-alt-format="d/m/Y" data-flatpickr-date-format="d/m/Y">
                        
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table">#ID</th>
                                <th id="compact-table">Name</th>
                                <th id="compact-table">Category</th>
                                <th id="compact-table">Privacy</th>
                                <th id="compact-table">Views</th>
                                <th id="compact-table">Status</th>
                                <th id="compact-table">Created At</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                            @foreach($surveys as $survey)
                            @php
                                $category = SurveyCategory::find($survey->category_id)->first()->name;
                            @endphp 
                                <tr>
                                    <td><div class="badge badge-light">#{{ $survey->id }}</div></td>
                                    <td id="compact-table">
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex align-items-center">
                                                <a href="{{ route('surveys.view', [$survey->id]) }}" style="text-decoration: none; color: #333;"><b>
                                                @if ($survey->avatar)
                                                    <img src="{{ url($survey->avatar) }}" width="30px">
                                                    {{ $survey->name }}
                                                @else
                                                    <img src="{{ url(env('APP_ICON')) }}" width="30px" style="margin-right: 7px;">
                                                    {{ $survey->name }}
                                                @endif
                                                </b></a>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <a href="{{ route('surveys.view', [$survey->id]) }}" style="margin-right: 7px">View</a> | 
                                            <a href="{{ route('surveys.edit', [$survey->id]) }}" id="space-table">Edit</a> | 
                                            <a href="{{ route('surveys.manage', [$survey->id]) }}" id="space-table">Manage</a> | 
                                            <a href="#" id="space-table" data-toggle="modal" data-target="#quick-edit-{{ $survey->id }}">Quick Edit</a> | 
                                            @if ($survey->status == SurveyStatus::ACTIVE || $survey->status == SurveyStatus::PENDING)
                                                <a href="#" data-href="{{ route('surveys.delete', [$survey->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Delete</a>
                                            @endif

                                            @if ($survey->status == SurveyStatus::INACTIVE)
                                                <a href="#" data-href="{{ route('surveys.recover', [$survey->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Recover</a>
                                            @endif
                                        </div>
                                    </td>
                                    <td id="compact-table">{{ $category }}</td>
                                    <td id="compact-table">
                                        @if ($survey->privacy == 'public')
                                            <i class="material-icons icon-16pt mr-1 text-muted">visibility_on</i> 
                                        @elseif ($survey->privacy == 'private')
                                            <i class="material-icons icon-16pt mr-1 text-muted">lock</i> 
                                        @elseif ($survey->privacy == 'shared')
                                            <i class="material-icons icon-16pt mr-1 text-muted">shared</i> 
                                        @endif
                                        {{ $survey->privacy }}
                                    </td>
                                    <td id="compact-table">{{ $survey->views }}</td>
                                    <td>
                                        @if ($survey->status == SurveyStatus::ACTIVE)
                                            <div class="badge badge-success ml-2">Active</div>
                                        @elseif ($survey->status == SurveyStatus::PENDING)
                                            <div class="badge badge-warning ml-2">Pending</div>
                                        @elseif ($survey->status == SurveyStatus::INACTIVE)
                                            <div class="badge badge-danger ml-2">Inactive</div>
                                        @endif
                                    </td>
                                    <td id="compact-table"><i class="material-icons icon-16pt text-muted mr-1">today</i> {{ $survey->created_at->format('M d Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table> 

                    @if (count($surveys) <= 0)
                        <div style="padding: 20px">
                            <center><i class="material-icons icon-16pt mr-1 text-muted">assignment</i> No record/s found</center>
                        </div>
                    @endif
                </div>
            </div>
            {{ $surveys->links() }}
        </div>
    </div>
</div>
@include('layouts.auth.footer')