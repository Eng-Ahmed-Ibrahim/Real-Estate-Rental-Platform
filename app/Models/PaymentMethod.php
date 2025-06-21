<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;
    public $fillable = [
        'name',
        'image',
        'name_ar',
        'account',
        'status',
        'created_at',
    ];
}
