<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asterisk_Conference extends Model
{

    protected $connection = 'asterisk';
    protected $table = 'conference';
    protected $casts = [
        'schedule' => 'datetime',
    ];
    
    protected $fillable = ['conference_id','organiser_id','schedule','course_id','class_id'];
    public function course()
    {
        return $this->belongsTo(Asterisk_Courses::class, 'course_id','course_id');
    }
    
 
}
