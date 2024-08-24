<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asterisk_Courses extends Model
{
    protected $connection = 'asterisk';
    protected $table = 'course'; 
    protected $fillable = ['class_id','course_id','name','joining_key'];
  
    public function class()
    {

        return $this->belongsTo(Asterisk_Classes::class, 'class_id', 'class_id');
    }

    public function conference()
    {
        return $this->hasMany(Asterisk_Conference::class, 'course_id','course_id');
    }
}
