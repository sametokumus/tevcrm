@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@endif

@if (! empty($message))
    # {{ $message }}
@endif

{{-- Action Button --}}
@isset($actionText)
@component('mail::button', ['url' => $actionUrl, 'color' => 'primary'])
{{ $actionText }}
@endcomponent
@endisset


# Teşekkürler,<br>
{{ config('app.name') }}

{{-- Subcopy --}}
@isset($actionText)
@slot('subcopy')
@lang(
    "Yukarıdaki \":actionText\" butonu ile, yada aşağıdaki URL'i tarayıcınıza yapıştırarak hesabınızı doğrulayabilirsiniz.\n".
    'Tarayıcı Linki:',
    [
        'actionText' => $actionText,
    ]
) <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
@endslot
@endisset
@endcomponent
