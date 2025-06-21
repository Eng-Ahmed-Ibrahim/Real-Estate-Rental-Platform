<?php

namespace App\Models;

use App\Models\User;
use App\Models\Coupon;
use App\Models\Service;
use App\Models\UserMobile;
use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Booking extends Model
{
    protected $guarded=[];
    use HasFactory;

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }
    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }
    public function booking_status()
    {
        return $this->belongsTo(Booking_status::class, 'booking_status_id');
    }

    public function payment_status()
    {
        return $this->belongsTo(PaymentStatus::class, 'payment_status_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }
    public function scopeDaily($query)
    {
        return $query->whereDate('created_at', Carbon::today());
    }

    public function scopeWeekly($query)
    {
        return $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
    }

    public function scopeMonthly($query)
    {
        return $query->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year);
    }
    protected $casts = [
        'end_at' =>   'datetime:  Y-m-d H:i',
        'start_at' => 'datetime:Y-m-d H:i',
    ];
}
