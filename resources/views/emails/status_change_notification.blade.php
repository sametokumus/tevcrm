@component('mail::message')

    <table class="action" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td align="center">
                <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
                    <tr>
                        <td align="center">
                            <table border="0" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td style="text-align: center;">
                                        <img src="{{ $notify_logo }}" style="width: 150px; margin-bottom: 30px;" alt="">
                                        <p style="color: #2d3748;">{!! $message !!}</p>
                                        <a href="{{ $actionUrl }}" class="button button-primary" target="_blank" rel="noopener">{{ $actionText }}</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

@endcomponent
