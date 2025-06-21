<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rooms extends Model
{
    use HasFactory;

    protected $table="rooms";
    protected $guarded=[];
    public function userOne()
    {
        return $this->belongsTo(User::class,"user_one");
    }
    public function userTwo()
    {
        return $this->belongsTo(User::class,"user_two");
    }

}
