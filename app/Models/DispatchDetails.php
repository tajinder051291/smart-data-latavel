<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DispatchDetails extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at','created_at','updated_at'];
    protected $table = 'dispatch_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'id', 'order_id','dispatch_images','dispatch_remarks','dispatch_tracking_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'dispatch_images',
        'deleted_at', 
        // 'created_at', 'updated_at',
    ];

  
    protected $appends = [ 'dispatch_attachments' ];

    /**
     * Get order
     *
     */
    public function order()
    {
        return $this->hasOne( 'App\Models\Orders', 'id','order_id' );
    }

    /**
     * Set dispatch attachments attribute
     *
     */
    public function getDispatchAttachmentsAttribute()
    {   
        if( $this->dispatch_images )
            return explode(",",$this->dispatch_images);
        else
            return $this->dispatch_images;
    }  
}
