<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use App\Models\Messages;

class MessageSent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Messages $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('room.' . $this->message->room_id);
    }

    public function broadcastAs()
    {
        return 'message-sent';
    }

    public function broadcastWith()
    {
        return [
            "message" => $this->message->message, 
            "user_sender" => $this->message->user_sender, 
            "date" => $this->message->date,
            "time" => $this->message->time,
            "room_id" => $this->message->room_id, 
        ];
    }
}
