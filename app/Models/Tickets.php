<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\TicketComments;

class Tickets extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at','created_at','updated_at'];
    protected $table = 'tickets';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','subject','description','images','user_id','user_role','have_comments','is_read','is_active','is_admin','created_at','updated_at','order_id'
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
                            'query_images',
                            'user_details',
                            'unread_seller_comments_count',
                            'unread_user_comments_count',
                        ];

    /**
     * Set images attribute
     *
     */
    public function getQueryImagesAttribute()
    {   
        if( $this->images )
            return explode(",",$this->images);
        else
            return $this->images;
    }

    /**
     * Get query comments
     *
     */
    public function comments()
    {
        return $this->hasMany( 'App\Models\TicketComments', 'ticket_id','id' );
    }

    /**
     * Get user
     *
     */
    public function getuserDetailsAttribute()
    {
        if( $this->user_role == 7 ){ //seller
            $seller = Sellers::whereId( $this->user_id )->first();
            return array('name'=>$seller->name,'role'=>"Seller",'phone_number'=>$seller->phone_number);
        }
        elseif( $this->user_role == 0 ){ //superadmin
            $seller = Admin::whereId( $this->user_id )->first();
            return array('name'=>$seller->name,'role'=>"SuperAdmin",'phone_number'=>$seller->phone_no);
        }
        else{ //manager
            $seller = User::with('userRole')->whereId( $this->user_id )->first();
            // dd($seller  );
            return array('name'=>$seller->name,'role'=>$seller->userRole->name,'phone_number'=>$seller->phone_number);
        }
    }


    /**
     * Unread seller comments count attribute
     *
     */
    public function getUnreadSellerCommentsCountAttribute()
    {   
       return TicketComments::where('ticket_id','=',$this->id)->where('user_role','=','7')->where('is_read','=','0')->count();
    }

    /**
     * Unread user comments count attribute
     *
     */
    public function getUnreadUserCommentsCountAttribute()
    {   
       return TicketComments::where('ticket_id','=',$this->id)->where('user_role','!=','7')->where('is_read','=','0')->count();
    }

}
