<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignUsers extends Model
{
    use HasFactory;
    protected $table="assign_users";
    protected $guarded=[];
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }
}
