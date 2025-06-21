<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceFeature extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function feature()
    {
        return $this->belongsTo(Feature::class, 'feature_id');
    }
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
