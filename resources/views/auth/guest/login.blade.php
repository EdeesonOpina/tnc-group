<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login</title>

    <!-- Prevent the demo from appearing in search engines -->
    <meta name="robots" content="noindex">

    <!-- Simplebar -->
    <link type="text/css" href="{{ url('auth/admin/assets/vendor/simplebar.min.css') }}" rel="stylesheet">

    <!-- App CSS -->
    <link type="text/css" href="{{ url('auth/admin/assets/css/app.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ url('auth/admin/assets/css/app.rtl.css') }}" rel="stylesheet">

    <!-- Material Design Icons -->
    <link type="text/css" href="{{ url('auth/admin/assets/css/vendor-material-icons.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ url('auth/admin/assets/css/vendor-material-icons.rtl.css') }}" rel="stylesheet">

    <!-- Font Awesome FREE Icons -->
    <link type="text/css" href="{{ url('auth/admin/assets/css/vendor-fontawesome-free.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ url('auth/admin/assets/css/vendor-fontawesome-free.rtl.css') }}" rel="stylesheet">


    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-31FE9FZ0JW"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-31FE9FZ0JW');
    </script>


    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
          n.callMethod.apply(n,arguments):n.queue.push(arguments)};
          if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
          n.queue=[];t=b.createElement(e);t.async=!0;
          t.src=v;s=b.getElementsByTagName(e)[0];
          s.parentNode.insertBefore(t,s)}(window, document,'script',
              'https://connect.facebook.net/en_US/fbevents.js');
          fbq('init', '327167911228268');
          fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=327167911228268&ev=PageView&noscript=1" /></noscript>
    <!-- End Facebook Pixel Code -->

    <!-- Author Meta -->
    <meta name="author" content="{{ env('APP_NAME') }}">
    <!-- Meta Description -->
    <meta name="description" content="Login to access your BigFour Global Technologies Inc. account">
    <!-- Meta Keyword -->
    <meta name="keywords" content="{{ env('APP_NAME') }}">

    <!-- for Facebook -->
    <meta property="og:title" content="Login" />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="{{ url(env('APP_BRAND')) }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:description" content="Login to access your BigFour Global Technologies Inc. account" />

  </head>

  <body class="layout-login">

    <div class="layout-login__overlay"></div>
    <div class="layout-login__form bg-white" data-simplebar>
        <div class="d-flex justify-content-left mt-2 mb-5 navbar-light">
            <a href="{{ route('site.index') }}" class="navbar-brand" style="min-width: 0">
                <img class="navbar-brand-icon" src="{{ url(env('APP_LOGO_WITH_TEXT')) }}" width="250" alt="{{ str_replace('_', ' ', env('APP_NAME')) }}">
                <!-- <span>{{ str_replace('_', ' ', env('APP_NAME')) }}</span> -->
            </a>
        </div>

        <h4 class="m-0">Welcome back!</h4>
        <p class="mb-5">Login to access your {{ str_replace('_', ' ', env('APP_NAME')) }} account </p>

        @include('layouts.partials.alerts')

        <form action="{{ route('login') }}" method="post" novalidate>
            {{ csrf_field() }}
            <div class="form-group">
                <label class="text-label" for="email_2">Email Address:</label>
                <div class="input-group input-group-merge">
                    <input id="email_2" name="email" type="email" required="" class="form-control form-control-prepended" placeholder="john@doe.com" value="{{ old('email') }}">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <span class="far fa-envelope"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="text-label" for="password_2">Password:</label>
                <div class="input-group input-group-merge">
                    <input id="password_2" name="password" type="password" required="" class="form-control form-control-prepended" placeholder="Enter your password">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <span class="fa fa-key"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group mb-5">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" checked="" id="remember">
                    <label class="custom-control-label" for="remember">Remember me</label>
                </div>
            </div>
            <div class="form-group text-center">
                <button class="btn btn-primary mb-5" type="submit">Login</button><br>
            </div>
        </form>
    </div>


    <!-- jQuery -->
    <script src="{{ url('auth/admin/assets/vendor/jquery.min.js') }}"></script>

    <!-- Bootstrap -->
    <script src="{{ url('auth/admin/assets/vendor/popper.min.js') }}"></script>
    <script src="{{ url('auth/admin/assets/vendor/bootstrap.min.js') }}"></script>

    <!-- Simplebar -->
    <script src="{{ url('auth/admin/assets/vendor/simplebar.min.js') }}"></script>

    <!-- DOM Factory -->
    <script src="{{ url('auth/admin/assets/vendor/dom-factory.js') }}"></script>

    <!-- MDK -->
    <script src="{{ url('auth/admin/assets/vendor/material-design-kit.js') }}"></script>

    <!-- App -->
    <script src="{{ url('auth/admin/assets/js/toggle-check-all.js') }}"></script>
    <script src="{{ url('auth/admin/assets/js/check-selected-row.js') }}"></script>
    <script src="{{ url('auth/admin/assets/js/dropdown.js') }}"></script>
    <script src="{{ url('auth/admin/assets/js/sidebar-mini.js') }}"></script>
    <script src="{{ url('auth/admin/assets/js/app.js') }}"></script>

    <!-- App Settings (safe to remove) -->
    <script src="{{ url('auth/admin/assets/js/app-settings.js') }}"></script>
</body>

</html>