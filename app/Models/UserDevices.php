<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDevices extends Model
{
    protected $table = 'user_devices';
    protected $primaryKey = 'id';
    
    protected $fillable = [
       'id', 'user_id', 'device_type', 'device_token', 'created_at', 'updated_at'
    ];
}
