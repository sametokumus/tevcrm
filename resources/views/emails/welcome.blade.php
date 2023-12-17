@component('mail::message')

    # {{ $title }}

    <p>{!! $message !!}</p>

    <br>
    <br>
    <br>


    {{ config('app.name') }}
@endcomponent
