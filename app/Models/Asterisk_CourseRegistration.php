<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asterisk_CourseRegistration extends Model
{
    protected $connection = 'asterisk';
    protected $table = 'course_registration';
    protected $fillable = ['course_reg_id','user_id','course_id','role'];
}
