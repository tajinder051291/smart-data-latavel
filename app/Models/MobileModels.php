<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\MobileBrands;


class MobileModels extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
	protected $table = 'mobile_models';
    
    protected $fillable = [
        'id', 'brand_id', 'model', 'ram', 'storage', 'is_active', 'deleted_at', 'created_at', 'updated_at', 'specification', 'color'
    ];

    protected $hidden = [
       'deleted_at', 'created_at', 'updated_at'
    ];

    protected $appends = ['brand_name'];

    /**
     * Get brand name
     *
     */
    public function getBrandNameAttribute()
    {
        return MobileBrands::where('id',$this->brand_id)->where('is_active',1)->pluck('brand_name')['0'];
    }

}
