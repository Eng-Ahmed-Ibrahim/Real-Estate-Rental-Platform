<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;
    protected $table="subscriptions";
    protected $guarded=[];
    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }
    public function package()
    {
        return $this->belongsTo(Packages::class, 'package_id');
    }
    public function method()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
}
