<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Connection;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Bican\Roles\Traits\HasRoleAndPermission;
use Bican\Roles\Contracts\HasRoleAndPermission as HasRoleAndPermissionContract;
use App\Traits\HasStoreRelationship;
use App\Contracts\HasStoreRelationship as HasStoreRelationshipContract;
use App\Traits\Search as UserSearch;
use App\Contracts\Search as UserSearchContract;
use DB;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract, 
                                    HasStoreRelationshipContract,
                                    HasRoleAndPermissionContract,
                                    UserSearchContract
{
    use Authenticatable, Authorizable, CanResetPassword, HasStoreRelationship, HasRoleAndPermission, UserSearch {
        HasRoleAndPermission ::can insteadof Authorizable;
    } 


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['firstname', 'lastname', 'dob', 'username', 'email', 'password', 'city', 'province', 'phone'];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];














    /**
    *
    *   readNotification
    *       - Sets the Notification as Read
    *
    *   Params:
    *       $notificationid:        (INT) The Notification ID to Assign as Read
    *
    *   Returns:
    *       n/a
    *
    **/
    public function readNotification( $notificationid ){
        $this->belongsToMany('App\Models\Notification')->updateExistingPivot( $notificationid , [
            'read' => 1
        ] );
    }


    














    /**
    *
    *   notifications
    *       - Loads the Belongs to Many Relationship Attachments
    *
    *   URL Params:
    *       n/a
    *
    *
    *   Returns (Object):
    *       1. The Notification to Create
    *
    **/
    public function notifications(){

        return $this->morphMany('App\Models\Notification', 'link');

    }


















}