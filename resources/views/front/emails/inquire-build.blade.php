@include('layouts.emails.header')
<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;"><img src="{{ url(env('APP_LOGO_WITH_TEXT')) }}" width="150px"></p>

<h3 style="font-family: sans-serif; font-weight: bold; margin: 0; Margin-bottom: 15px;">PC Build Inquiry</h3>

<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">
  <strong>Name:</strong> {{ $firstname }} {{ $lastname }}
  <br>
  <strong>Email:</strong> {{ $email }}
  <br>
  <strong>Mobile:</strong> {{ $mobile }}
  <br>
  <strong>Phone:</strong> {{ $phone }}
  <br>
  <strong>Sent At:</strong> {{ date('M-d-Y g:i A') }}
</p>

<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">
  <strong>List:</strong>
  <ul>
    @foreach ($build_items as $build_item)
      <li><strong>{{ $build_item->inventory->item->name }}</strong><br>
        {{ $build_item->inventory->item->brand->name }}<br>
        ₱{{ number_format($build_item->price, 2) }}<br>
        {{ $build_item->qty }}pc/s
      </li>
    @endforeach
  </ul>
  <strong>Grand Total: </strong>₱{{ number_format($build_items_total, 2) }}
</p>

<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">
  <strong>Message:</strong> {!! $description !!}
</p>

<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;"><small>&copy; {{ str_replace('_', ' ', env('APP_NAME')) }} {{ date('Y') }}</small>
</p>
@include('layouts.emails.footer')