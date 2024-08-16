<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asterisk extends Model
{
    // use HasFactory;

    protected $connection = 'asterisk';
    protected $table = 'user'; 
    protected $fillable = ['phone','email','l_name','f_name','dob','role','sex','user_id','password'];
  
}
