<?php

namespace App\Services;

use Bican\Roles\Models\Role;
use App\Models\User;

/**
* Service for global common functions
*/

class CommonService
{

    public function __construct($ip = null)
    {
    }

    /**
    * Get user list for sending mail
    * 
    * @param mixed $users: sending users except admin
    */
    public static function getUsersForSendingMails($users)
    {
        $Recipients = User::whereHas('roles', function($query) {
            // get users to have administrator role
            $query->where( 'roles.id' , 1);
        })->get();
        
        foreach($Recipients as $Recipient){
            $email = $Recipient->email;
            if($email){
                $exist = false;
                foreach($users as $user){
                    if($email = $user->email){
                        $exist = true;
                        break;
                    }
                }
                if(!$exist){
                    $users[] = $Recipient;
                }
            }
        }
        
        return $users;
    }

    /**
    * Get email list for sending mail
    * 
    * @param mixed $users: sending emails except admin
    */
    public static function getEmailsForSendingMails($emails)
    {
        $Recipients = User::whereHas('roles', function($query) {
            // get users to have administrator role
            $query->where( 'roles.id' , 1);
        })->get();
        
        foreach($Recipients as $Recipient){
            $email = $Recipient->email;
            if($email && !in_array($email, $emails)){
                $emails[] = $email;
            }
        }
        
        return $emails;
    }

}