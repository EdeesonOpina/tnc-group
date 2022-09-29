@php
    use App\Models\Country;
@endphp
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Register</title>

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

    <!-- Select2 -->
    <link type="text/css" href="{{ url('auth/admin/assets/css/vendor-select2.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ url('auth/admin/assets/css/vendor-select2.rtl.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ url('auth/admin/assets/vendor/select2/select2.min.css') }}" rel="stylesheet">

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-31FE9FZ0JW"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-31FE9FZ0JW');
    </script>

    <!-- Author Meta -->
    <meta name="author" content="{{ env('APP_NAME') }}">
    <!-- Meta Description -->
    <meta name="description" content="Create an account with BigFour Global Technologies Inc.">
    <!-- Meta Keyword -->
    <meta name="keywords" content="{{ env('APP_NAME') }}">

    <!-- for Facebook -->
    <meta property="og:title" content="Sign Up" />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="{{ url(env('APP_BRAND')) }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:description" content="Create an account with BigFour Global Technologies Inc." />

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

  </head>

  <body class="layout-login-centered-boxed">
    <div class="layout-login-centered-boxed__form card">
        <div class="d-flex flex-column justify-content-center align-items-center mt-2 mb-5 navbar-light">
            <a href="{{ route('site.index') }}" class="navbar-brand flex-column mb-2 align-items-center mr-0" style="min-width: 0">
                <img class="navbar-brand-icon mr-0 mb-2" src="{{ url(env('APP_LOGO_WITH_TEXT')) }}" width="250" alt="{{ str_replace('_', ' ', env('APP_NAME')) }}">
                <!-- <span>{{ str_replace('_', ' ', env('APP_NAME')) }}</span> -->
            </a>
            <p class="m-0">Create an account with {{ str_replace('_', ' ', env('APP_NAME')) }}</p>
        </div>

        <a href="#" class="btn btn-light btn-block">
            <span class="fab fa-google mr-2"></span>
            Continue with Google
        </a>

        <div class="page-separator">
            <div class="page-separator__text">or</div>
        </div>

        @include('layouts.partials.alerts')
        <form action="{{ route('auth.create') }}" method="post">
            {{ csrf_field() }}
            <div class="form-group">
                <label class="text-label" for="name_2">Name:</label>
                <div class="input-group input-group-merge">
                    <input id="name_2" type="text" name="name" class="form-control form-control-prepended" placeholder="John Doe" value="{{ old('name') }}">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <span class="far fa-user"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="text-label" for="country">Country:</label>
                <select id="country" name="country_id" class="custom-select" data-toggle="select">
                    @if (old('country_id'))
                    @php
                        $old_country = Country::find(old('country_id'));
                    @endphp
                        <option value="{{ $old_country->id }}">{{ $old_country->name }}</option>
                    @endif
                    <option value="">Select Country</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="text-label" for="email_2">Email Address:</label>
                <div class="input-group input-group-merge">
                    <input id="email_2" name="email" type="email" class="form-control form-control-prepended" placeholder="john@doe.com" value="{{ old('email') }}">
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
                    <input id="password_2" name="password" type="password" class="form-control form-control-prepended" placeholder="Enter your password">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <span class="fa fa-key"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="text-label" for="confirm_password">Confirm Password:</label>
                <div class="input-group input-group-merge">
                    <input id="confirm_password" name="confirm_password" type="password" class="form-control form-control-prepended" placeholder="Confirm your password">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <span class="fa fa-key"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group mb-5">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="terms" name="terms_and_conditions" />
                    <label class="custom-control-label" for="terms">I accept <a href="{{ route('site.terms') }}" target="_blank">Terms and Conditions</a></label>
                </div>
            </div>
            <div class="form-group text-center">
                <button class="btn btn-primary mb-2" type="submit">Create Account</button><br>
                Have an account? <a class="text-body text-underline" href="{{ route('login') }}">Login</a>
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

    <!-- Select2 -->
    <script src="{{ url('auth/admin/assets/vendor/select2/select2.min.js') }}"></script>
    <script src="{{ url('auth/admin/assets/js/select2.js') }}"></script>

    <!-- App Settings (safe to remove) -->
    <script src="{{ url('auth/admin/assets/js/app-settings.js') }}"></script>
</body>

</html>