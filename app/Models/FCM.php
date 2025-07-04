<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FCM extends Model
{
    use HasFactory;
    protected $table="f_c_m_s";
    protected $guarded=[];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
