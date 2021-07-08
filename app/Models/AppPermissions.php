<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppPermissions extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
	protected $table = 'app_permissions';
    
    protected $fillable = [
        'id', 'name', 'is_active', 'deleted_at', 'created_at', 'updated_at'
    ];

}
