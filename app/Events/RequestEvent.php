<?php

namespace App\Events;

use App\Models\Booking;
use App\Models\Service;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RequestEvent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $booking;
    public function __construct(Booking $booking)
    {
        $this->booking=$booking;
    }   
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('request.' . $this->booking->provider_id ),
        ];
    }
    public function broadcastWith(){
        $lang=User::find($this->booking->provider_id)->lang;
        $service=Service::find($this->booking->service_id);
        $customer=User::find($this->booking->customer_id);
        return [
            "id"=>$this->booking->id,
            "service_id"=>$service->id,
            "service_name"=>$lang=="en"?$service->name:$service->name_ar,
            "service_image"=>$service->image,
            "customer_name"=>$customer->name,
            "customer_image"=>$customer->image,
            "amount"=>$this->booking->total_amount,
            "booking_status_id"=>$this->booking->booking_status_id,
            "payment_status_id"=>$this->booking->payment_status_id,
            "payment_type"=>$this->booking->payment_type,
            "overview_time"=>$this->booking->overview_time,
            "overview_time_payment"=>$this->booking->overview_time_payment,
            "start_at"=>$this->booking->start_at,
            "end_at"=>$this->booking->end_at,
            "created_at"=>$this->booking->created_at,
        ];
    }
}
