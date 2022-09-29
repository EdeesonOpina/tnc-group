@include('layouts.emails.header')
<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;"><img src="{{ url(env('APP_LOGO_WITH_TEXT')) }}" width="150px"></p>

<h3 style="font-family: sans-serif; font-weight: bold; margin: 0; Margin-bottom: 15px;">Newsletter Subscriber Added</h3>
<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">
  <strong>Email:</strong> {{ $email }}
  <br>
  <strong>Created At:</strong> {{ date('M-d-Y g:i A') }}
</p>

<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;"><small>&copy; {{ str_replace('_', ' ', env('APP_NAME')) }} {{ date('Y') }}</small>
</p>
@include('layouts.emails.footer')