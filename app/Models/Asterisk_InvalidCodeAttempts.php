<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asterisk_InvalidCodeAttempts extends Model
{
   
    protected $connection = 'asterisk';
    protected $table = 'invalid_code_attempts'; 
    protected $fillable = ['attempt_id','phone','code'];
}
