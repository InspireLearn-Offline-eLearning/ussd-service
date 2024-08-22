<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asterisk_Classes extends Model
{
    protected $connection = 'asterisk';
    protected $table = 'class';
    protected $fillable = ['class_id', 'school_id', 'name'];
    public function school()
    {
        return $this->belongsTo(Asterisk_Schools::class, 'school_id', 'school_id');
    }
}
