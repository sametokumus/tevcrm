@component('mail::message')

    {{$title}}
    <p>{{$message}}</p>
    <p></p>
    <p></p>
    <p></p>
    {{ config('app.name') }}
@endcomponent
