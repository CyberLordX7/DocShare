@extends('layouts.notification')

@section('content')
    <h1 style="color: #223a5c; margin-top: 0;">@yield('title')</h1>

    <p style="margin-bottom: 20px;">Hello @yield('recipient_name'),</p>

    @yield('message_content')

    @hasSection('action_url')
    <div style="margin: 25px 0;">
        <a href="@yield('action_url')" style="@yield('action_style', 'display: inline-block; padding: 12px 24px; background-color: #223a5c; color: #ffffff; text-decoration: none; border-radius: 4px; font-weight: 600;')">
            @yield('action_text')
        </a>
    </div>
    @endif

    @hasSection('details')
    <table class="info-table" style="width: 100%; border-collapse: collapse; margin: 20px 0;">
        @yield('details')
    </table>
    @endif

    <p style="margin-top: 25px;">@yield('closing_message', 'Thank you for using our service!')</p>
@endsection
