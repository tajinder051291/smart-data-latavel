<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerDevices extends Model
{
    protected $table = 'seller_devices';
    protected $primaryKey = 'id';
    
    protected $fillable = [
       'seller_id', 'device_type', 'device_token', 'created_at', 'updated_at'
    ];
}
