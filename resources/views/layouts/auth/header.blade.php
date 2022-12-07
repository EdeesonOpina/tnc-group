<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>TNC Group</title>

    <!-- Favicons-->
    <link rel="shortcut icon" href="{{ url(env('APP_ICON')) }}" type="image/x-icon">
    <link rel="apple-touch-icon" type="image/x-icon" href="{{ url(env('APP_ICON')) }}">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="{{ url(env('APP_ICON')) }}">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="{{ url(env('APP_ICON')) }}">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="{{ url(env('APP_ICON')) }}">

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

    <!-- Dropzone -->
    <link type="text/css" href="{{ url('auth/admin/assets/css/vendor-dropzone.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ url('auth/admin/assets/css/vendor-dropzone.rtl.css') }}" rel="stylesheet">

    <!-- Flatpickr -->
    <link type="text/css" href="{{ url('auth/admin/assets/css/vendor-flatpickr.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ url('auth/admin/assets/css/vendor-flatpickr.rtl.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ url('auth/admin/assets/css/vendor-flatpickr-airbnb.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ url('auth/admin/assets/css/vendor-flatpickr-airbnb.rtl.css') }}" rel="stylesheet">


    
<script src="https://cdn.tiny.cloud/1/vo0lymzoradfze99cmcf2coaw4p5i8wusnlqbm0nbelrs1rg/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
  
<script>
    tinymce.init({
    selector:'#tiny',
    height: 400,
    toolbar: 'formatselect bold underline italic numlist bullist alignjustify alignleft aligncenter alignright ',
    plugins: [
      'advlist autolink lists link image charmap print preview hr anchor pagebreak',
      'searchreplace wordcount visualblocks visualchars code fullscreen',
      'insertdatetime media nonbreaking save table contextmenu directionality',
      'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc help'
      ]
  });
</script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-133433427-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-133433427-1');
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

    <!-- Select2 -->
    <link type="text/css" href="{{ url('auth/admin/assets/css/vendor-select2.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ url('auth/admin/assets/css/vendor-select2.rtl.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ url('auth/admin/assets/vendor/select2/select2.min.css') }}" rel="stylesheet">

    <style type="text/css">
    #margin-right {
        margin-right: 7px;
    }

    #hidden-options {
        display: none;
    }

    #large-font {
        style="font-size: 13px;
    }

    #top-space {
        margin-top: 7px;
    }

    #table-letter-margin {
        margin-right: 7px;
    }

    #bottom-spaced-field {
        margin-bottom: 20px;
    }

    #compact-table {
        width:1%;
        white-space:nowrap;
    }

    .no-underline {
        text-decoration: none !important;
    }

    #spaced {
        max-width: 100%;
        padding-top: 1rem;
        padding-left: 1rem;
        padding-right: 1rem;
        padding-bottom: 1rem;
        font-weight: 600;
        margin: 0;
    }

    #space-table {
        margin-left: 7px;
        margin-right: 7px;
    }

    #space-step {
        padding-left: 14px;
        padding-right: 14px;
        padding-top: 14px;
        padding-bottom: 14px;
    }

    #spaced-card {
        max-width: 100%;
        padding: 3rem;
        margin: 0;
    }

    #semi-spaced-card {
        max-width: 100%;
        padding-top: 3rem;
        padding-bottom: 3rem;
        margin: 0;
    }

    #spaced-radio {
        margin-right: 7px;
    }

    .note {
            font-size: 9px;
    }

    #force-uppercase {
        text-transform: uppercase;
    }

    #table-clickable {
        color: #333;
        text-decoration: none;
    }
    </style>


</head>

<body class="layout-default">

    <div class="preloader"></div>

    <!-- Header Layout -->
    <div class="mdk-header-layout js-mdk-header-layout">

        <!-- Header -->

        <div id="header" class="mdk-header js-mdk-header m-0" data-fixed>
            <div class="mdk-header__content">

                <div class="navbar navbar-expand-sm navbar-main navbar-dark bg-primary  pr-0" id="navbar" style="background-color: #FF5733 !important" data-primary>
                    <div class="container-fluid p-0">

                        <!-- Navbar toggler -->

                        <button class="navbar-toggler navbar-toggler-right d-block d-md-none" type="button" data-toggle="sidebar">
                            <span class="navbar-toggler-icon"></span>
                        </button>


                        <!-- Navbar Brand -->
                        <a href="{{ route('auth.dashboard') }}" class="navbar-brand ">
                            <img class="navbar-brand-icon" src="{{ url(env('BIG_FOUR_LOGO_WHITE')) }}" width="100" alt="{{ env('APP_NAME') }}">
                            <!-- <span>Stack</span> -->
                        </a>


                        <!-- <form class="search-form d-none d-sm-flex flex" action="index.html">
                            <button class="btn" type="submit" role="button"><i class="material-icons">search</i></button>
                            <input type="text" class="form-control" placeholder="Search">
                        </form> -->


                        <ul class="nav navbar-nav ml-auto d-none d-md-flex">
                            <li class="nav-item">
                                <!-- <a href="#" class="nav-link">
                                    <i class="material-icons">help_outline</i> Get Help
                                </a> -->
                            </li>
                        </ul>

                        <ul class="nav navbar-nav d-none d-sm-flex border-left navbar-height align-items-center">
                            <li class="nav-item dropdown">
                                <a href="#account_menu" class="nav-link dropdown-toggle" data-toggle="dropdown" data-caret="false">
                                    @if (auth()->user()->avatar)
                                        <img src="{{ url(auth()->user()->avatar) }}" class="rounded-circle" width="32" alt="{{ auth()->user()->firstname }}">
                                    @else
                                        <img src="{{ url(env('AVATAR_LOGO')) }}" class="rounded-circle" width="32" alt="{{ auth()->user()->firstname }}">
                                    @endif
                                    
                                    <span class="ml-1 d-flex-inline">
                                        <span class="text-light">{{ auth()->user()->firstname }} {{ auth()->user()->lastname }}</span>
                                    </span>
                                </a>
                                <div id="account_menu" class="dropdown-menu dropdown-menu-right">
                                    <div class="dropdown-item-text dropdown-item-text--lh">
                                        <div><strong>{{ auth()->user()->firstname }} {{ auth()->user()->lastname }}</strong></div>
                                        <div>{{ auth()->user()->role }}</div>
                                    </div>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('auth.dashboard') }}">Dashboard</a>
                                    <!-- <a class="dropdown-item" href="{{ route('auth.profile') }}">My profile</a> -->
                                    <!-- <a class="dropdown-item" href="{{ route('auth.profile.edit') }}">Edit account</a> -->
                                    <a class="dropdown-item" href="{{ route('admin.users.edit', [auth()->user()->id]) }}">Edit account</a>
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#change-password">Change Password</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('logout') }}">Logout</a>
                                </div>
                            </li>
                        </ul>

                    </div>
                </div>

            </div>
        </div>

        <!-- // END Header -->

        <!-- Header Layout Content -->
        <div class="mdk-header-layout__content">

            <div class="mdk-drawer-layout js-mdk-drawer-layout" data-push data-responsive-width="992px">
                <div class="mdk-drawer-layout__content page">