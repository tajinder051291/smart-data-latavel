<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class OrderItems extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at','created_at','updated_at'];
    protected $table = 'order_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'id', 'order_id','brand_id','model_id','quantity','price','is_item_active','is_made_in_india','negotiated_quantity','negotiated_price','negotiated_by','negotiated_by_role',
         'negotiation_date', 'created_at','updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at', 
        'created_at', 'updated_at',
        // 'negotiated_quantity','negotiated_price','negotiated_by','negotiated_by_role',
        // 'negotiation_date',
    ];

  
    protected $appends = [
            'item_detail','item_brand','delivery_method'
    ];

    /**
     * Get order
     *
     */
    public function order()
    {
        return $this->hasOne( 'App\Models\Orders', 'id','order_id' );
    }

    /**
     * Get item detail
     *
     */
    public function getItemDetailAttribute()
    {
        return MobileModels::where( 'id',$this->model_id )->first();
    }

    /**
     * Get item Brand
     *
     */
    public function getItemBrandAttribute()
    {
        return MobileBrands::where('id',$this->brand_id)->pluck('brand_name')['0'];
    }

    /**
     * Get delivery method 
     *
     */
    public function getDeliveryMethodAttribute()
    {
        return Orders::where('id',$this->order_id)->pluck('delivery_method')[0];
    }

}
