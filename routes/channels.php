<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\Admin\BroadcastingController;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

//Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//    return (int) $user->id === (int) $id;
//});

Broadcast::channel("company-chat-channel", function () {
    return [
        "message" => $this->message,
        "user" => $this->user
    ];
});

Broadcast::channel("status-channel", function () {
    return [
        "id" => $this->id,
        "title" => $this->title,
        "message" => $this->message,
        "receiver_id" => $this->receiver_id
    ];
});

