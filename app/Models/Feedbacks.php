<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Users;
use App\Models\Sellers;
use App\Models\Admin;


class Feedbacks extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at','created_at','updated_at'];
    protected $table = 'feedbacks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'id', 'description','image','feedback_rating','submitted_by','submitter_role', 'created_at','updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at', 'created_at', 'updated_at'
    ];

      
    protected $appends = [
            'user_name_role'
    ];

    /**
     * Get user
     *
     */
    public function getuserNameRoleAttribute()
    {
        if( $this->submitter_role == 7 ){ //seller
            $seller = Sellers::whereId( $this->submitted_by )->first();
            return array('name'=>$seller->name,'role'=>"Seller");
        }
        elseif( $this->submitter_role == 0 ){ //superadmin
            $seller = Admin::whereId( $this->submitted_by )->first();
            return array('name'=>$seller->name,'role'=>"SuperAdmin");
        }
        else{ //manager
            $seller = User::with('userRole')->whereId( $this->submitted_by )->first();
            // dd($seller  );
            return array('name'=>$seller->name,'role'=>$seller->userRole->name);
        }
    }


}
