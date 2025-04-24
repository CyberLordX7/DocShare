@extends('layouts.notification')

@section('title', 'Your Files Are Ready to Share')

@section('recipient_name', $user->name ?? 'User')

@section('message_content')
    <p>Your file upload has been successfully processed and is now ready to share.</p>
    <p>The download link will remain active until <strong>{{ $session->expires_at->format('F j, Y \a\t g:i A') }}</strong>.</p>
@endsection

@section('action_url', $downloadUrl)
@section('action_text', 'Access Your Files')

@section('details')
    <tr>
        <td class="label" style="padding: 10px; text-align: left; font-weight: bold; width: 35%;">Upload Reference:</td>
        <td style="padding: 10px; text-align: left;">{{ $session->token }}</td>
    </tr>
    <tr>
        <td class="label" style="padding: 10px; text-align: left; font-weight: bold;">Total Files:</td>
        <td style="padding: 10px; text-align: left;">{{ $session->files->count() }}</td>
    </tr>
    <tr>
        <td class="label" style="padding: 10px; text-align: left; font-weight: bold;">Total Size:</td>
        <td style="padding: 10px; text-align: left;">{{ format_bytes($session->files->sum('size')) }}</td>
    </tr>
    <tr>
        <td class="label" style="padding: 10px; text-align: left; font-weight: bold;">Expiration:</td>
        <td style="padding: 10px; text-align: left;">{{ $session->expires_at->format('F j, Y \a\t g:i A') }}</td>
    </tr>
@endsection

@section('closing_message')
    <p>For security reasons, please don't forward this email. If you need to share these files, use the share link instead.</p>
    <p>Thank you for using our service!</p>
@endsection
