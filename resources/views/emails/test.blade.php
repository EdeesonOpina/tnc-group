@include('layouts.emails.header')
@php
  use Carbon\Carbon;
@endphp

<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;"><img src="{{ url(env('APP_LOGO_WITH_TEXT')) }}" width="150px"></p>

<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">
  This is a test email.
</p>

<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;"><small>&copy; {{ str_replace('_', ' ', env('APP_NAME')) }} {{ date('Y') }}</small>
</p>

@include('layouts.emails.footer')