<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketComments extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at','created_at','updated_at'];
    protected $table = 'ticket_comments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ticket_id','comment','images','commented_by','user_role','is_read','created_at','updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at', 
        // 'created_at', 
        // 'updated_at',
        'images'
    ];

    protected $appends = [
                          'comment_images',
                          'user_details',
                        ];

    
    /**
     * Set images attribute
     *
     */
    public function getCommentImagesAttribute()
    {   
        if( $this->images )
            return explode(",",$this->images);
        else
            return $this->images;
    }

    /**
     * Get user
     *
     */
    public function getuserDetailsAttribute()
    {
        if( $this->user_role == 7 ){ //seller
            $seller = Sellers::whereId( $this->commented_by )->first();
            return array('name'=>$seller->name,'role'=>"Seller",'phone_number'=>$seller->phone_number);
        }
        elseif( $this->user_role == 0 ){ //superadmin
            $seller = Admin::whereId( $this->commented_by )->first();
            return array('name'=>$seller->name,'role'=>"SuperAdmin",'phone_number'=>$seller->phone_no);
        }
        else{ //manager
            $seller = User::with('userRole')->whereId( $this->commented_by )->first();
            // dd($seller  );
            return array('name'=>$seller->name,'role'=>$seller->userRole->name,'phone_number'=>$seller->phone_number);
        }
    }

}
