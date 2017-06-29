<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Bican\Roles\Models\Role;
use App\Models\Notification;
use App\Models\User;
use Auth;
use DB;
use Input;

class NotificationController extends Controller {





    /**
    *
    *   getNotifications
    *       - Loads the Users Notifications
    *
    *
    *   URL Params:
    *       - limit:                (INT) The Page Limit (Default: 15)
    *       - notificationid:       (INT) The Notification ID to start from
    *       - direction:            (Char) The Query Direction to Load in
    *
    *
    *
    *   Returns (JSON):
    *       1. The Notifications
    *
    **/
    public function getNotifications( $limit , $notificationid = null , $direction = '>' ){

        return [
        
            //The Notifications in the List
            'data'      => Notification::with(['users' => function($query){

                //Only add Users that were Read
                $query->where( 'user_id' , Auth::user()->id );
                $query->where( 'read', 1 );

            }])->whereHas('users' , function($query){

                //Add the User ID
                $query->where( 'user_id' , Auth::user()->id );

            })->where(function( $query ) use ($notificationid , $direction){

                if( $notificationid ){

                    $query->where( 'id' , $direction , $notificationid );

                }

            })->orderBy( 'id' , 'DESC' )->take( $limit )->get(),
        

            //The Total Unread Notifications
            'total'     => Notification::whereHas( 'users' , function( $query ){

                $query->where( 'user_id' , Auth::user()->id );
                $query->where( 'read' , 0 );

            } )->count()
        
        ];

    }





    /**
    *
    *   getNewNotifications
    *       - Loads any new notifications
    *
    *
    *   URL Params:
    *       - notificationid:       (INT) The Notification ID to start from
    *
    *   Returns (JSON):
    *       1. The Notifications
    *
    **/
    public function getNewNotifications( $notificationid = null ){

        return $this->getNotifications( PHP_INT_MAX , $notificationid , '<' );

    }







    /**
    *
    *   getUnreadNotifications
    *       - Loads the Total of Unread Notifications
    *
    *
    *   Params:
    *       n/a
    *
    *
    *   Returns (JSON):
    *       1. The Notifications
    *
    **/
    public function getUnreadTotal(){

        return [

            'total' => Notification::whereHas( 'users' , function( $query ){

                $query->where( 'user_id' , Auth::user()->id );
                $query->where( 'read' , 0 );

            } )->count()

        ];

    }





    /**
    *
    *   readNotification
    *       - Sets the Notification as Read
    *
    *
    *   URL Params:
    *       - notificationid:       (INT) The Notification ID to start from
    *
    *
    *   Returns (JSON):
    *       1. The Notifications
    *
    **/
    public function readNotification( $notificationid ){

        Auth::user()->readNotification( $notificationid );

    }

}
