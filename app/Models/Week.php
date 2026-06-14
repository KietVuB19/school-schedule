<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Week extends Model
{
    protected $table = 'weeks';
    protected $fillable = ['name', 'order', 'start', 'end', 'school_id', 'year'];
}
