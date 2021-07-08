<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class States extends Authenticatable
{
    use Notifiable;
	protected $table = 'states';
    
    protected $fillable = [
        'id', 'state', 'country_id', 'isactive', 'created_at', 'updated_at'
    ];

}
