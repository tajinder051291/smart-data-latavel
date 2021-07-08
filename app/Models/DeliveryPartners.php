<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryPartners extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
	protected $table = 'delivery_partners';
    
    protected $fillable = [
        'id', 'company_name', 'name', 'phone_number','email','address','counry_code', 'image', 'is_active', 'deleted_at', 'created_at', 'updated_at','added_by','added_by_role'
    ];

}
