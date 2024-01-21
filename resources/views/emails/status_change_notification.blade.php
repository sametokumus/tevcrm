@component('mail::message')

    {{-- Logo --}}
    <img src="{{ $notify_logo }}" style="width: 250px;" alt="">

    {{-- Title --}}
{{--    # {{ $title }}--}}

    {{-- Message --}}
    <p style="font-family: Roboto; font-size: 16px; color: #000000;">{!! $message !!}</p>

    {{-- Action Button --}}
    @isset($actionText)
            <?php
            switch ($level) {
                case 'success':
                case 'error':
                    $color = $level;
                    break;
                default:
                    $color = 'primary';
            }
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
