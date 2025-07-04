<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Packages extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $table="packages";
    public function features()
    {
        return $this->hasMany(PackageHasFeatures::class, 'package_id');
    }
}
