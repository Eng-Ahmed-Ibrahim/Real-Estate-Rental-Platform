<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PropertyTypes extends Model
{
    use HasFactory;
    protected $table="property_types";
    protected $guarded=[];
}
