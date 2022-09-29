@include('layouts.emails.header')
<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;"><img src="{{ url(env('APP_LOGO')) }}" width="150px"></p>
<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">
  Hello {{ auth()->user()->name }}! You have created {{ $item->name }} survey.
</p>
<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">
  You have created a <b>{{ $item->name }}</b> survey.
</p>

<h4>{{ $item->name }}</h4>
<p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">
  {!! $item->description !!}
</p>

<table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; box-sizing: border-box;">
  <tbody>
    <tr>
      <td align="left" style="font-family: sans-serif; font-size: 14px; vertical-align: top; padding-bottom: 15px;">
        <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: auto;">
          <tbody>
            <tr>
              <td style="font-family: sans-serif; font-size: 14px; vertical-align: top; background-color: #f8b600; border-radius: 5px; text-align: center;"> 

                <a href="{{ route('admin.items.view', [$item->id]) }}" target="_blank" style="display: inline-block; color: #ffffff; background-color: #{{ env('BUTTON_COLOR') }}; border: solid 1px #{{ env('BUTTON_COLOR') }}; border-radius: 0px; box-sizing: border-box; cursor: pointer; text-decoration: none; font-size: 14px; font-weight: bold; margin: 0; padding: 12px 25px; text-transform: capitalize; border-color: #{{ env('BUTTON_COLOR') }};">Click Here To View</a>
              </td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
  </tbody>
</table>
@include('layouts.emails.footer')