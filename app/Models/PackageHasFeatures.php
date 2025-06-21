<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageHasFeatures extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $table="package_has_features";
    public function package()
    {
        return $this->belongsTo(Packages::class, 'package_id');
    }
    public function feature()
    {
        return $this->belongsTo(PackageFeatures::class, 'feature_id');
    }
}
