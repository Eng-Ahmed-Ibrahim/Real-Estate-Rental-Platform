<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CronjobExpression extends Model
{
    use HasFactory;
    protected $table='cronjob_expressions';
    protected $guarded =[];
}
