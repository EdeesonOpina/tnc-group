<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ $survey->name }}</title>

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

      <style type="text/css">
        #hidden-options {
            display: none;
        }

        #empty-space {
            padding-top: 40px;
            padding-bottom: 40px;
        }

        #semi-empty-space {
            padding-top: 20px;
            padding-bottom: 20px;
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
        </style>

  </head>

  <body>