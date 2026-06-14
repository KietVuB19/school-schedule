<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rollcall extends Model
{
    protected $table = 'rollcalls';
    protected $fillable = ['student_id', 'period_id', 'is_absent'];
}
