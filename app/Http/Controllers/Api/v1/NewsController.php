<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use App\Models\News;
use App\Models\Attachment;
use App\Models\Store;
use App\Models\User;
use App\Models\Event;
use App\Models\Notification;
use App\Services\CommonService;
use Input;
use Auth;
use Validator;
use Mail;
use DB;

class NewsController extends EventController {









    /**
    *
    *   getAllArticles
    *       - Loads All of the News Articles
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
    public function getAllArticles( $limit = 15 , $page = 1 ){

        //Load the User Data
        $news = News::with([ 'roles', 'stores', 'author' ])->take( $limit )->skip( ( ( $page - 1 ) * $limit ) )->get();

        foreach( $news as $index => $article ){

            //Prepare & Get the Author
            $permissions                = [ true , true ];
            $news[ $index ]->user       = $article->author;


            //If the User doesn't have Edit Permissions, Ensure he User has the correct Roles / Stores
            if( !Auth::user()->hasPermission( 'news.edit' ) ){

                //Check the Roles
                if( !empty( $article->roles ) ){

                    $permissions[0] = Auth::user()->isOne( array_column( $article->roles->toArray() , 'id' ) );

                }

                //Check the Stores
                if( !empty( $article->stores ) ){

                    $permissions[1] = Auth::user()->inStores( array_column( $article->stores->toArray() , 'id' ) );

                }

            }


            //If we have the Permissions or the User created the article
            if( $permissions == [ true , true ] || $news[ $index]->user->id == Auth::user()->id ){

                //Prepare the Summary
                $article                    = strip_tags( $article->article );
                $news[ $index ]->summary    = strlen( $article ) > 300 ? substr( htmlspecialchars_decode($article) , 0 , 300 ) . '...' : $article ;

            }else{

                //Set the Article & Summary to Hidden
                $news[ $index ]->article    = null;
                $news[ $index ]->summary    = '** Hidden **';

            }

        }

        //Return the Data
        return [ 
            'total' => News::count() , 
            'data'  => $news
        ];

    }  
    










    /**
    *
    *   getAllArticles
    *       - Loads All of the News Articles
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
    public function getUserArticles( $limit = 15 , $newsid = null ){


        //Load the User Data
        $news = News::with([ 'author' ])->where(function($query){

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


        })->where(function($query) use ($newsid){


            //Sort by the News ID
            if( $newsid ){

                $query->where( 'id' , '>' , $newsid );

            }

        });


        //Get the Articles
        $list = $news->take( $limit )->orderBy('id', 'DESC')->get();

        foreach( $list as $index => $article ){

            //Prepare the Summary
            $article                    = strip_tags( $article->article );
            $list[ $index ]->summary    = strlen( $article ) > 300 ? substr( htmlspecialchars_decode($article) , 0 , 300 ) . '...' : $article ;

        }





        //Return the Data
        return [ 
            'total' => $news->count() , 
            'data'  => $list
        ];


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
    *       1. The News Articles
    *
    **/
    public function getArticle( $id ){

        //Load the Article Data
        $article        = News::with([ 'roles', 'stores', 'attachments', 'author', 'event' ])->find( $id );
        $permissions    = [ true , true ];

        //If the User doesn't have Edit Permissions, Ensure he User has the correct Roles / Stores
        if( !Auth::user()->hasPermission( 'news.edit' ) && $article->author->id != Auth::user()->id ){

            //Check the Roles
            if( !empty( $article->roles ) ){

                $permissions[0] = Auth::user()->isOne( array_column( $article->roles->toArray() , 'id' ) );

            }

            //Check the Stores
            if( !empty( $article->stores ) ){

                $permissions[1] = Auth::user()->inStores( array_column( $article->stores->toArray() , 'id' ) );

            }

        }

        //If we have the Permissions or the User created the article
        if( $article && $permissions == [ true , true ] ){

            //Return the Data
            return [ 'result' => 1 , 'data' => [
                'author'        => $article->author,
                'id'            => $article->id,
                'event_id'      => $article->event_id,
                'start'         => ( $article->event_id ? $article->event->start : '' ),
                'end'           => ( $article->event_id ? $article->event->end : '' ),
                'attachments'   => $article->attachments,
                'subject'       => $article->subject,
                'article'       => $article->article,
                'created'       => $article->created_at->format( \Config::get('settings.timestamp')->long ),
                'stores'        => $article->stores,
                'roles'         => $article->roles,
                'reminders'     => $article->event_id ? $article->event->reminders : []
            ] ];

        }

        //Return Failed
        return [ 'result' => 0 , 'error' => 'Article not found' , 'code' => 'article-404' ];

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
    *   addArticle
    *       - Create a News Article
    *
    *   Params ($_PUT):
    *       - stores:          (String) The Stores who can read the article
    *       - roles:           (String) The Roles who can read the article
    *       - files:           (FILE) The Files to Send
    *       - article:         (String) The Article to send
    *       - email:           (Bool) Send an Email?
    *
    *   Returns (JSON):
    *       Returns the [ result => ( 1 = Success, 0 = Failure ) ]
    *
    **/
    public function addArticle(){  

        $recipients     = [];
        $attachments    = Input::get('files');
        $data           = Input::all();
        $validator      = Validator::make( $data, [
            'start'         => ( Input::get('createevent') == 1 ? 'before:' . Input::get('end') . '|after:' . $data['today'] : '' ),
            'end'           => ( Input::get('createevent') == 1 ? 'required_if:createevent,1|after:' . Input::get('start') : '' ),
            'article'       => 'required',
            'subject'       => 'required|min:1|max:100',
            'sendemail'     => 'required|integer'
        ]);

        if( $validator->fails() ){

            //Validator Failed, Return
            return [ 'result' => 0 , 'errors' => $validator->errors()->all() ];

        }else{

            try {

                //Create the Event
                if( Input::get('createevent') ){

                    Input::merge([ 'name' => $data['subject'] , 'details' => $data['article'] ]);

                    //Add the Event
                    $event = $this->addEvent();

                    if( !$event['result'] ) return $event;

                }


                //Insert the News Article
                $news = News::create([
                    'user_id'   => Auth::user()->id,
                    'event_id'  => ( isset( $event ) ? $event['id'] : null ),
                    'article'   => $data['article'],
                    'subject'   => $data['subject']
                ]);


                //Assign Permissions
                if( !empty( $data['roles'] ) ){

                    foreach( Role::find( $data['roles'] ) as $role ){
          
                        $news->attachRole( $role );

                    }

                }


                //Assign Stores
                if( !empty( $data['stores'] ) ){

                    foreach( Store::find( $data['stores'] ) as $store ){
                     
                        $news->attachStore( $store );

                    }

                }


                //Get the Attachments
                if( count( $attachments ) > 0 ){

                    foreach( $attachments as $key => $file ){
                        if(!empty( $file['attachment_id'] )){

                            $attachments[ $key ] = $file['attachment_id'];

                            $news->attachFile( $file['attachment_id'] );
                    
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
                    Mail::send([ 'html' => 'emails.default'] , [ 'body' => $data['article'] , 'type' => 'New Company News' , 'subject' => $data['subject'] ] , function($message) use ( $data , $Recipients , $attachments){

                        //Setup Recipients
                        foreach( $Recipients as $user ){
                            if( $user->email ){

                                $user->fullname = ( !empty( $user->firstname ) && !empty( $user->lastname ) ? $user->firstname . ' ' . $user->lastname : null );

                                $message->to( $user->email , $user->fullname );

                            }
                        }

                        //Set Subject
                        $message->subject( 'Company News: ' . $data['subject'] . ' - ' . env('COMPANY') );

                        //Attach the Attachments
                        if( count( $attachments ) > 0 ){
                            foreach( $attachments as $attachment ){
                         
                                $message->attach( $attachment->file );
                         
                            }
                        }

                    });

                }

                //Create the Notification
                $news->notifications()->create(array(

                    'icon'      => 'bell-o',
                    'type'      => 'New Article',
                    'details'   => $data['subject'],
                    'url'       => '/news/view/' . $news->id

                ))->send([

                    'permissions' => [ Permission::where('slug' , 'news.view' )->first()->id ],
                    'roles'       => $data['roles'],
                    'stores'      => $data['stores']

                ]);





                //Update the Search Criteria
                $news->search([

                    'title'       => $data['subject'],
                    'query'       => strip_tags( $data['article'] ),
                    'url'         => '/news/view/' . $news->id

                ])->assign([

                    'permissions' => [ Permission::where('slug' , 'news.view' )->first()->id ],
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
    *   editArticle
    *       - Updates a News Article
    *
    *   Params ($_POST):
    *       - articleid:       (INT) The Article ID to Update
    *       - stores:          (String) The Stores who can read the article
    *       - roles:           (String) The Roles who can read the article
    *       - files:           (FILE) The Files to Send
    *       - article:         (String) The Article to send
    *       - email:           (Bool) Send an Email?
    *
    *   Returns (JSON):
    *       Returns the [ result => ( 1 = Success, 0 = Failure ) ]
    *
    **/
    public function editArticle( $articleid ){  

        $recipients     = [];
        $attachments    = Input::get('files');
        $data           = array_merge([
            'id'            => $articleid,
            'stores'        => [],
            'roles'         => [],
            'files'         => [],
            'article'       => '',
            'subject'       => '',
            'email'         => false
        ],Input::all());
        $validator      = Validator::make( $data, [
            'id'            => 'required|integer|exists:news',
            'article'       => 'required',
            'subject'       => 'required|min:1|max:100',
            'sendemail'     => 'required|integer'
        ]);

        if( $validator->fails() ){

            //Validator Failed, Return Failure
            return [ 'result' => 0 , 'errors' => $validator->errors()->all() ];

        }else{
            
            //Get the Article    
            $news = News::find( $articleid );


            try {

                //Create the Event
                if( Input::get('createevent') ){
                    
                    //Prepare the Data
                    Input::merge([ 'id' => $data['event_id'] , 'name' => $data['subject'] , 'details' => $data['article'] ]);

                    if( !$news->event_id ){

                        //Add the Event
                        $event = $this->addEvent( $news->event_id );

                        if( !$event['result'] ) return $event;

                    }else{

                        //Update the Event
                        $event = $this->editEvent( $news->event_id );

                    }

                }else if( $news->event_id ){

                    //Delete the Event
                    $this->deleteEvent( $news->event_id );

                }

                //Update the News Article
                $news->article  = $data['article'];
                $news->subject  = $data['subject'];
                $news->save();

                //Empty the Roles
                $news->detachAllRoles();

                //Assign Permissions
                if( !empty( $data['roles'] ) ){

                    foreach( Role::find( $data['roles'] ) as $role ){
          
                        $news->attachRole( $role );

                    }

                }

                //Empty the Stores
                $news->detachAllStores();

                //Assign Stores
                if( !empty( $data['stores'] ) ){

                    foreach( Store::find( $data['stores'] ) as $store ){
                     
                        $news->attachStore( $store );

                    }

                }

                //Empty the Files
                $news->detachAllFiles();

                //Get the Attachments
                if( count( $attachments ) > 0 ){

                    foreach( $attachments as $key => $file ){
                        if(!empty( $file['attachment_id'] )){

                            $attachments[ $key ] = $file['attachment_id'];

                            $news->attachFile( $file['attachment_id'] );
                    
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
                    Mail::send([ 'html' => 'emails.news'] , [ 'body' => $data['article'] , 'type' => 'Updated Company News' , 'subject' => $data['subject']  ] , function($message) use ( $data , $Recipients , $attachments){

                        //Setup Recipients
                        foreach( $Recipients as $user ){
                            if( $user->email ){

                                $user->fullname = ( !empty( $user->firstname ) && !empty( $user->lastname ) ? $user->firstname . ' ' . $user->lastname : null );

                                $message->to( $user->email , $user->fullname );

                            }
                        }

                        //Set Subject
                        $message->subject( 'Company News Update: ' .$data['subject'] . ' - ' . env('COMPANY') );

                        //Attach the Attachments
                        if( count( $attachments ) > 0 ){
                            foreach( $attachments as $attachment ){
                         
                                $message->attach( $attachment->file );
                         
                            }
                        }

                    });

                }

                //Create the Notification
                $news->notifications()->create([

                    'icon'      => 'bell-o',
                    'type'      => 'Article Updated',
                    'details'   => $data['subject'],
                    'url'       => '/news/view/' . $news->id
               
                ])->send([
               
                    'permissions' => [ Permission::where('slug' , 'news.view' )->first()->id ],
                    'roles'       => $data['roles'],
                    'stores'      => $data['stores']
               
                ]);



                //Update the Search Criteria
                $news->search([

                    'title' => $data['subject'],
                    'query' => strip_tags( $data['article'] ),

                ])->assign([

                    'permissions' => [ Permission::where('slug' , 'news.view' )->first()->id ],
                    'roles'       => $data['roles'],
                    'stores'      => $data['stores']

                ]);




                return [ 'result' => 1 ];
    
            } catch(Exception $e ){

                return [ 'result' => 0 , 'errors' => [ 'An Unknown Error Occured' ] ];

            }

        }

    } 











    /**
    *
    *   deleteArticle
    *       - Delete an Existing News Article
    *
    *   Params (URL):
    *       - newsid:                   (INT) The Article ID
    *
    *   Returns (JSON):
    *       1. (Bool) Returns True / False
    *
    **/
    public function deleteArticle( $newsid ){  


        if( $article = News::with([ 'roles' , 'stores' ])->find( $newsid ) ){

            if( $article->event_id ){

                //Delete any associated Events
                $this->deleteEvent( $article->event_id );

            }

            //Delete the Search
            $article->search()->delete();

            //Clear the Notifications
            $article->notifications()->delete();

            //Create the Notification
            $article->notifications()->create([

                'icon'      => 'map-marker',
                'type'      => 'Article Deleted',
                'details'   => $article->subject,
                'url'       => null
           
            ])->send([
         
                'permissions' => [ Permission::where('slug' , 'news.delete' )->first()->id ],
                'roles'       => $article->roles,
                'stores'      => $article->stores
          
            ]);

            //Delete the Store
            $article->delete();

            //Return Success
            return [ 'result' => 1 ];

        }

        //Return Failed
        return [ 'result' => 0 , 'errors' => [ 'That Article Doesn\'t exist' ] ];

    }    








}
