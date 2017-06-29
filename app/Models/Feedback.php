<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Bican\Roles\Models\Permission;
use App\Models\User;
use Auth;
use Mail;

class Feedback extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'feedback';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['link_id', 'link_type', 'user_id', 'relation', 'type', 'message'];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['link_id', 'link_type'];
















    /**
    *
    *   send
    *       - Sends the Feedback Previously Saved
    *
    *   URL Params:
    *       $data:          (Object) The Feedback to Send
    * 			- subject: 			(String) The Feedback Subject to Send
    *           - name:             (String) The Name of the Item receiving Feedback
    *           - relation:         (String) Any additional Associations to add
    *           - type:             (String) The Type of Feedback Sent
    *           - message:          (String) The Feedback Message
    *
    *
    *   Returns (Object):
    *       1. The Recipe Feedback
    *
    **/
    public static function send( $data ){

    	$data = array_merge([
    		'subject' 	=> 'New Feedback',
    		'name' 		=> '',
    		'relation' 	=> '',
    		'type' 		=> '',
    		'message' 	=> ''
    	],$data);

        //Get the Recipients
        $Recipients = User::whereHas( 'roles' , function($query){

            $query->whereHas('permissions',function($query){

                //Add the Query Field
                $query->where( 'permission_id' , Permission::where('slug' , 'feedback.receive' )->first()->id );

            });

        })->get();

        //Send the Email
        Mail::send([ 'html' => 'emails.feedback'] , [ 'data' => $data ] , function($message) use ( $Recipients , $data ){


            //Setup Recipients
            foreach( $Recipients as $user ){
                if( $user->email ){

                    $user->fullname = ( !empty( $user->firstname ) && !empty( $user->lastname ) ? $user->firstname . ' ' . $user->lastname : null );

                    $message->to( $user->email , $user->fullname );

                }
            }

            //Set Subject
            $message->subject( $data['type'] . ' Feedback: ' . $data['subject'] . ' - ' . env('COMPANY') );


        });

   
    }






}
