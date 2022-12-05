@include('layouts.emails.header')
@php
  use Carbon\Carbon;
@endphp

<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;"><img src="{{ url(env('APP_LOGO_WITH_TEXT')) }}" width="150px"></p>

<h3 style="font-family: sans-serif; font-weight: bold; margin: 0; Margin-bottom: 15px;">Your cost estimate - {{ $brf->reference_number }} has been disapproved by {{ auth()->user()->firstname }} {{ auth()->user()->lastname }} for the following reason:</h3>

<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">
  {!! $brf->remarks !!}
</p>

<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">
  <a href="{{ route('internals.brf.manage', [$brf->id]) }}" target="_blank" style="display: inline-block; color: #ffffff; background-color: #FF6F11; border: solid 1px #FF6F11; border-radius: 0px; box-sizing: border-box; cursor: pointer; text-decoration: none; font-size: 14px; font-weight: bold; margin: 0; padding: 12px 25px; text-transform: capitalize; border-color: #FF6F11;">Click here to view</a> 
</p>

<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">
  Please apply the revisions to the cost estimate and re-submit for approval.
</p>

<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;"><small>&copy; {{ str_replace('_', ' ', env('APP_NAME')) }} {{ date('Y') }}</small>
</p>

@include('layouts.emails.footer')