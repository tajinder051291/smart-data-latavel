<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faq extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
	protected $table = 'faq';
    
    protected $fillable = [
        'id', 'title', 'description', 'is_active', 'deleted_at', 'created_at', 'updated_at'
    ];

    protected $hidden = [
       'id', 'is_active', 'deleted_at', 'created_at', 'updated_at'
    ];

}
