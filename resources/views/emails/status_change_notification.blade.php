@component('mail::message')
    {{-- Greeting --}}
    @if (! empty($greeting))
        # {{ $greeting }}
    @endif

    {{-- Logo --}}
    <img src="{{ $notify_logo }}" style="width: 150px; margin: auto;" alt="">
    <p>{!! $message !!}</p>
    <table class="action" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td align="center">
                <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
                    <tr>
                        <td align="center">
                            <table border="0" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td>
                                        <a href="{{ $url }}" class="button button-{{ $color ?? 'primary' }}" target="_blank" rel="noopener">{{ $slot }}</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- Action Button --}}
    @isset($actionText)
        @component('mail::button', ['url' => $actionUrl, 'color' => 'primary'])
            {{ $actionText }}
        @endcomponent
    @endisset

    {{-- Outro Lines --}}
    @foreach ($outroLines as $line)
        {{ $line }}
    @endforeach

    {{-- Salutation --}}
    @if (! empty($salutation))
        {{ $salutation }}
    @else
        {{--        @lang('Regards'),<br>--}}
        {{ config('app.name') }}
    @endif


    {{-- Subcopy --}}
    @isset($actionText)
        @slot('subcopy')
            @lang(
                "Eğer \":actionText\" butonuna tıkladığınızda sorun yaşıyorsanız aşağıdaki linki tarayıcınızın adres çubuğuna yapıştırabilirsiniz.\n".
                'Bağlantı adresi:',
                [
                    'actionText' => $actionText,
                ]
            ) <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
        @endslot
    @endisset
@endcomponent
