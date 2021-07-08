<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Orders extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at','created_at','updated_at'];
    protected $table = 'orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'id', 'seller_id','delivery_method','stock_availablity','accepted_by','accepted_by_role','accepted_date','order_status','due_date',
         'created_at','updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at', 
        // 'created_at', 'updated_at'
    ];

    /*protected $appends = [
        'items_by_order_and_brand'
    ]; */

    /**
     * Get items by brand and order
     *
     */
/*    public function getItemsByOrderAndBrandAttribute()
    {
        $items =  OrderItems::where('order_id',$this->id)
                            ->get();
        return $items->groupBy('brand_id')->values();
        
    } */

    /**
     * Get order items
     *
     */
    public function seller()
    {
        return $this->hasone( 'App\Models\Sellers', 'id','seller_id' );
    }

    /**
     * Get order items
     *
     */
    public function items()
    {
        return $this->hasmany( 'App\Models\OrderItems', 'order_id','id' )
                    // ->groupBy('brand_id')
                    ;
    }

    /**
     * Get order attachments
     *
     */
    public function attachments()
    {
        return $this->hasmany( 'App\Models\OrderAttachments', 'order_id','id' );
    }

    /**
     * Get order invoices
     *
     */
    public function invoices()
    {
        return $this->hasmany( 'App\Models\OrderInvoices', 'order_id','id' );
    }

    /**
     * Get order pickup details
     *
     */
    public function pickupdetails()
    {
        return $this->hasone( 'App\Models\PickupDetails', 'order_id','id' );
    }

    /**
     * Get order dispatch details
     *
     */
    public function dispatchdetails()
    {
        return $this->hasone( 'App\Models\DispatchDetails', 'order_id','id' );
    }

    /**
     * Get order warehouse details
     *
     */
    public function warehousedetails()
    {
        return $this->hasone( 'App\Models\WarehouseDetails', 'order_id','id' );
    }

}
