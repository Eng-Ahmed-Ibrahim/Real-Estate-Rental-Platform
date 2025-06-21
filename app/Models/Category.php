<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Exercise;

class Category extends Model
{
    use HasFactory;

    public function exercises()
    {
        return $this->belongsToMany(Exercise::class, 'category_exercise');
    }
}
