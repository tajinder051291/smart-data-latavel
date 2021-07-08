<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatConnection extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at','created_at','updated_at'];
    protected $table = 'chat_connection';
    protected $guard = 'user';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'id', 'sender_id', 'receiver_id','connection_id','created_at','updated_at' ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [ 'deleted_at' ];

    protected $appends = [ 'unread_count'];


    /**
     * Get chat
     *
     */
    public function chat()
    {
        return $this->hasMany( 'App\Models\Chat', 'connection_id','connection_id' );
    }

    /**
     * Latest message
     *
     */
    public function latestMessages()
    {
        $user = \Auth::guard('user')->user();
         
        return $this->hasOne( 'App\Models\Chat', 'connection_id','connection_id' )
                    ->orderBy('message_time','DESC')
                    ;
    }

    /**
     * Latest messages count
     *
     */
    public function getUnreadCountAttribute()
    {
        $user = \Auth::guard('user')->user();

        if( $user ){
            return Chat::where('connection_id',$this->connection_id)
                       ->where('user_id','!=',$user->id)
                       ->where('is_read',0)
                       ->count();
        }else{
            return 0;
        }

    }

    /**
     * Sender And Receiver Details
     *
    */

    public function senderDetails()
    {
        return $this->hasOne( 'App\Models\User', 'id','sender_id' );
    }

    public function receiverDetails()
    {
        return $this->hasOne( 'App\Models\User', 'id','receiver_id' );
    }

}
