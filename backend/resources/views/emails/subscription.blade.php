{{--<!DOCTYPE html>--}}
{{--<html>--}}
{{--<head>--}}
{{--    <title>ItsolutionStuff.com</title>--}}
{{--</head>--}}
{{--<body>--}}
{{--<h1>{{ $mailData->amount_subtotal }}</h1>--}}
{{--<p>{{ $mailData->currency }}</p>--}}

{{--<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod--}}
{{--    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,--}}
{{--    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo--}}
{{--    consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse--}}
{{--    cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non--}}
{{--    proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>--}}

{{--<p>Thank you</p>--}}
{{--</body>--}}
{{--</html>--}}


    <!DOCTYPE html>
<html lang="en" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8">
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no, date=no, address=no, email=no">
    <!--[if mso]>
    <xml><o:officedocumentsettings><o:pixelsperinch>96</o:pixelsperinch></o:officedocumentsettings></xml>
    <![endif]-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700" rel="stylesheet" media="screen">
    <style>
        .hover-underline:hover {
            text-decoration: underline !important;
        }
        @media (max-width: 600px) {
            .sm-w-full {
                width: 100% !important;
            }
            .sm-px-24 {
                padding-left: 24px !important;
                padding-right: 24px !important;
            }
            .sm-py-32 {
                padding-top: 32px !important;
                padding-bottom: 32px !important;
            }
        }
    </style>
</head>
<body style="margin: 0; width: 100%; padding: 0; word-break: break-word; -webkit-font-smoothing: antialiased;">
<div role="article" aria-roledescription="email" aria-label="" lang="en" style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly;">
    <table style="width: 100%; font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif;" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td align="center" style="mso-line-height-rule: exactly; background-color: #eceff1; font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif;">
                <table class="sm-w-full" style="width: 600px;" cellpadding="0" cellspacing="0" role="presentation">
                    <tr>
                        <td class="sm-py-32 sm-px-24" style="mso-line-height-rule: exactly; padding: 48px; text-align: center; font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif;">
                            <a href="{{ route('home') }}" style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly;">
                                <img src="{{ asset('assets/img/logo.png') }}" width="90" alt="Deft@" style="max-width: 100%; vertical-align: middle; line-height: 100%; border: 0;">
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" class="sm-px-24" style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly;">
                            <table style="width: 100%;" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td class="sm-px-24" style="mso-line-height-rule: exactly; border-radius: 4px; background-color: #ffffff; padding: 48px; text-align: left; font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif; font-size: 16px; line-height: 24px; color: #626262;">
                                        <p style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; margin-bottom: 0; font-size: 20px; font-weight: 600;">Hey</p>
                                        <p style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; margin-top: 0; font-size: 24px; font-weight: 700; color: #fddf3d;">{{ $mailData->user->name }}</p>
                                        <p style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; margin: 0; margin-bottom: 24px;">
                                            Thanks for using Deft@ This is an invoice for your recent purchase.
                                        </p>
                                        <table style="width: 100%;" cellpadding="0" cellspacing="0" role="presentation">
                                            <tr>

                                            </tr>
                                        </table>
                                        <table style="width: 100%;" cellpadding="0" cellspacing="0" role="presentation">
                                            <tr>
                                                <td style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly;">
                                                    <h3 style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; margin-top: 0; text-align: left; font-size: 14px; font-weight: 700;">#{{ $mailData->id }}</h3>
                                                </td>
                                                <td style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly;">
                                                    <h3 style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; margin-top: 0; text-align: right; font-size: 14px; font-weight: 700;">
                                                        {{ $mailData->created_at->calendar() }}
                                                    </h3>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly;">
                                                    <table style="width: 100%;" cellpadding="0" cellspacing="0" role="presentation">
                                                        <tr>
                                                            <th align="left" style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; padding-bottom: 8px;">
                                                                <p style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly;">Description</p>
                                                            </th>
                                                            <th align="right" style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; padding-bottom: 8px;">
                                                                <p style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly;">Amount</p>
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; width: 80%; padding-top: 10px; padding-bottom: 10px; font-size: 16px;">
                                                                {{ $mailData->package->package_name }}
                                                            </td>
                                                            <td align="right" style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; width: 20%; text-align: right; font-size: 16px;">${{ $mailData->amount_subtotal }}.00</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; width: 80%; padding-top: 10px; padding-bottom: 10px; font-size: 16px;">
                                                                Handling Fees
                                                            </td>
                                                            <td align="right" style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; width: 20%; text-align: right; font-size: 16px;">$00.00</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; width: 80%;">
                                                                <p align="right" style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; margin: 0; padding-right: 16px; text-align: right; font-size: 16px; font-weight: 700; line-height: 24px;">
                                                                    Total
                                                                </p>
                                                            </td>
                                                            <td style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; width: 20%;">
                                                                <p align="right" style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; margin: 0; text-align: right; font-size: 16px; font-weight: 700; line-height: 24px;">
                                                                    ${{ $mailData->amount_subtotal }}.00
                                                                </p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                        <p style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; margin-top: 6px; margin-bottom: 20px; font-size: 16px; line-height: 24px;">
                                            If you have any questions about this invoice, simply reply to this email or reach out to our
                                            <a href="#" style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly;color: #fddf3d">support team</a> for help.
                                        </p>
                                        <p style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; margin-top: 6px; margin-bottom: 20px; font-size: 16px; line-height: 24px;">
                                            Thanks,
                                            <br>The Deft@ Team
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; height: 20px;"></td>
                    </tr>
                    <tr>
                        <td style="mso-line-height-rule: exactly; padding-left: 48px; padding-right: 48px; font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif; font-size: 14px; color: #eceff1;">
                            <p align="center" style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; margin-bottom: 16px; cursor: default;">
                                <a href="#" style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; color: #263238; text-decoration: none;"><img src="{{ asset('assets/img/mails/facebook.png') }}" width="17" alt="Facebook" style="max-width: 100%; vertical-align: middle; line-height: 100%; border: 0; margin-right: 12px;"></a>
                                &bull;
                                <a href="#" style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; color: #263238; text-decoration: none;"><img src="{{ asset('assets/img/mails/twitter.png') }}" width="17" alt="Twitter" style="max-width: 100%; vertical-align: middle; line-height: 100%; border: 0; margin-right: 12px;"></a>
                                &bull;
                                <a href="#" style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; color: #263238; text-decoration: none;"><img src="{{ asset('assets/img/mails/instagram.png') }}" width="17" alt="Instagram" style="max-width: 100%; vertical-align: middle; line-height: 100%; border: 0; margin-right: 12px;"></a>
                            </p>
                            <p style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; color: #263238;">
                                Use of our service and website is subject to our
                                <a href="{{route('home')}}" class="hover-underline" style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; color: #fddf3d; text-decoration: none;">Terms of Use</a> and
                                <a href="{{ route('home') }}" class="hover-underline" style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; color: #ebcf39; text-decoration: none;">Privacy Policy</a>.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; height: 16px;"></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
</body>
</html>








