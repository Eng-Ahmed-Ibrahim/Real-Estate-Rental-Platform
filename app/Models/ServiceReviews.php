<?php

namespace App\Models;

use App\Models\Categories;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceReviews extends Model
{
    use HasFactory;

    protected $guarded=[];
    protected $table='service_reviews';
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
    
    public function category()
    {
        return $this->belongsTo(Categories::class, 'brand_id');
    }

}
