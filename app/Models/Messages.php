<?php

namespace App\Models;

use App\Models\User;
use App\Models\Rooms;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Messages extends Model
{
    use HasFactory;
    protected $table="messages";
    protected $guarded=[];
    public function room()
    {
        return $this->belongsTo(Rooms::class,"room_id");
    }
    public function user_sender()
    {
        return $this->belongsTo(User::class,"user_sender");
    }

}
