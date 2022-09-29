@include('layouts.auth.header')

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

<div class="container-fluid page__container">
    <div class="row">
        <div class="col-lg-8">
            <a href="#" class="dp-preview card mb-4">
                @if ($survey->image)
                    <img src="{{ url($survey->image) }}" alt="digital product" class="img-fluid">
                @else
                    <img src="{{ url(env('APP_IMAGE')) }}" alt="digital product" class="img-fluid">
                @endif
                
                <span class="dp-preview__overlay">
                    <span class="btn btn-light">Take Survey</span>
                </span>
            </a>
            <div class="card card-body">
                <h2 class="mb-4"><strong>Description</strong></h2>
                {!! $survey->description !!}
            </div>

            <div class="card card-body">
                <h2 class="mb-4"><strong>Shared Users</strong>
                    <br>
                    <small>Total of 0 shared users</small>
                </h2>
                <table class="table mb-0 thead-border-top-0 table-striped">
                        <thead>
                            <tr>
                                <th id="compact-table">#ID</th>
                                <th id="compact-table">Name</th>
                                <th id="compact-table">Role</th>
                                <th id="compact-table">Email</th>
                                <th id="compact-table">Contact</th>
                                <th id="compact-table">Country</th>
                                <th id="compact-table">Status</th>
                                <th id="compact-table">Created At</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="companies">
                            @foreach($survey_shared_users as $survey_shared_user)
                            @php
                                $user = User::find($survey_shared_user->user_id);
                                $country = Country::find($user->country_id)->first()->name;
                            @endphp
                                <tr>
                                    <td><div class="badge badge-light">#{{ $user->id }}</div></td>
                                    <td id="compact-table">
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex align-items-center">
                                                <a href="{{ route('auth.profile.view', [$user->id]) }}" style="text-decoration: none; color: #333;"><b>
                                                @if ($user->avatar)
                                                    <img src="{{ url($user->avatar) }}" width="30px">
                                                    {{ $user->name }}
                                                @else
                                                    <img src="{{ url(env('APP_ICON')) }}" width="30px" style="margin-right: 7px;">
                                                    {{ $user->name }}
                                                @endif
                                                </b></a>
                                            </div>

                                            <div class="d-flex align-items-center">
                                                @if ($user->email_verified_at != null)
                                                    <div class="badge badge-success ml-2">Verified</div>
                                                @else
                                                    <div class="badge badge-warning ml-2">Not Verified</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <a href="{{ route('auth.profile.view', [$user->id]) }}" style="margin-right: 7px">View</a> | 
                                            <a href="{{ route('admin.users.resend.email', [$user->id]) }}" id="space-table">Resend Survey</a> | 
                                            @if ($user->status == UserStatus::ACTIVE || $user->status == UserStatus::PENDING)
                                                <a href="#" data-href="{{ route('admin.users.delete', [$user->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Delete</a>
                                            @endif
                                        </div>
                                    </td>
                                    <td id="compact-table"><i class="material-icons icon-16pt mr-1 text-muted">face</i> {{ $user->role }}</td>
                                    <td id="compact-table"><i class="material-icons icon-16pt mr-1 text-muted">email</i> {{ $user->email }}</td>
                                    <td id="compact-table">
                                        @if ($user->mobile)
                                            <i class="material-icons icon-16pt mr-1 text-muted">phone_android</i> {{ $user->mobile }}<br>
                                        @endif
                                        
                                        @if ($user->phone)
                                            <i class="material-icons icon-16pt mr-1 text-muted">phone</i> {{ $user->phone }}
                                        @endif
                                    </td>
                                    <td id="compact-table"><i class="material-icons icon-16pt mr-1 text-muted">location_on</i> {{ $country }}</td>
                                    <td>
                                        @if ($user->status == UserStatus::ACTIVE)
                                            <div class="badge badge-success ml-2">Active</div>
                                        @elseif ($user->status == UserStatus::PENDING)
                                            <div class="badge badge-warning ml-2">Pending</div>
                                        @elseif ($user->status == UserStatus::INACTIVE)
                                            <div class="badge badge-danger ml-2">Inactive</div>
                                        @endif
                                    </td>
                                    <td id="compact-table"><i class="material-icons icon-16pt text-muted mr-1">today</i> {{ $user->created_at->format('M d Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if (count($survey_shared_users) <= 0)
                        <div style="padding: 20px">
                            <center><i class="material-icons icon-16pt mr-1 text-muted">assignment</i> No record/s found</center>
                        </div>
                    @endif
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-body">
                <div class="d-flex align-items-center mb-3">
                    <strong href="#" class="text-body">Name</strong>
                    <div class="ml-auto h2 mb-0">
                        <strong>{{ $survey->name }}</strong>
                    </div>
                </div>

                <!-- <div class="mb-4">
                    <button class="btn btn-primary btn-block">Purchase</button>
                    <button class="btn btn-light btn-block">Live preview</button>
                </div> -->

                <!-- <div class="mb-4 text-center">
                    <div class="d-flex flex-column align-items-center justify-content-center">

                        <span class="mb-1">
                            <a href="#" class="rating-link active"><i class="material-icons ">star</i></a>
                            <a href="#" class="rating-link active"><i class="material-icons ">star</i></a>
                            <a href="#" class="rating-link active"><i class="material-icons ">star</i></a>
                            <a href="#" class="rating-link active"><i class="material-icons ">star</i></a>
                            <a href="#" class="rating-link active"><i class="material-icons ">star_half</i></a>
                        </span>
                        <div class="d-flex align-items-center">
                            <strong>4.7/5</strong>
                            <span class="text-muted ml-1">&mdash; 4 reviews</span>
                        </div>

                    </div>
                </div> -->

                <div class="list-group list-group-flush mb-4">
                    <div class="list-group-item bg-transparent d-flex align-items-center px-0">
                        <strong>Category</strong>
                        <div class="ml-auto">{{ $category->name }}</div>
                    </div>
                    <div class="list-group-item bg-transparent d-flex align-items-center px-0">
                        <strong>Privacy</strong>
                        <div class="ml-auto">{{ $survey->privacy }}</div>
                    </div>
                    <div class="list-group-item bg-transparent d-flex align-items-center px-0">
                        <strong>Views</strong>
                        <div class="ml-auto">{{ $survey->views }}</div>
                    </div>
                </div>

                <div class="card card-body bg-dark text-white mb-0">

                    <ul class="list-unstyled ml-1 mb-0">
                        <li class="d-flex align-items-center"><i class="material-icons icon-16pt text-primary mr-2">check_circle</i> Survey is currently active</li>

                        @if ($survey->password)
                            <li class="d-flex align-items-center"><i class="material-icons icon-16pt text-primary mr-2">check_circle</i> Survey is protected with a password</li>
                        @endif

                        @if ($survey->privacy == 'public')
                            <li class="d-flex align-items-center pb-1"><i class="material-icons icon-16pt text-primary mr-2">check_circle</i> Survey is open to the public</li>
                        @elseif ($survey->privacy == 'private')
                            <li class="d-flex align-items-center pb-1"><i class="material-icons icon-16pt text-primary mr-2">check_circle</i> Survey has limited access</li>
                        @elseif ($survey->privacy == 'shared')
                            <li class="d-flex align-items-center pb-1"><i class="material-icons icon-16pt text-primary mr-2">check_circle</i> Survey can only be access to those shared people</li>
                        @endif
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>

@include('layouts.auth.footer')