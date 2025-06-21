<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PaymentMethod;
use App\Models\PaymentStatus;
use Carbon\Carbon;

class Payment extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function payment_status()
    {
        return $this->belongsTo(PaymentStatus::class, 'payment_status_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
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
}
