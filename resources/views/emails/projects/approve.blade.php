@include('layouts.emails.header')
@php
  use Carbon\Carbon;
@endphp

<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;"><img src="{{ url(env('APP_LOGO_WITH_TEXT')) }}" width="150px"></p>

<h3 style="font-family: sans-serif; font-weight: bold; margin: 0; Margin-bottom: 15px;">Your cost estimate has been approved! Please see the details below:</h3>

<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">
  <strong>CE Submission Date:</strong> {{ Carbon::parse($project->created_at)->format('M d Y') }}
  <br>
  <strong>Project Name:</strong> {{ $project->name }}
  <br>
  <strong>CE#:</strong> {{ $project->reference_number }}
  <br>
  <!-- <strong>Start Date:</strong> {{ Carbon::parse($project->start_date)->format('M d Y') }}
  <br>
  <strong>End Date:</strong> {{ Carbon::parse($project->end_date)->format('M d Y') }}
  <br>
  <strong>Duration Date:</strong> {{ Carbon::parse($project->duration_date)->format('M d Y') }}
  <br> -->
  <!-- <strong>Company:</strong> {{ $project->company->name }}
  <br> -->
  <strong>Client:</strong> {{ $project->client->name }}
</p>

<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">
  <a href="{{ route('internals.projects.view', [$project->reference_number]) }}" target="_blank" style="display: inline-block; color: #ffffff; background-color: #FF6F11; border: solid 1px #FF6F11; border-radius: 0px; box-sizing: border-box; cursor: pointer; text-decoration: none; font-size: 14px; font-weight: bold; margin: 0; padding: 12px 25px; text-transform: capitalize; border-color: #FF6F11;">Click here to view</a> 
</p>

<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;"><small>&copy; {{ str_replace('_', ' ', env('APP_NAME')) }} {{ date('Y') }}</small>
</p>

@include('layouts.emails.footer')