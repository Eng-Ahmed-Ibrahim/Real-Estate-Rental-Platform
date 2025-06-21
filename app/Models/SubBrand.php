<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubBrand extends Model
{
    use HasFactory;

    public function service()
    {
        return $this->hasMany(Service::class, 'sub_id');
    }
    
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }
}
