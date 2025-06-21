<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $table="categories";
    public function service()
    {
        return $this->hasMany(Service::class, 'car_id');
    }
    
     public function subBrand()
    {
        return $this->hasMany(SubBrand::class, 'Brand_id');
    }
}
