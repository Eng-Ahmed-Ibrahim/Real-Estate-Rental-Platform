<?php

use App\Models\Booking;
use App\Models\Rooms;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('test-channel', function ($user) {
    // Only allow the user with ID 1 to listen to this channel s
    return true;
});


Broadcast::channel('room.{room}', function ($user, Rooms $room) {
    return $room && ($user->id === $room->user_one || $user->id === $room->user_two);
});
Broadcast::channel("request.{providerId}",function($user, $providerId){
    return $user->id === (int) $providerId ;

});