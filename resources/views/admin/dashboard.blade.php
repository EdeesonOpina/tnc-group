@include('layouts.auth.header')
@php
    use App\Models\UserStatus;
@endphp

<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </nav>
            <h1 class="m-0">Dashboard</h1>
        </div>
        <!-- <a href="" class="btn btn-light ml-3"><i class="material-icons icon-16pt text-muted mr-1">settings</i> Settings</a> -->
    </div>
</div>


<div class="container-fluid page__container">
    @include('layouts.partials.alerts')
    @include('layouts.partials.top-tabs')

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header card-header-large bg-white d-flex align-items-center">
                    <h4 class="card-header__title flex m-0">Recent Users</h4>
                    <div data-toggle="flatpickr" data-flatpickr-wrap="true" data-flatpickr-static="true" data-flatpickr-mode="range" data-flatpickr-alt-format="d/m/Y" data-flatpickr-date-format="d/m/Y">
                        
                    </div>
                </div>

                <div class="table-responsive">
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
                            @foreach($users as $user)
                                <tr>
                                    <td><div class="badge badge-light">#{{ $user->id }}</div></td>
                                    <td id="compact-table">
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex align-items-center">
                                                <a href="{{ route('auth.profile.view', [$user->id]) }}" style="text-decoration: none; color: #333;"><b>
                                                @if ($user->avatar)
                                                    <img src="{{ url($user->avatar) }}" width="30px">
                                                    {{ $user->firstname }} {{ $user->firstname }}
                                                @else
                                                    <img src="{{ url(env('BIG_FOUR_ICON')) }}" width="30px" style="margin-right: 7px;">
                                                    {{ $user->firstname }} {{ $user->lastname }}
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
                                            <!-- <a href="{{ route('auth.profile.view', [$user->id]) }}" style="margin-right: 7px">View</a> | 
                                            <a href="{{ route('admin.users.edit', [$user->id]) }}" id="space-table">Edit</a> | 
                                            <a href="{{ route('admin.users.resend.email', [$user->id]) }}" id="space-table">Resend Email</a> | 
                                            @if ($user->status == UserStatus::ACTIVE || $user->status == UserStatus::PENDING)
                                                <a href="#" data-href="{{ route('admin.users.delete', [$user->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Delete</a>
                                            @endif

                                            @if ($user->status == UserStatus::INACTIVE)
                                                <a href="#" data-href="{{ route('admin.users.recover', [$user->id]) }}" data-toggle="modal" data-target="#confirm-action" id="space-table">Recover</a>
                                            @endif -->
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
                                    <td id="compact-table"><i class="material-icons icon-16pt mr-1 text-muted">location_on</i> {{ $user->country->name }}</td>
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

                    @if (count($users) <= 0)
                        <div style="padding: 20px">
                            <center><i class="material-icons icon-16pt mr-1 text-muted">assignment</i> No record/s found</center>
                        </div>
                    @endif
                </div>

                <div class="card-footer text-center border-0">
                    <a class="text-muted" href="{{ route('admin.users') }}">View All</a>
                </div>
            </div>

            <p class="text-dark-gray d-flex align-items-center mt-3">
                <i class="material-icons icon-muted mr-2">event</i>
                <strong>Authentication</strong>
            </p>

            @foreach ($activity_log_auth as $al_auth)
                <div class="row align-items-center projects-item mb-1">
                    <div class="col-sm-auto mb-1 mb-sm-0">
                        <div class="text-dark-gray">{{ $al_auth->created_at->format('g:i a') }}</div>
                    </div>
                    <div class="col-sm">
                        <div class="card m-0">
                            <div class="px-4 py-3">
                                <div class="row align-items-center">
                                    <div class="col" style="min-width: 300px">
                                        <div class="d-flex align-items-center">
                                            <a href="#" class="text-body"><strong class="text-15pt mr-2">{{ $al_auth->description }}</strong></a>
                                            <span class="badge badge-success">{{ $al_auth->uri }}</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <small class="text-dark mr-2">{{ $al_auth->device }}</small>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <small class="text-dark-gray mr-2">User</small>
                                            <a href="#" class="d-flex align-items-middle">
                                                <span class="avatar avatar-xxs avatar-online mr-2">
                                                    @if ($al_auth->auth)
                                                        @if ($al_auth->auth->avatar)
                                                            <img src="{{ url($al_auth->auth->avatar) }}" alt="{{ $al_auth->auth->firstname }} {{ $al_auth->auth->lastname }}" class="avatar-img rounded-circle">
                                                        @else
                                                            <img src="{{ url(env('BIG_FOUR_ICON')) }}" alt="{{ $al_auth->auth->firstname }} {{ $al_auth->auth->lastname }}" class="avatar-img rounded-circle">
                                                        @endif
                                                    @else
                                                        <img src="{{ url(env('BIG_FOUR_ICON')) }}" alt="anon" class="avatar-img rounded-circle">
                                                    @endif
                                                </span>
                                                @if ($al_auth->auth)
                                                    {{ $al_auth->auth->firstname }} {{ $al_auth->auth->lastname }}
                                                @else
                                                    Anonymous
                                                @endif
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-auto d-flex align-items-center">
                                        <i class="material-icons icon-muted mr-2">access_time</i>
                                        {{ $al_auth->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="col">
            <div class="card card-body">
                <canvas id="line-chart-30-days"></canvas>
            </div>

            <div class="card card-body">
                <canvas id="line-chart-7-days"></canvas>
            </div>
        </div>
    </div>

</div>


@include('layouts.auth.footer')

<script>
  $(function(){
      //get the line chart canvas
      var cData = JSON.parse(`<?php echo $last_30_days_chart['chart_data']; ?>`);
      var ctx = $("#line-chart-30-days");
 
      //pie chart data
      var data = {
        labels: cData.label,
        datasets: [
          {
            label: "Transactions",
            data: cData.data,
            backgroundColor: [
               "#51D016", 
            ],
            borderColor: [
              "#51D016",
            ],
            borderWidth: [1]
          }
        ]
      };
 
      //options
      var options = {
        responsive: true,
        title: {
          display: true,
          position: "top",
          text: "Last 30 Days",
          fontColor: "#111"
        },
        legend: {
          display: true,
          position: "bottom",
          labels: {
            fontColor: "#333"
          }
        }
      };
 
      //create Pie Chart class object
      var chart1 = new Chart(ctx, {
        type: "line",
        data: data,
        options: options
      });
 
  });
</script>

<script>
  $(function(){
      //get the line chart canvas
      var cData = JSON.parse(`<?php echo $last_7_days_chart['chart_data']; ?>`);
      var ctx = $("#line-chart-7-days");
 
      //pie chart data
      var data = {
        labels: cData.label,
        datasets: [
          {
            label: "Transactions",
            data: cData.data,
            backgroundColor: [
               "#51D016", 
            ],
            borderColor: [
              "#51D016",
            ],
            borderWidth: [1]
          }
        ]
      };
 
      //options
      var options = {
        responsive: true,
        title: {
          display: true,
          position: "top",
          text: "Last 7 Days",
          fontColor: "#111"
        },
        legend: {
          display: true,
          position: "bottom",
          labels: {
            fontColor: "#333"
          }
        }
      };
 
      //create Pie Chart class object
      var chart1 = new Chart(ctx, {
        type: "line",
        data: data,
        options: options
      });
 
  });
</script>