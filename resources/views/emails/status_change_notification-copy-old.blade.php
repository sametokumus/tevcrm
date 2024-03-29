@component('mail::message')
    {{-- Greeting --}}
    @if (! empty($greeting))
        # {{ $greeting }}
    @endif

    {{-- Logo --}}
    <img src="{{ $notify_logo }}" style="width: 250px;" alt="">

    {{-- Title --}}
{{--    # {{ $title }}--}}

    {{-- Message --}}
    {!! $message !!}

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
