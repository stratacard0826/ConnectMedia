<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Bican\Roles\Models\Permission;
use Bican\Roles\Models\Role;
use App\Models\Notification;
use App\Models\SickDay;
use App\Models\User;
use App\Services\CommonService;
use Input;
use Validator;
use Mail;

class SickDayController extends Controller {

    



    /**
    *
    *   getSick
    *       - Loads All of the Sicks
    *
    *   URL Params:
    *       - promotionid:       (INT) The Sick ID to Lookup
    *
    *
    *   Returns (JSON):
    *       1. The Sick Data
    *
    **/
    public function getSickDay( $sickdayid = 1 ){

        return [
            'result' => 1,
            'data'   => SickDay::with([ 'user' , 'store' ])->find( $sickdayid )
        ];

    }   
    










    /**
    *
    *   getAllSicks
    *       - Loads All of the Sicks
    *
    *   URL Params:
    *       - limit:     The Page Limit (Default: 15)
    *       - page:      Pages to Load (Default: 1)
    *
    *
    *   Returns (JSON):
    *       1. The User Looked up (If $userid was passed)
    *       2. The Current User Session Data (If $userid was not passed)
    *       3. Null
    *
    **/
    public function getAllSickDays( $limit = 15 , $page = null ){
        if( $page ){

            return [ 
                'result'    => 1,
                'total'     => SickDay::count() , 
                'data'      => SickDay::with([ 'user' , 'store' ])->take( $limit )->skip( ( ( $page - 1 ) * $limit ) )->get()
            ];

        }else{

            return [
                'result' => 1,
                'data'   => SickDay::with([ 'user' , 'store' ])->all(['id','name'])
            ];

        }
    }    












    /**
    *
    *   addSick
    *       - Create a New Sick
    *
    *   Params ($_PUT):
    *       - name:             (String) The Sick's Name
    *       - description:      (String) The Sick's Description
    *       - permissions:      (Array) The User's Permissions
    *
    *   Returns (JSON):
    *       Returns the [ result => ( 1 = Success, 0 = Failure ) ]
    *
    **/
    public function addSickDay(){  

        $data           = Input::all();
        $validator      = Validator::make( $data, [
            'date'     => 'required|date',
            'user_id'  => 'required|exists:users,id',
            'store_id' => 'required|exists:stores,id',
            'details'  => 'required'
        ]);

        if( $validator->fails() ){

            //Return Failure
            return [ 'result' => 0 , 'errors' => $validator->errors()->all() ];

        }else{

            try {


                //Create the new Sick
                $sickday = SickDay::create( $data );

                //Get the User Data
                $user = $sickday->user()->first();

                //Get the Store Data
                $store = $sickday->store()->first();

                //Send the Email
                $this->send( $sickday , 'Created' );

                //Create the Notification
                $sickday->notifications()->create([

                    'icon'      => 'ambulance',
                    'type'      => 'New Sick Day',
                    'details'   => $store->name . ': ' . $user->firstname . ' ' . $user->lastname,
                    'url'       => '/admin/sick/edit/' . $sickday->id
              
                ])->send([
            
                    'permissions' => [ Permission::where('slug' , 'sickdays.edit' )->first()->id ]
            
                ]);




                //Update the Search Criteria
                $sickday->search([

                    'title'     => $data['date'] . ' - ' . $store->name . ': ' . $user->firstname . ' ' . $user->lastname ,
                    'query'     => $data['details'],
                    'url'       => '/admin/sick/edit/' . $sickday->id

                ])->assign([

                    'permissions' => [ Permission::where('slug' , 'sickdays.edit' )->first()->id ],

                ]);

                //Return Success
                return [ 'result' => 1 ];
    
            } catch(Exception $e ){

                //Return Failure
                return [ 'result' => 0 , 'errors' => [ 'An Unknown Error Occured' ] ];

            }

        }

    } 














    /**
    *
    *   editSick
    *       - Create a New Sick
    *
    *   Params ($_POST):
    *       - name:             (String) The Sick's Name
    *       - description:      (String) The Sick's Description
    *
    *   Returns (JSON):
    *       Returns the [ result => ( 1 = Success, 0 = Failure ) ]
    *
    **/
    public function editSickDay( $sickdayid ){  
        
        $data       = Input::all();
        $validator  = Validator::make( $data, [
            'date'     => 'required|date',
            'user_id'  => 'required|exists:users,id',
            'store_id' => 'required|exists:stores,id',
            'details'  => 'required'
        ]);

        if( $validator->fails() ){

            //Return Failure
            return [ 'result' => 0 , 'errors' => $validator->errors()->all() , 'data' => $data ];

        }else{

            //Get the Sick
            $sickday = SickDay::with([ 'user' , 'store' ])->find( $sickdayid );

            try {

                //Update the Sicks
                $sickday->date         = $data['date'];
                $sickday->user_id      = $data['user_id'];
                $sickday->store_id     = $data['store_id'];
                $sickday->details      = $data['details'];
                $sickday->save();

                //Send the Email
                $this->send( $sickday , 'Updated' );

                //Create the Notification
                $sickday->notifications()->create([

                    'icon'      => 'ambulance',
                    'type'      => 'Sick Day Updated',
                    'details'   => $sickday->store->name . ': ' . $sickday->user->firstname . ' ' . $sickday->user->lastname,
                    'url'       => '/admin/sick/edit/' . $sickday->id
                
                ])->send([
                
                    'permissions' => [ Permission::where('slug' , 'sickdays.edit' )->first()->od ]
                
                ]);


                //Update the Search Criteria
                $sickday->search([

                    'title'     => $data['date'] . ' - ' . $sickday->store->name . ': ' . $sickday->user->firstname . ' ' . $sickday->user->lastname ,
                    'query'     => $data['details'],

                ]);
                

                //Return Success
                return [ 'result' => 1 ];

            } catch( Exception $e ){

                //Return Failures
                return [ 'result' => 0 , 'errors' => [ 'An Unknown Error Occured' ] ];

            }

        }

    }  










    /**
    *
    *   deleteSick
    *       - Delete an Existing User
    *
    *   Params (URL):
    *       - promotionid:                   (String) The Sick ID
    *
    *   Returns (JSON):
    *       1. (Bool) Returns True / False
    *
    **/
    public function deleteSickDay( $sickdayid ){  

        if( $sickday = SickDay::with([ 'user' , 'store' ])->find( $sickdayid ) ){

            //Delete the Search
            $sickday->search()->delete();

            //Clear the Notifications
            $sickday->notifications()->delete();

            //Send Notification
            $sickday->notifications()->create([

                'icon'      => 'ambulance',
                'type'      => 'Sick Day Deleted',
                'details'   => $sickday->store->name . ': ' . $sickday->user->firstname . ' ' . $sickday->user->lastname,
                'url'       => null
           
            ])->send([
           
                'permissions'   => [ Permission::where('slug' , 'sickdays' )->first()->id ]
           
            ]);

            //Delete the Sick
            $sickday->delete();

            //Return Success
            return [ 'result' => 1 ];

        }

        //Return Failure
        return [ 'result' => 0 , 'errors' => [ 'That Sick Day Doesn\'t Exist' ] ];

    }      













    /**
    *
    *   send
    *       - Sends the Sick Day Email
    *
    *   Params:
    *      $sickday:       (Object) The Created / Updated Sick Day
    *      $type:       (String) The Type of Logout Sent
    *           
    *
    *   Returns (JSON):
    *       Returns the [ result => ( 1 = Success, 0 = Failure ) ]
    *
    **/
    private function send( $sickday , $type ){


        $Recipients = User::where(function($query) use ($sickday){

            //Get the recipient roles
            $query->where(function($query){

                //Get all the Roles with Recipient ALL status
                $roles = Role::whereHas( 'permissions' , function($query){

                    $query->where( 'slug' , 'sickdays.recipients.all' );

                })->get()->lists('id');

                if(!empty( $roles )){

                    //Has the Roles
                    $query->whereHas( 'roles' , function($query) use ($roles){

                        $query->whereIn( 'role_id' , $roles );

                    });

                }

            });


            //Get the recipient roles
            $query->orWhere(function($query) use ($sickday){

                //Get the Roels with Recipient Single status
                $roles = Role::whereHas( 'permissions' , function($query){
               
                    $query->where( 'slug' , 'sickdays.recipients.single' );
               
                })->get()->lists('id');

                if(!empty( $roles )){

                    //Has the Store ID
                    $query->whereHas( 'stores' , function($query) use ($roles, $sickday){

                        $query->where( 'store_id' , $sickday->store_id );

                    });

                    //Has the Role ID
                    $query->whereHas( 'roles' , function($query) use ($roles){

                        $query->whereIn( 'role_id' , $roles );

                    });

                }

            });



            //CGet the "All" Role User Permissions
            $query->orWhereHas( 'userPermissions' , function($query){

                //Has Permission with All Recipients
                $query->where( 'slug' , 'sickdays.recipients.all' );

            });


            //Get the Single Role Permission Users
            $query->orWhere(function($query) use ($sickday){

                //Has the Store
                $query->whereHas( 'stores' , function($query) use ($sickday){

                    $query->where( 'store_id' , $sickday->store_id );

                });

                //Has the User Permisison
                $query->whereHas( 'userPermissions' , function($query){

                    $query->where( 'slug' , 'sickdays.recipients.single' );

                });

            });


        })->get();


        /**
        * Get all users including administrators
        */
        $Recipients = CommonService::getUsersForSendingMails($Recipients);

        //Send the Email
        Mail::send([ 'html' => 'emails.sickday' ] , [ 'data' => $sickday , 'type' => $type ] , function($message) use ( $Recipients , $sickday , $type ){

            //Setup Recipients
            foreach( $Recipients as $user ){
                if( $user->email ){

                    $user->fullname = ( !empty( $user->firstname ) && !empty( $user->lastname ) ? $user->firstname . ' ' . $user->lastname : null );

                    $message->to( $user->email , $user->fullname );

                }
            }

            //Set Subject
            $message->subject( env('COMPANY') . ' ' . $type . ' Sick Day (' . $sickday->store->name . ') ' . date( 'M d, Y h:ia' , strtotime( $sickday->submitted ) ) );

        });


    } 





}
