@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{--{{ config('app.name') }}--}}
{{--            <img src="https://crm.semytechnology.com/img/logo/semy-light2-mail.png" style="width: 250px;" alt="">--}}
{{--            <img src="{{ $notify_logo }}" style="width: 250px;" alt="">--}}
        @endcomponent
    @endslot

    {{-- Body --}}
    {{ $slot }}

    {{-- Subcopy --}}
    @isset($subcopy)
        @slot('subcopy')
            @component('mail::subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endisset

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} {{ config('app.name') }}. @lang('Tüm hakları saklıdır.')
        @endcomponent
    @endslot
@endcomponent
