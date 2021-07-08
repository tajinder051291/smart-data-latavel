<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PickupDetails extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at','created_at','updated_at'];
    protected $table = 'pickup_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'id', 'order_id','pickup_by','pickup_by_role','pickup_type','pickup_images','pickup_remarks'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'pickup_images',
        'deleted_at', 
        // 'created_at', 'updated_at',
    ];

  
    protected $appends = [ 'pickup_attachments' ,'pickup_user_details'];

    /**
     * Get order
     *
     */
    public function order()
    {
        return $this->hasOne( 'App\Models\Orders', 'id','order_id' );
    }

    /**
     * Set pickup attachments attribute
     *
     */
    public function getPickupAttachmentsAttribute()
    {
        // dd($this->id);
        if( $this->pickup_images )
            return explode(",",$this->pickup_images);
        else
            return $this->pickup_images;
    }

    /**
     * Set pickup user details attribute
     *
     */
    public function getPickupUserDetailsAttribute()
    {   
        return \App\Models\User::where('id',$this->pickup_by)->first();
    }  

}
