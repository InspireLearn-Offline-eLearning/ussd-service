<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asterisk_ClassRegistration extends Model
{
    
    protected $connection = 'asterisk';
    protected $table = 'class_registration';

    protected $fillable = ['class_reg_id','user_id','class_id',];
}
