<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserRoles extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
	protected $table = 'user_roles';
    
    protected $fillable = [
        'id', 'app_permission_id', 'name', 'is_active', 'deleted_at', 'created_at', 'updated_at'
    ];

    protected $hidden = [
       'is_active', 'deleted_at', 'created_at', 'updated_at','app_permission_id'
    ];

    public function permissions(){
        return $this->hasMany('App\Models\AppPermissions','offer_id', 'id');
    }
    
}
