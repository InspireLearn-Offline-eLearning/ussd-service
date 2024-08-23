<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asterisk_UserRequests extends Model
{
    protected $connection = 'asterisk';
    protected $table = 'user_requests'; 
    protected $fillable = ['user_id','request_id','request_type','context','related_id','request_data','approved_by','approved_id','status'];
  
}
