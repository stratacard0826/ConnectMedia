<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use App\Models\Attachment;
use App\Models\Event;
use App\Models\Store;
use App\Models\Reminder;
use App\Models\User;
use App\Models\Notification;
use Carbon\Carbon;
use App\Services\CommonService;
use Input;
use Auth;
use Validator;
use Mail;

class EventController extends Controller {









    /**
    *
    *   getAllEvents
    *       - Loads All of the Events
    *
    *   URL Params:
    *       - limit:     (INT) The Page Limit (Default: 15)
    *       - page:      (INT) Pages to Load (Default: 1)
    *
    *
    *   Returns (JSON):
    *       1. The News
    *
    **/
    public function getAllEvents( $limit = 15 , $page = 1 ){

        //Load the Events
        $events = Event::with([ 'roles', 'stores', 'author' ])->take( $limit )->skip( ( ( $page - 1 ) * $limit ) )->get();

        foreach( $events as $index => $event ){

            //Prepare & Get the Author
            $permissions                = [ true , true ];
            $events[ $index ]->user     = $event->author;


            //If the User doesn't have Edit Permissions, Ensure he User has the correct Roles / Stores
            if( !Auth::user()->hasPermission( 'event.edit' ) ){

                //Check the Roles
                if( !empty( $event->roles ) ){

                    $permissions[0] = Auth::user()->isOne( array_column( $event->roles->toArray() , 'id' ) );

                }

                //Check the Stores
                if( !empty( $event->stores ) ){

                    $permissions[1] = Auth::user()->inStores( array_column( $event->stores->toArray() , 'id' ) );

                }

            }


            //If we have the Permissions or the User created the article
            if( $permissions != [ true , true ] && $event->author->id != Auth::user()->id ){

                //Set the Article & Summary to Hidden
                $events[ $index ]->name       = '** Hidden **';
                $events[ $index ]->details    = null;

            }

        }

        //Return the Data
        return [ 
            'total' => Event::count() , 
            'data'  => $events
        ];

    }  

    /**
    *
    *   getUserEvents
    *       - Outputs the Events for the Calendar
    *
    *   GET Params:
    *       - start:     (Date) Get Events Starting at Date
    *       - end:       (Date) Get Events Ending at Date
    *
    *
    *   Returns (JSON):
    *       1. The News
    *
    **/
    public function getUserEvents(){

        //Load the User Data
        $events = Event::with([ 'author' ])->where(function($query){

            $query->where(function($query){

                //Sort by Roles
                $query->where(function($query){

                    $roles = Auth::user()->roles()->get()->lists('id');

                    $query->whereDoesntHave('roles');

                    if( count( $roles ) > 0 ){

                        $query->orWhereHas('roles',function($query) use ($roles) {

                            $query->whereIn('role_id', $roles );

                        });

                    }


                });


                //Sort by Stores
                $query->where(function($query){

                    $stores = Auth::user()->stores()->get()->lists('id');

                    $query->whereDoesntHave('stores');

                    if( count( $stores ) > 0 ){

                        $query->orWhereHas('stores',function($query) use ($stores) {

                            $query->whereIn('store_id' , $stores );

                        });

                    }

                });

            });


            //Return all user Posts
            $query->orWhere( 'user_id' , Auth::user()->id );


        })->where(function($query){


            //Sort by the Start Date
            if( Input::get('start') ){

                $query->where( 'start' , '>' , Input::get('start') );

            }


            //Sort by the End Date
            if( Input::get('end') ){

                $query->where( 'end' , '<' , Input::get('end') );

            }

        });


        $list = $events->get();

        foreach( $list as $key => $value ){
            $list[ $key ] = [
                'id'    => $value['id'],
                'title' => $value['name'],
                'start' => $value['start'],
                'end'   => $value['end']
            ];
        }



        //Return the Data
        return $list;


    }  
    
    /**
    *
    *   attach
    *       - Attaches a File
    *
    *   Request Params:
    *       file:       (FILE) The File to Attach     
    *
    *
    *   Returns (JSON):
    *       1. The News
    *
    **/
    public function attach(){
        if( Input::file('file') ){

            return [ 'result' => 1 , 'attachment_id' => Attachment::store( Input::file('file') )->id ];

        }

        return [ 'result' => 0 , 'error' => 'Invalid File Uploaded' , 'code' => 'invalid-file' ];
    
    }
    
    /**
    *
    *   getArticle
    *       - Loads a Single News Article
    *
    *   URL Params:
    *       - id:        (INT) The Article ID to Load
    *
    *
    *   Returns (JSON):
    *       1. The Event
    *
    **/
    public function getEvent( $id ){
        //Load the Article Data
        $event          = Event::with(['roles', 'stores', 'author', 'attachments'])->find( $id );
        $permissions    = [ true , true ];

        //If the User doesn't have Edit Permissions, Ensure he User has the correct Roles / Stores
        if( !Auth::user()->hasPermission( 'events.edit' ) && $event->author->id != Auth::user()->id ){

            //Check the Roles
            if( !empty( $event->roles ) ){

                $permissions[0] = Auth::user()->isOne( array_column( $event->roles->toArray() , 'id' ) );

            }

            //Check the Stores
            if( !empty( $event->stores ) ){

                $permissions[1] = Auth::user()->inStores( array_column( $event->stores->toArray() , 'id' ) );

            }

        }

        //If we have the Permissions or the User created the event
        if( $event && $permissions == [ true , true ] ){

            //Return the Data
            return [ 'result' => 1 , 'data' => [
                'id'            => $event->id,
                'author'        => $event->author,
                'name'          => $event->name,
                'details'       => $event->details,
                'start'         => $event->start,
                'end'           => $event->end,
                'stores'        => $event->stores,
                'roles'         => $event->roles,
                'attachments'   => $event->attachments,
                'reminders'     => $event->reminders,
                'created'       => $event->created_at->format( \Config::get('settings.timestamp')->long ),
            ] ];

        }

        //Return Failed
        return [ 'result' => 0 , 'error' => 'Event not found' , 'code' => 'not-found' ];

    }  



    /**
    *
    *   addEvent
    *       - Create a New Event
    *
    *   Params ($_PUT):
    *       - stores:          (String) The Stores who will receive the event
    *       - roles:           (String) The Roles who will receive the event
    *       - start:           (Date String) The Date the Event should Start at
    *       - end:             (Date String) The Date the Event should End at
    *       - details:         (String) The Details of the Event
    *
    *   Returns (JSON):
    *       Returns the [ result => ( 1 = Success, 0 = Failure ) ]
    *
    **/
    public function addEvent(){  

        $attachments    = Input::get('files');
        $data           = array_merge([
            'stores'        => [],
            'roles'         => [],
            'today'         => date('Y-m-d H:i:s'),
            'start'         => '',
            'end'           => '',
            'name'          => '',
            'details'       => '',
            'reminders'       => '',
        ],Input::all());
        $validator      = Validator::make( $data, [
            'start'        => 'required|before:' . Input::get('end') . '|after:' . $data['today'],
            'end'          => 'required|after:' . Input::get('start'),
            'name'         => 'required|min:1|max:100',
            'details'      => 'required',
            'sendemail'    => 'required|integer'
        ]);

        if( $validator->fails() ){

            //Validator Failed, Return
            return [ 'result' => 0 , 'errors' => $validator->errors()->all() ];

        }else{

            try {

                //Insert the News Article
                $event = Event::create([
                    'user_id'   => Auth::user()->id,
                    'name'      => $data['name'],
                    'details'   => $data['details'],
                    'start'     => $data['start'],
                    'end'       => $data['end'],
                ]);


                //Assign Permissions
                if( !empty( $data['roles'] ) ){

                    foreach( Role::find( $data['roles'] ) as $role ){
          
                        $event->attachRole( $role );

                    }

                }


                //Assign Stores
                if( !empty( $data['stores'] ) ){

                    foreach( Store::find( $data['stores'] ) as $store ){
                     
                        $event->attachStore( $store );

                    }

                }
                //Assign Reminders
                if( !empty( $data['reminders'] ) ){

                    foreach( $data['reminders'] as $reminder ){
                        // the type is not "role", set role_id to 0
                        $reminder['role_id'] = $reminder['type'] != "role" ? 0 : $reminder['role_id'];
                     
                        Reminder::create([
                            'item_id'       => $event->id,
                            'timecount'     => $reminder['timecount'],
                            'type'          => $reminder['type'],
                            'role_id'       => isset($reminder['role_id']) ? $reminder['role_id'] : 0,
                            'period'        => $reminder['period'],
                            'module_name'   => "event",
                        ]);
                        
                    }

                }


                //Get the Attachments
                if( count( $attachments ) > 0 ){

                    foreach( $attachments as $key => $file ){
                        if(!empty( $file['attachment_id'] )){

                            $attachments[ $key ] = $file['attachment_id'];

                            $event->attachFile( $file['attachment_id'] );
                    
                        }
                    }

                    $attachments = Attachment::grab($attachments);

                }


                //Only if we want to email it.
                if( Input::get('sendemail') ){


                    if( !empty( $data['stores'] ) || !empty( $data['roles'] ) ){

                        $Recipients = User::where(function($query) use ($data) {

                            if( !empty( $data['stores'] ) ){

                                $query->whereHas( 'stores' , function($query) use ($data) {

                                    $stores = [];

                                    array_walk( $data['stores'] , function(&$item) use (&$stores) {
                                        if( !empty($item['id']) ){

                                            $stores[] = $item['id'];

                                        }
                                    });

                                    $query->whereIn( 'store_id' , $stores );
                                
                                });

                            }

                            if( !empty( $data['roles'] ) ){

                                $query->whereHas( 'roles' , function($query) use ($data) {

                                    $roles = [];

                                    array_walk( $data['roles'] , function(&$item) use (&$roles) {
                                        if( !empty($item['id']) ){

                                            $roles[] = $item['id'];

                                        }
                                    });

                                    $query->whereIn( 'role_id' , $roles );

                                });

                            }

                        })->get();

                    }else{

                        $Recipients = User::all();

                    }
                    /**
                    * Get all users including administrators
                    */
                    $Recipients = CommonService::getUsersForSendingMails($Recipients);

                    //Send the Email
                    Mail::send([ 'html' => 'emails.default'] , [ 'body' => $data['details'] , 'type' => 'New Event', 'subject' => '<p style="margin-top:0;">' . $data['name'] . '</p> <p>' . date('M d, Y' , strtotime( $data['start'] ) ) . ' to ' . date('M d, Y' , strtotime( $data['end'] ) ) . '</p>' ] , function($message) use ( $data , $Recipients , $attachments ){

                        //Setup Recipients
                        foreach( $Recipients as $user ){
                            if( $user->email ){

                                $user->fullname = ( !empty( $user->firstname ) && !empty( $user->lastname ) ? $user->firstname . ' ' . $user->lastname : null );

                                $message->to( $user->email , $user->fullname );

                            }
                        }

                        //Set Subject
                        $message->subject( 'New Event: ' . $data['name'] . ' - ' . env('COMPANY') );

                        //Attach the Attachments
                        if( count( $attachments ) > 0 ){
                            foreach( $attachments as $attachment ){
                         
                                $message->attach( $attachment->file );
                         
                            }
                        }

                    });

                }


                //Create the Event
                $event->notifications()->create(array(

                    'icon'      => 'calendar',
                    'type'      => 'New Event',
                    'details'   => $data['name'],
                    'url'       => '/admin/events/view/' . $event->id

                ))->send([

                    'permissions' => [ Permission::where('slug' , 'events.view' )->first()->id ],
                    'roles'       => $data['roles'],
                    'stores'      => $data['stores']

                ]);



                //Create the Search Criteria
                $event->search([

                    'title' => $data['name'],
                    'query' => strip_tags( $data['details'] ),
                    'url'   => '/admin/events/view/' . $event->id

                ])->assign([

                    'permissions' => [ Permission::where('slug' , 'events.view' )->first()->id ],
                    'roles'       => $data['roles'],
                    'stores'      => $data['stores']

                ]);


                //Return Success
                return [ 'result' => 1 , 'id' => $event->id ];
    
            } catch(Exception $e ){

                //Return Failure
                return [ 'result' => 0 , 'errors' => [ 'An Unknown Error Occured' ] ];

            }

        }

    } 


    /**
    *
    *   editEvent
    *       - Updates a Event
    *
    *   URL Params:
    *       - eventid:          (INT) The Event to Update
    *
    *   Params ($_POST):
    *       - stores:          (String) The Stores who will receive the event
    *       - roles:           (String) The Roles who will receive the event
    *       - start:           (Date String) The Date the Event should Start at
    *       - end:             (Date String) The Date the Event should End at
    *       - details:         (String) The Details of the Event
    *
    *   Returns (JSON):
    *       Returns the [ result => ( 1 = Success, 0 = Failure ) ]
    *
    **/
    public function editEvent( $eventid ){  
        $attachments    = Input::get('files');
        $data           = array_merge([
            'stores'        => [],
            'roles'         => [],
            'today'         => date('Y-m-d H:i:s'),
            'start'         => '',
            'end'           => '',
            'name'          => '',
            'details'       => '',
        ],Input::all());
        $validator      = Validator::make( $data, [
            'id'           => 'required|integer|exists:event',
            'start'        => 'required|before:' . Input::get('end'),
            'end'          => 'required|after:' . Input::get('start'),
            'name'         => 'required|min:1|max:100',
            'details'      => 'required',
            'sendemail'    => 'required|integer'
        ]);

        if( $validator->fails() ){

            //Validator Failed, Return Failure
            return [ 'result' => 0 , 'errors' => $validator->errors()->all() ];

        }else{
            
            //Get the Article    
            $event = Event::find( $eventid );

            //Validate the After Date
            if( strtotime( $event->start ) != strtotime( $data['start'] ) && strtotime( $data['start'] ) < strtotime( $data['today'] ) ){

                //Validator Failed, Return Failure
                return [ 'result' => 0 , 'errors' => [ 'Start Date must begin after ' . $data['today'] ] ];

            }

            try {

                //Update the News Article
                $event->start    = $data['start'];
                $event->end      = $data['end'];
                $event->name     = $data['name'];
                $event->details  = $data['details'];
                $event->save();

                //Empty the Roles
                $event->detachAllRoles();

                //Assign Permissions
                if( !empty( $data['roles'] ) ){

                    foreach( Role::find( $data['roles'] ) as $role ){
          
                        $event->attachRole( $role );

                    }

                }
                
                /**
                * Update && Add reminder data
                * 
                * @var mixed
                */
                
                $reminderIds = array();
                if( !empty( $data['reminders'] ) ){
                    
                    foreach( $data['reminders'] as $reminder ){
                        if(isset($reminder['id'])){
                            /**
                            * if id is not null, update the reminder
                            * 
                            * @var mixed
                            */
                            $dbReminder         = Reminder::find($reminder['id']);
                        }else{
                            /**
                            * if id is null, then add a new reminder
                            * 
                            * @var mixed
                            */
                            $dbReminder                 = new Reminder();
                            $dbReminder->item_id        = $eventid;
                            $dbReminder->module_name    = "event";
                        }
                        
                        // the type is not "role", set role_id to 0
                        $reminder['role_id']        = $reminder['type'] != "role" ? 0 : $reminder['role_id'];

                        $dbReminder->timecount      = $reminder['timecount'];
                        $dbReminder->type           = $reminder['type'];
                        $dbReminder->role_id        = isset($reminder['role_id']) ? $reminder['role_id'] : 0;
                        $dbReminder->period         = $reminder['period'];
                        $dbReminder->save();
                        $reminderIds[]              = $dbReminder->id;
                    }

                }
                
                /**
                * Delete removed reminders from db
                */
                foreach($event->reminders as $db_reminder){
                    if(!in_array($db_reminder['id'], $reminderIds)){
                        $db_reminder->delete();
                    }
                }

                //Empty the Stores
                $event->detachAllStores();

                //Assign Stores
                if( !empty( $data['stores'] ) ){

                    foreach( Store::find( $data['stores'] ) as $store ){
                     
                        $event->attachStore( $store );

                    }

                }

                //Empty the Files
                $event->detachAllFiles();

                //Get the Attachments
                if( count( $attachments ) > 0 ){

                    foreach( $attachments as $key => $file ){
                        if(!empty( $file['attachment_id'] )){

                            $attachments[ $key ] = $file['attachment_id'];

                            $event->attachFile( $file['attachment_id'] );
                    
                        }
                    }

                    $attachments = Attachment::grab($attachments);

                }




                //Only if we want to email it.
                if( Input::get('sendemail') ){


                    if( !empty( $data['stores'] ) || !empty( $data['roles'] ) ){

                        $Recipients = User::where(function($query) use ($data) {

                            if( !empty( $data['stores'] ) ){

                                $query->whereHas( 'stores' , function($query) use ($data) {

                                    $stores = [];

                                    array_walk( $data['stores'] , function(&$item) use (&$stores) {
                                        if( !empty($item['id']) ){

                                            $stores[] = $item['id'];

                                        }
                                    });

                                    $query->whereIn( 'store_id' , $stores );
                                
                                });

                            }

                            if( !empty( $data['roles'] ) ){

                                $query->whereHas( 'roles' , function($query) use ($data) {

                                    $roles = [];

                                    array_walk( $data['roles'] , function(&$item) use (&$roles) {
                                        if( !empty($item['id']) ){

                                            $roles[] = $item['id'];

                                        }
                                    });

                                    $query->whereIn( 'role_id' , $roles );

                                });

                            }

                        })->get();

                    }else{

                        $Recipients = User::all();

                    }

                    /**
                    * Get all users including administrators
                    */
                    $Recipients = CommonService::getUsersForSendingMails($Recipients);

                    //Send the Email
                    Mail::send([ 'html' => 'emails.event'] , [ 'body' => $data['details'] , 'type' => 'Event Update', 'name' => $data['name'] , 'period' => date('M d, Y' , strtotime( $data['start'] ) ) . ' to ' . date('M d, Y' , strtotime( $data['end'] ) ) ] , function($message) use ( $data , $Recipients , $attachments ){

                        //Setup Recipients
                        foreach( $Recipients as $user ){
                            if( $user->email ){

                                $user->fullname = ( !empty( $user->firstname ) && !empty( $user->lastname ) ? $user->firstname . ' ' . $user->lastname : null );

                                $message->to( $user->email , $user->fullname );

                            }
                        }

                        //Set Subject
                        $message->subject( 'Event Update: ' . $data['name'] . ' - ' . env('COMPANY') );

                        //Attach the Attachments
                        if( count( $attachments ) > 0 ){
                            foreach( $attachments as $attachment ){
                         
                                $message->attach( $attachment->file );
                         
                            }
                        }

                    });

                }


                //Create the Notification
                $event->notifications()->create([

                    'icon'      => 'calendar',
                    'type'      => 'Event Updated',
                    'details'   => $event->name,
                    'url'       => '/admin/events/view/' . $event->id
               
                ])->send([

                    'permissions' => [ Permission::where('slug' , 'events.view' )->first()->id ],
                    'roles'       => $data['roles'],
                    'stores'      => $data['stores']
                
                ]);




                //Update the Search Criteria
                $event->search([

                    'title' => $data['name'],
                    'query' => strip_tags( $data['details'] )

                ])->assign([

                    'permissions' => [ Permission::where('slug' , 'events.view' )->first()->id ],
                    'roles'       => $data['roles'],
                    'stores'      => $data['stores']

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
    *   deleteEvent
    *       - Delete an Existing News Article
    *
    *   Params (URL):
    *       - newsid:                   (String) The Article ID
    *
    *   Returns (JSON):
    *       1. (Bool) Returns True / False
    *
    **/
    public function deleteEvent( $eventid ){  


        if( $event = Event::with([ 'roles' , 'stores', 'reminders'])->find( $eventid ) ){

            //Delete the Event
            $event->search()->delete();

            //Clear the Reminders
            $event->reminders()->delete();

            //Clear the Notifications
            $event->notifications()->delete();

            //Create the Notification
            $event->notifications()->create([

                'icon'      => 'calendar',
                'type'      => 'Event Deleted',
                'details'   => $event->name,
                'url'       => null

            ])->send([

                'permissions' => [ Permission::where('slug' , 'events.delete' )->first()->id ],
                'roles'       => $event->roles,
                'stores'      => $event->stores

            ]);

            //Delete the Event
            $event->delete();


            //Return Success
            return [ 'result' => 1 ];

        }

        //Return Failed
        return [ 'result' => 0 , 'errors' => [ 'That Event Doesn\'t exist' ] ];

    }    







}
