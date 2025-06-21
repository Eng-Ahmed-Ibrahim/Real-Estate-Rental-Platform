<?php

namespace App\Models;

use App\Models\ServiceReviews;
use App\Models\ServiceEventDays;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $appends = ['eventDays'];

    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function eventDays()
    {
        return $this->hasMany(ServiceEventDays::class, 'service_id');
    }
    // Accessor to rename the attribute
    public function getEventDaysAttribute()
    {
        return $this->eventDays()->get();
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function car()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }

    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }

    public function features()
    {
        return $this->hasMany(ServiceFeature::class, 'service_id');
    }

    public function files()
    {
        return $this->hasMany(ServiceFile::class, 'service_id');
    }

    public function dates()
    {
        return $this->hasMany(Date::class, 'service_id');
    }

    public function gallery()
    {
        return $this->hasMany(ServiceGallery::class, 'service_id');
    }

    public function booking()
    {
        return $this->hasMany(Booking::class, 'service_id');
    }


    public function review()
    {
        return $this->hasMany(ServiceReviews::class, 'service_id');
    }

    public function getRateAttribute()
    {
        return floor(ServiceReviews::query()->where('service_id', $this->id)->avg('rating'));
        // return $this->review->sum('rating') / $this->review->count();
    }
}
