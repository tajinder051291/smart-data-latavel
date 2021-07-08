<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\User;


class UserTeams extends Model
{
    use SoftDeletes;
    
    protected $table = 'user_teams';
    protected $dates = ['created_at','updated_at','deleted_at'];
    
    protected $fillable = [
        'id', 'title', 'image', 'is_active', 'created_at', 'updated_at','members','created_by','created_by_role'
    ];

    protected $hidden = [
        'is_active', 'created_at', 'updated_at','deleted_at','members'
    ];

    protected $appends = [
        'all_members','total_members'
    ];


     /**
     * Set members attribute
     *
     */
    public function getAllMembersAttribute()
    {   
        if( $this->members ){
            $members =  explode(",",$this->members);
            return User::whereIn('id',$members)->select('id','name','state','image','is_active','user_role')->get();
        }
        else{
            return $this->members;
        }
    }

     /**
     * Get members count
     *
     */
    public function getTotalMembersAttribute()
    {   
        if( $this->members ){
            $members =  explode(",",$this->members);

            return count($members );
        }
        else{
            return 0;
        }
    }

}
