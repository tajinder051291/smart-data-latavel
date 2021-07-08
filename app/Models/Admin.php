<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
	protected $table = 'admins';
    protected $guard = 'admin';

    protected $fillable = [
        'id', 'name', 'email','password','email_verified_at', 'created_at', 'updated_at', 'phone_no', 'country_code', 'user_role', 'country', 'state', 'image', 'app_notifications', 'is_active', 'OTP', 'expiry_time', 'phone_verified', 'email_verified', 'deleted_at'
    ];

    protected $hidden = [
        'password'
    ];
    
}
