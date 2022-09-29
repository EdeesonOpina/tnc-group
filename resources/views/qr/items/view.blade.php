@include('layouts.generic.header')
<div class="container page__container">
    <div id="empty-space"></div>
    <center>
        <a href="{{ route('site.index') }}">
            <img src="{{ url(env('BIG_FOUR_LOGO')) }}" width="40%">
        </a>
    </center>
    <div id="empty-space"></div>
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card" id="spaced-card">
                <div class="card-body">
                    <div class="row">
                    <div class="col">
                        @if ($item->image)
                            <img src="{{ url($item->image ?? env('BIG_FOUR_ICON')) }}" alt="{{ $item->name }}" width="100%">
                        @else
                            <img src="{{ url(env('BIG_FOUR_ICON')) }}" width="100%">
                        @endif
                        <div class="row">
                            @foreach($item_photos as $item_photo)
                                <div class="col-md-6">
                                    <img src="{{ url($item_photo->image ?? env('BIG_FOUR_ICON')) }}" alt="{{ $item->name }}" style="width: 100%; height: 100px;">
                                </div>
                            @endforeach
                        </div>
                    </div>
                
                    <div class="col">
                        <div class="form-group">
                            <h5>{{ $item->name }}</h5>
                            {{ $item->category->name }}<br>
                            {{ $item->sub_category->name }}<br>
                            {{ $item->brand->name }}<br><br>
                            {!! $item->description !!}<br><br>
                            {!! QrCode::size(50)->generate(url(route('qr.items.view', [$item->id]))) !!}
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="text-center">
        <small>&copy; {{ str_replace('_', ' ', env('APP_NAME')) }} {{ date('Y') }}</small>
    </div>
    <div id="empty-space"></div>
</div>
@include('layouts.generic.footer')