@component('mail::message')
    {{-- Greeting --}}
    @if (! empty($greeting))
        # {{ $greeting }}
    @endif

    {{-- Logo --}}
    <img src="{{ $notify_logo }}" style="width: 150px; margin: auto;" alt="">


    <p style="font-weight: bold; color: #000000;">{!! $message !!}</p>

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
