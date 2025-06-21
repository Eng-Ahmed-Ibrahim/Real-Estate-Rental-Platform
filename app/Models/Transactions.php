<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    use HasFactory;
    protected $table="transactions";
    protected $guarded=[];
    public function payment()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
