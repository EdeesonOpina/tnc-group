@include('layouts.emails.header')
@php
  use Carbon\Carbon;
@endphp

<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;"><img src="{{ url(env('APP_LOGO_WITH_TEXT')) }}" width="150px"></p>

<h3 style="font-family: sans-serif; font-weight: bold; margin: 0; Margin-bottom: 15px;">Finance has uploaded a release file. Here are the BRF details:</h3>

<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">
  <strong>BRF #:</strong> {{ $brf->reference_number }}
  <br>
  <strong>Payment To:</strong> 
  @if ($brf->payment_for_user)
      {{ $brf->payment_for_user->firstname }} {{ $brf->payment_for_user->lastname }}
  @endif

  @if ($brf->payment_for_supplier)
      {{ $brf->payment_for_supplier->name }}
  @endif
  <br>
  <strong>Payment For:</strong> {{ $brf->name }}
  <br>
  <strong>CE #:</strong> {{ $brf->project->reference_number }}
  <br>
  <strong>Project Name:</strong> {{ $brf->name }}
  <br>
  <strong>Client:</strong> {{ $brf->project->client->name }}
</p>

<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">
  <a href="{{ route('internals.brf.view', [$brf->reference_number]) }}" target="_blank" style="display: inline-block; color: #ffffff; background-color: #FF6F11; border: solid 1px #FF6F11; border-radius: 0px; box-sizing: border-box; cursor: pointer; text-decoration: none; font-size: 14px; font-weight: bold; margin: 0; padding: 12px 25px; text-transform: capitalize; border-color: #FF6F11;">Click here to view and approve</a> 
</p>

<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;"><small>&copy; {{ str_replace('_', ' ', env('APP_NAME')) }} {{ date('Y') }}</small>
</p>

@include('layouts.emails.footer')