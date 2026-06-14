<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    protected $table = 'periods';
    protected $fillable = ['schedule_id', 'class_id', 'subject_id', 'day', 'session', 'lesson_name', 'status'];
}
