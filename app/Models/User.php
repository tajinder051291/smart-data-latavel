<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;

use App\Models\States;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'users';
    protected $guard = 'manager';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'email', 'email_verified_at', 'password', 'remember_token', 'created_at', 'updated_at', 'phone_number', 'country_code', 'user_role', 'country', 'state', 'image', 'app_notifications', 'is_active', 'OTP', 'expiry_time', 'deleted_at', 'phone_verified', 'email_verified',
        'added_by','added_by_role','company_name','address'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'company_name'
    ];


     protected $appends = [
        'state_name',
        // 'user_role_name'
    ];


     protected $with = [
        'userRole',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    public function userRole()
    {
        return $this->hasOne('App\Models\UserRoles','id', 'user_role')->withTrashed();
    } 

    public function states()
    {
        return $this->hasOne('App\Models\States','id', 'state');
    }

    public function user_device_tokens()
    {
        return $this->hasMany('App\Models\UserDevices','user_id', 'id');
    }

    /**
     * Get State name
     *
     */
    public function getStateNameAttribute()
    {   $state = States::where('id',$this->state);
        if( $state->exists() ){
            return States::where('id',$this->state)->pluck('state')[0];
        }
        else{
            return '';
        }
    }

    /**
     * Get user role name
     *
     */
    public function getUserRoleNameAttribute()
    {   
        return $this->hasOne('App\Models\UserRoles','id', 'user_role')->pluck('name')[0];
    }

}
