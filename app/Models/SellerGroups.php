<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Sellers;


class SellerGroups extends Model
{
    use SoftDeletes;
    protected $table = 'seller_groups';
    protected $dates = ['created_at','updated_at','deleted_at'];
    
    protected $hidden = [
        'is_active', 'created_at', 'updated_at','deleted_at','created_by','created_by_role','sellers'
    ];
    
    protected $fillable = [
        'id', 'title', 'image', 'is_active', 'created_at', 'updated_at','created_by','created_by_role', 'sellers','icon_type'
    ];

    protected $appends = [
        'all_sellers','total_sellers'
    ];

    public function sellers()
    {
        return $this->hasMany('App\Models\Sellers','seller_group_id', 'id');
    }

    /**
     * Set sellers attribute
     *
     */
    public function getAllSellersAttribute()
    {   
        if( $this->sellers ){
            $sellers =  explode(",",$this->sellers);

            return Sellers::whereIn('id',$sellers)->select('id','name','address','pincode','is_active')->get();
        }
        else{
            return $this->sellers;
        }
    }

    /**
     * Get sellers count
     *
     */
    public function getTotalSellersAttribute()
    {   
        if( $this->sellers ){
            $sellers =  explode(",",$this->sellers);
            return count($sellers );
        }
        else{
            return 0;
        }
    }
}
