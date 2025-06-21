<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Commission;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class User extends Authenticatable 
{
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use HasApiTokens;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    //protected $fillable = ['power'];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'power' => 'string',
    ];




    public function getUserAvatar()
    {
        if ($this->avatar == null)
            return env('DEFAULT_IMAGE_AVATAR');
        else
            return env('STORAGE_URL') . '/uploads/users/' . $this->avatar;
    }

    public function scopeWithoutTimestamps()
    {
        $this->timestamps = false;
        return $this;
    }



    public function service()
    {
        return $this->hasMany(Service::class, 'user_id')->latest();
    }
    public function reviews()
    {
        return $this->hasMany(ServiceReviews::class, 'provider_id');
    }
    public function earning()
    {
        return $this->hasMany(WithdrawEarning::class, 'user_id');
    }

    public function driver_license()
    {
        return $this->hasMany(Driver_license::class, 'user_id');
    }
    public function commission()
    {
        return $this->hasMany(Commission::class, 'provider_id');
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
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
