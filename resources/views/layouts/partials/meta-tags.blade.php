<!-- Required meta tags -->
@if (Request::is('shop/items/view/*'))
    <!-- Author Meta -->
    <meta name="author" content="{{ str_replace('_', ' ', env('APP_NAME')) }}">
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
    <!-- Author Meta -->
    <meta name="author" content="{{ str_replace('_', ' ', env('APP_NAME')) }}">
    <!-- Meta Description -->
    <meta name="description"
        content="TNCPC Warehouse - Home of the One True Phoenix">
    <!-- Meta Keyword -->
    <meta name="keywords"
        content="TNCPC Warehouse - Home of the One True Phoenix">

    <!-- for Facebook -->
    <meta property="og:title" content="{{ str_replace('_', ' ', env('APP_NAME')) }}" />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="{{ url(env('APP_ICON')) }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:description"
        content="TNCPC Warehouse - Home of the One True Phoenix" />
@endif

<meta property="fb:app_id" content="your_app_id" />
<meta name="twitter:site" content="@website-username">