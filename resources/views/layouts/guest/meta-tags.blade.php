<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Author Meta -->
<meta name="author" content="{{ str_replace('_', ' ', env('APP_NAME')) }}">
<!-- Meta Description -->
<meta name="description" content="Grow Your Business With Us">
<!-- Meta Keyword -->
<meta name="keywords" content="TNC, TNC Group, Grow Your Business With Us, Business Units">

<!-- for Facebook -->
<meta property="og:title" content="{{ str_replace('_', ' ', env('APP_NAME')) }}" />
<meta property="og:type" content="website" />
<meta property="og:image" content="{{ url(env('APP_BRAND')) }}" />
<meta property="og:url" content="{{ url()->current() }}" />
<meta property="og:description" content="{{ str_replace('_', ' ', env('APP_NAME')) }}" />

<meta property="fb:app_id" content="your_app_id" />
<meta name="twitter:site" content="@website-username">