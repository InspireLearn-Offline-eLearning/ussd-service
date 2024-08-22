<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asterisk_Schools extends Model
{
    protected $connection = 'asterisk';
    protected $table = 'school'; 
    protected $fillable = ['school_id','name'];
}
