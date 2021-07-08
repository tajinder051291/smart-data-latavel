<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;


class Sellers extends Authenticatable
{
    
    use HasApiTokens,Notifiable;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'sellers';
    protected $guard = 'seller';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'id', 'name', 'email', 'password', 'phone_number', 'counry_code', 'aadhaar_number', 'pan_number', 'gst_number', 'address', 'pincode', 'is_verified', 'is_active','aadhaar_front_image','aadhaar_back_image', 'pan_image', 'gst_image','check_number', 'cheque_image', 'deleted_at', 'created_at', 'updated_at','added_by','added_by_role','cheque_number'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    protected $appends = [
                            // 'user_role'
                        ];

    protected $with = [
                            'userRole'
                        ];

     /**
     * Set payment attachment attribute
     *
     */
    // public function getUserRoleAttribute()
    // { 
    //     return '7';
    // }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    public function userRole()
    {
        return $this->hasOne('App\Models\UserRoles','id', 'user_role')->withTrashed();
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    public function orders()
    {
        return $this->hasMany('App\Models\Orders','seller_id', 'id')->with('items');
    } 


    public function user_device_tokens()
    {
        return $this->hasMany('App\Models\SellerDevices','seller_id', 'id');
    }

    
}
