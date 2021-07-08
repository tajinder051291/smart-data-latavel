<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class MobileBrands extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
	protected $table = 'mobile_brands';
    
    protected $fillable = [
        'id', 'brand_name', 'is_active', 'deleted_at', 'created_at', 'updated_at'
    ];

    protected $hidden = [
       'deleted_at', 'created_at', 'updated_at'
    ];

    /**
     * Get models
     *
     */
    public function models()
    {
        if( \Auth::user()->user_role == '1' ){
            return $this->hasMany( 'App\Models\MobileModels', 'brand_id','id' );
        }else{
            return $this->hasMany( 'App\Models\MobileModels', 'brand_id','id' )->where('mobile_models.is_active',1)->groupBy('mobile_models.model')->select('mobile_models.model','mobile_models.brand_id','mobile_models.id');
        }
    }

}
