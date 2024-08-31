<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asterisk_Languages extends Model
{
    protected $connection = 'asterisk';
    protected $table = 'language'; 
    protected $fillable = ['name','code'];
  
}
