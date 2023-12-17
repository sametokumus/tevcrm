@component('mail::message')

    # {{ $title }}

    <h3>{!! $message !!}</h3>




    {{ config('app.name') }}
@endcomponent
