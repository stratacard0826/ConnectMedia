<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\CommonService;
use Validator;
use Input;
use Mail;
use Auth;
use View;

class EmailrequestController extends Controller
{
    /**
    *
    *   send
    *       - Send an email request
    *
    *   Params ($_PUT):
    *       - message:              (String) The sending message
    *       - type:                 (String) The type of sending request
    *       - permissions:          (Array) The Permissions of New email request
    *
    *   Returns (JSON):
    *       Returns the [ result => ( 1 = Success, 0 = Failure ) ]
    *
    **/
    public function send(){  
        $data           = Input::all();
        $validator      = Validator::make( $data, [
            'type'                      => 'required|filled',
            'message'                   => 'required|filled'
        ]);

        if( $validator->fails() ){

            //Return Failure
            return [ 'result' => 0 , 'errors' => $validator->errors()->all() ];

        }else{
            try {
                    if(!$data['type']){
                        $subject = "New Email Request";
                    }else{
                        $subject = "New Password Change Request";
                    }
                    
                    $data['user'] = Auth::user();
                    
                    $emails = array('corey@wishmedia.ca', 'alex@newbalancevancouver.ca');
                    /**
                    * Get all emails including administrators
                    */
                    $emails = CommonService::getEmailsForSendingMails($emails);

                    //Send the Email
                    Mail::send([ 'html' => 'emails.emailrequest'] , [ 'data' => $data , 'subject' => $subject ] , function($message) use ( $data , $subject, $emails ){
                        foreach($emails as $email){
                            if($email){
                                $message->to( $email );
                            }
                        }

                        // Set Subject
                        $message->subject( $subject );

                    });

                //Return Success
                return [ 'result' => 1 ];
                
                
                
            } catch(Exception $e ){

                //Return Failure
                return [ 'result' => 0 , 'errors' => [ 'An Unknown Error Occured' ] ];
            
            }
        }
    }
}
