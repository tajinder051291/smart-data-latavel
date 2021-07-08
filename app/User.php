<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'google_id','name', 'email', 'email_verified_at', 'phone_number', 'phone_number_verify', 'total_points', 'pending_points','approved_points', 'is_active', 'password', 'remember_token', 'created_at', 'updated_at', 'OTP','device_type','device_token','security_token','profile_pic','referral_code','is_new','last_login','login_tried','last_phone_edit','edit_tried'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','device_type','device_token','last_login','login_tried','last_phone_edit','edit_tried'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function userAccountDetails()
    {
        return $this->hasMany('App\Models\UsersAccountDetails','user_id', 'id');
    }
}
