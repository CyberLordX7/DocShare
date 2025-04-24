@extends('layouts.notification')

@section('title', 'New File Upload - Requires Review')

@section('recipient_name', 'Admin Team')

@section('message_content')
    <p>Dear {{ $recipient_name }},</p>

    <p>A new file upload has been processed on the platform with the following details:</p>
@endsection

@section('action_url', $adminUrl)
@section('action_text', 'Review Upload')
@section('action_style', 'display: inline-block; padding: 12px 24px; background-color: #d9534f; color: #ffffff; text-decoration: none; border-radius: 4px; font-weight: 600;')

@section('details')
    <tr>
        <td class="label" style="padding: 10px; text-align: left; font-weight: bold; width: 35%;">Upload Reference:</td>
        <td style="padding: 10px; text-align: left;">{{ $session->token }}</td>
    </tr>
    <tr>
        <td class="label" style="padding: 10px; font-weight: bold;">Uploaded By:</td>
        <td style="padding: 10px;">
            {{ $session->user ? $session->user->email : 'Guest User' }}
            @if($session->user && $session->user->name)
                ({{ $session->user->name }})
            @endif
        </td>
    </tr>
    <tr>
        <td class="label" style="padding: 10px; text-align: left; font-weight: bold;">Files Count:</td>
        <td style="padding: 10px; text-align: left;">{{ $session->files->count() }}</td>
    </tr>
    <tr>
        <td class="label" style="padding: 10px; text-align: left; font-weight: bold;">Total Size:</td>
        <td style="padding: 10px; text-align: left;">{{ format_bytes($session->files->sum('size')) }}</td>
    </tr>
    <tr>
        <td class="label" style="padding: 10px; text-align: left; font-weight: bold;">Expires:</td>
        <td style="padding: 10px; text-align: left;">{{ $session->expires_at->format('F j, Y \a\t g:i A') }}</td>
    </tr>
    <tr>
        <td class="label" style="padding: 10px; text-align: left; font-weight: bold;">IP Address:</td>
        <td style="padding: 10px; text-align: left;">{{ $session->ip_address }}</td>
    </tr>
@endsection
