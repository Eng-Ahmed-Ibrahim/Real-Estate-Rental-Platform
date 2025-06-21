<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this->id,
            "title"=>$this->data['message'],
            "body"=>$this->data['message'],
            "model_type"=>"Tracking",
            "user_id"=>"$this->notifiable_id",
            "created_at"=>$this->data['date'],
            "is_read"=>$this->read_at==null ? "0" : "1",

        ];
    }
}
