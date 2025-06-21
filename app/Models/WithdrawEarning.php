<?php

namespace App\Models;

use App\Models\User;
use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WithdrawEarning extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
}
