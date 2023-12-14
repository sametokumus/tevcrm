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

//Broadcast::channel("status-channel", function () {
//    return [
//        "id" => $this->id,
//        "title" => $this->title,
//        "message" => $this->message
//    ];
//});
Broadcast::channel('status-channel', function ($user) {
    return [
        'id' => $user->id,
        'title' => $user->title,
        'message' => $user->message,
    ];
});
Broadcast::channel('presence-status-channel', function ($user) {
    return [
        'id' => $user->id,
        'name' => $user->name,
    ];
});

Broadcast::channel('user.{userId}', function ($user, $userId) {
    if ($user->id === $userId) {
        return array('name' => $user->name);
    }
});

// Add auth route
Route::post('/broadcasting/auth', [BroadcastingController::class, 'authenticate']);
