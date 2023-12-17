@component('mail::message')

    # {{ $title }}

    {!! $message !!}




    {{ config('app.name') }}
@endcomponent
