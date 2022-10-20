@include('layouts.auth.header')

<div class="mdk-drawer-layout__content page">

    <div style="padding-bottom: calc(5.125rem / 2); position: relative; margin-bottom: 1.5rem;">
        <div class="bg-primary" style="min-height: 150px;">
            <div class="d-flex align-items-end container-fluid page__container" style="position: absolute; left: 0; right: 0; bottom: 0;">
                <div class="avatar avatar-xl">
                    @if ($user->avatar)
                        <img src="{{ url($user->avatar) }}" alt="avatar" class="avatar-img rounded" style="border: 2px solid white;">
                    @else
                        <img src="{{ url(env('APP_ICON')) }}" alt="avatar" class="avatar-img rounded" style="border: 2px solid white; background: white;">
                    @endif

                </div>
                <div class="card-header card-header-tabs-basic nav flex" role="tablist">
                    <a href="#activity" class="active show" data-toggle="tab" role="tab" aria-selected="true">Activity</a>
                    <a href="#purchases" data-toggle="tab" role="tab" aria-selected="false">Purchases</a>
                    <a href="#emails" data-toggle="tab" role="tab" aria-selected="false">Emails</a>
                    <a href="#quotes" data-toggle="tab" role="tab" aria-selected="false">Quotes</a>
                </div>
            </div>
        </div>
    </div>



    <div class="container-fluid page__container">
        <div class="row">
            <div class="col-lg-3">
                <h1 class="h4 mb-1">{{ $user->firstname }} {{ $user->lastname }}</h1>
                <p>{{ $user->role }}</p>
                <div class="d-flex align-items-center">
                    <i class="material-icons mr-1">email</i>
                    <div class="flex">{{ $user->email }}</div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="tab-content">
                    <div class="tab-pane active" id="activity">


                        <div class="card">
                            <div class="px-4 py-3">
                                <div class="d-flex mb-1">
                                    <div class="avatar avatar-sm mr-3">
                                        @if ($user->avatar)
                                            <img src="{{ url($user->avatar) }}" alt="Avatar" class="avatar-img rounded-circle">
                                        @else
                                            <img src="{{ url(env('APP_ICON')) }}" alt="Avatar" class="avatar-img rounded-circle">
                                        @endif
                                    </div>
                                    <div class="flex">
                                        <div class="d-flex align-items-center mb-1">
                                            <strong class="text-15pt">{{ $user->name }}</strong>
                                            <small class="ml-2 text-muted">3 days ago</small>
                                        </div>
                                        <div>
                                            <p>Thanks for contributing to the release of FREE Admin Vision - PRO Admin Dashboard Theme <a href="">https://www.frontted.com/themes/admin-vision...</a> 🔥</p>
                                            <p><a href="">#themeforest</a> <a href="">#EnvatoMarket</a></p>
                                        </div>

                                        <div class="d-flex align-items-center">
                                            <a href="" class="text-muted d-flex align-items-center decoration-0"><i class="material-icons mr-1" style="font-size: inherit;">favorite_border</i> 38</a>
                                            <a href="" class="text-muted d-flex align-items-center decoration-0 ml-3"><i class="material-icons mr-1" style="font-size: inherit;">thumb_up</i> 71</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>


    @include('layouts.auth.footer')