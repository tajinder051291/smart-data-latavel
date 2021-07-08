<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseDetails extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at','created_at','updated_at'];
    protected $table = 'warehouse_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'id', 'order_id','warehouse_images','warehouse_remarks','warehouse_received_quantity','warehouse_received_quality','warehouse_stocks_with_issue'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'warehouse_images',
        'deleted_at', 
        // 'created_at', 'updated_at',
    ];

  
    protected $appends = [ 'warehouse_attachments' ];

    /**
     * Get order
     *
     */
    public function order()
    {
        return $this->hasOne( 'App\Models\Orders', 'id','order_id' );
    }

    /**
     * Set warehouse attachments attribute
     *
     */
    public function getWarehouseAttachmentsAttribute()
    {   
        if( $this->warehouse_images )
            return explode(",",$this->warehouse_images);
        else
            return $this->warehouse_images;
    }
}
