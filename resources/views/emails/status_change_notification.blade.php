@component('mail::message')
    {{-- Greeting --}}
    @if (! empty($greeting))
        # {{ $greeting }}
    @endif

    {{-- Logo --}}
    <img src="{{ $notify_logo }}" style="width: 150px; margin: auto;" alt="">

    {{-- Intro Lines --}}
    @foreach ($introLines as $line)
        {{ $line }}
    @endforeach

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

    {{-- Action Button --}}
    @isset($actionText)
            <?php
            $color = 'primary';
            ?>
        @component('mail::button', ['url' => $actionUrl, 'color' => $color])
            {{ $actionText }}
        @endcomponent
    @endisset

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
