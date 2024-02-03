<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <style>
        @media only screen and (max-width: 600px) {
            .inner-body {
                width: 100% !important;
            }

            .footer {
                width: 100% !important;
            }
        }

        @media only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }
    </style>
</head>
<body>

<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
        <td align="center">
            <table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color: #ffffff;">
{{--                {{ $header ?? '' }}--}}

{{--                <tr>--}}
{{--                    <td class="header">--}}
{{--                        <a href="https://www.semytechnology.com/" target="_blank" style="display: inline-block;">--}}
{{--                            <img src="https://crm.semytechnology.com/img/logo/semy-light2-mail.png" style="width: 250px;" alt="">--}}
{{--                        </a>--}}
{{--                    </td>--}}
{{--                </tr>--}}

                <!-- Email Body -->
                <tr>
                    <td class="body" width="100%" cellpadding="0" cellspacing="0" style="    background-color: #ffffff; border: none;">
                        <table class="inner-body" align="left" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color: #ffffff; color: #000000;">
                            <!-- Body content -->
                            <tr>
                                <td class="content-cell" style="padding-left: 0px;">

                                    {!! $text !!}

                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="footer" width="100%" cellpadding="0" cellspacing="0" style="">
                        <table class="inner-body" align="left" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color: #ffffff; color: #000000;">
                            <!-- Body content -->
                            <tr>
                                <td class="content-cell" style="text-align: left; padding-left: 0px;">

                                    <img src="https://crm.semytechnology.com{{ $signature }}" style="width: 800px;" alt="">

                                </td>
                            </tr>
                        </table>

                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
</body>
</html>
