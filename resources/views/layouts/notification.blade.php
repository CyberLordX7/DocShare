<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>{{ $title ?? 'DocShare' }}</title>
    <style type="text/css">
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }

        body { margin: 0 !important; padding: 0 !important; width: 100% !important; }

        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333333;
            background-color: #f7f7f7;
        }

        .email-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
        }

        .header {
            padding: 30px 20px;
            text-align: center;
            background: #ffffff;
        }

        .logo {
            max-width: 180px;
            height: auto;
        }

        .content {
            padding: 20px 30px 30px 30px;
            line-height: 1.6;
            font-size: 16px;
            color: #555555;
        }

        .footer {
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #999999;
            background: #f4f4f4;
        }

        .button {
            display: inline-block;
            padding: 12px 25px;
            background-color: #223a5c;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            margin: 15px 0;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .info-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eaeaea;
            vertical-align: top;
        }

        .info-table tr:last-child td {
            border-bottom: none;
        }

        .info-table .label {
            font-weight: 600;
            color: #333333;
            width: 35%;
        }

        .social-icons {
            padding: 15px 0;
        }

        .social-icon {
            display: inline-block;
            margin: 0 8px;
        }

        .social-icon img {
            width: 28px;
            height: 28px;
        }

        @media screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
            }
            .content {
                padding: 20px !important;
            }
            .info-table .label {
                width: 40% !important;
            }
        }

        @media screen and (max-width: 480px) {
            .header {
                padding: 20px 15px !important;
            }
            .info-table td {
                display: block;
                width: 100% !important;
                padding: 8px 0 !important;
                border-bottom: none !important;
            }
            .info-table tr {
                border-bottom: 1px solid #eaeaea;
                padding: 10px 0;
            }
            .info-table tr:last-child {
                border-bottom: none;
            }
            .button {
                display: block !important;
                width: 100% !important;
                text-align: center;
            }
        }
    </style>
     @stack('styles')
</head>
<body style="margin: 0; padding: 0; background-color: #f7f7f7;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 600px;" class="email-container">
        <tr>
            <td class="header" style="padding: 30px 20px; text-align: center;">
                <img src="{{ asset('docshare.jpg') }}" alt="DocShare Logo" class="logo" style="max-width: 180px; height: auto;" />
            </td>
        </tr>

        <tr>
            <td class="content" style="padding: 30px; line-height: 1.6; font-size: 16px; color: #555555;">
                @yield('content')
            </td>
        </tr>

        <tr>
            <td style="padding: 0 30px 20px 30px; color: #666666; font-size: 16px; line-height: 1.6;">
                <p style="margin: 0 0 15px 0;">If you have any questions, just reply to this email—we're always happy to help at <a href="mailto:operations@docshare.com" style="color: #223a5c; text-decoration: none;">operations@docshare.com</a></p>
                <p style="margin: 0;">Cheers,<br>The DocShare Team</p>
            </td>
        </tr>

        <tr>
            <td class="footer" style="padding: 20px; text-align: center; font-size: 12px; color: #999999; background: #f4f4f4;">
                <div class="social-icons" style="padding: 15px 0;">
                    <a href="/">
                        <img src="{{ asset('whatsapp.png') }}" class="img-fluid" alt="WhatsApp">
                    </a>
                    <a href="/">
                        <img src="{{ asset('linkedin.png') }}" class="img-fluid" alt="LinkedIn">
                    </a>
                    <a href="/">
                        <img src="{{ asset('discord.png') }}" class="img-fluid" alt="Discord">
                    </a>
                    <a href="/">
                        <img src="{{ asset('facebook.png') }}" class="img-fluid" alt="Facebook">
                    </a>
                    <a href="/">
                        <img src="{{ asset('tiktok.png') }}" class="img-fluid" alt="TikTok">
                    </a>
                </div>

                <p style="margin: 10px 0 15px 0;">
                    <a href="{{ url('/unsubscribe') }}" style="color: #666666; text-decoration: underline;">Unsubscribe</a> |
                    <a href="/" style="color: #666666; text-decoration: underline;">Privacy Policy</a>
                </p>

                <p style="margin: 0;">&copy; {{ date('Y') }} DocShare. All rights reserved.</p>
            </td>
        </tr>
    </table>
</body>
</html>
