<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

@if (Request::is('shop/items/view/*'))
    <!-- Author Meta -->
    <meta name="author" content="{{ env('APP_NAME') }}PC Warehouse">
    <!-- Meta Description -->
    <meta name="description" content="{{ $inventory->item->name }}">
    <!-- Meta Keyword -->
    <meta name="keywords" content="{!! strip_tags($inventory->item->description) !!}">

    <!-- for Facebook -->
    <meta property="og:title" content="{{ $inventory->item->name }}" />
    <meta property="og:type" content="website" />

    @if ($inventory->item->image)
        <meta property="og:image" content="{{ url($inventory->item->image) }}" />
    @endif

    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:description" content="{!! strip_tags($inventory->item->description) !!}" />

    <!-- for Twitter -->
    <meta name="twitter:title" content="{{ $inventory->item->name }}">
    <meta name="twitter:description" content="{!! strip_tags($inventory->item->description) !!}">

    @if ($inventory->item->image)
        <meta name="twitter:image" content="{{ url($inventory->item->image) }}">
    @endif

    <meta name="twitter:card" content="summary_large_image">

    <!-- for Google+ -->
    <meta itemprop="name" content="{{ $inventory->item->name }}">
    <meta itemprop="description" content="{!! strip_tags($inventory->item->description) !!}">

    @if ($inventory->item->image)
        <meta itemprop="image" content="{{ url($inventory->item->image) }}">
    @endif
@else
    @if (Request::is('contact'))
        <!-- Author Meta -->
        <meta name="author" content="{{ env('APP_NAME') }}">
        <!-- Meta Description -->
        <meta name="description" content="Feel free to send us a message. Help Center. 712-4675, 712-0089. ronalyn@bigfourglobal.com. MON to FRI 9am-6pm SAT 9am-5pm.">
        <!-- Meta Keyword -->
        <meta name="keywords" content="{{ env('APP_NAME') }}">

        <!-- for Facebook -->
        <meta property="og:title" content="Contact" />
        <meta property="og:type" content="website" />
        <meta property="og:image" content="{{ url(env('APP_BRAND')) }}" />
        <meta property="og:url" content="{{ url()->current() }}" />
        <meta property="og:description" content="Feel free to send us a message. Help Center. 712-4675, 712-0089. ronalyn@bigfourglobal.com. MON to FRI 9am-6pm SAT 9am-5pm." />
    @elseif (Request::is('build/*'))
        <!-- Author Meta -->
        <meta name="author" content="{{ env('APP_NAME') }}">
        <!-- Meta Description -->
        <meta name="description" content="Build your own PC using our platform. Choose individual parts.">
        <!-- Meta Keyword -->
        <meta name="keywords" content="{{ env('APP_NAME') }}">

        <!-- for Facebook -->
        <meta property="og:title" content="Contact" />
        <meta property="og:type" content="website" />
        <meta property="og:image" content="{{ url(env('APP_BRAND')) }}" />
        <meta property="og:url" content="{{ url()->current() }}" />
        <meta property="og:description" content="Build your own PC using our platform. Choose individual parts." />
    @else
        <!-- Author Meta -->
        <meta name="author" content="{{ env('APP_NAME') }}">
        <!-- Meta Description -->
        <meta name="description" content="{{ env('APP_NAME') }}">
        <!-- Meta Keyword -->
        <meta name="keywords" content="{{ env('APP_NAME') }}">

        <!-- for Facebook -->
        <meta property="og:title" content="{{ env('APP_NAME') }}" />
        <meta property="og:type" content="website" />
        <meta property="og:image" content="{{ url(env('APP_BRAND')) }}" />
        <meta property="og:url" content="{{ url()->current() }}" />
        <meta property="og:description" content="{{ env('APP_NAME') }}" />
    @endif
@endif

<meta property="fb:app_id" content="your_app_id" />
<meta name="twitter:site" content="@website-username">