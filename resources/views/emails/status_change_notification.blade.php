@component('mail::message')

    {{-- Logo --}}
    <img src="{{ $notify_logo }}" style="width: 150px; margin: auto;" alt="">

    {{-- Title --}}
    # {{ $title }}

    {{-- Message --}}
{{--    <p style="font-family: Roboto; font-size: 16px; color: #000000;">{!! $message !!}</p>--}}
{{--    {!! $message !!}--}}

{{--    --}}{{-- Intro Lines --}}
{{--    @foreach ($introLines as $line)--}}
{{--        {!! $line !!}--}}

{{--    @endforeach--}}

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
