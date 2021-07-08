<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Chat extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at','created_at','updated_at'];
    protected $table = 'chat';
    protected $guard = 'user';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'id', 'user_id', 'connection_id', 'message', 'image','created_at','updated_at','message_time','is_read', 'is_sent' ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [ 'deleted_at','created_at','updated_at' ];

    protected $appends = [ ];


}
