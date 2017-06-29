<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use App\Models\Notifiation;
use App\Models\BuyersLogout;
use App\Models\Promotion;
use App\Models\User;
use App\Models\Store;
use App\Services\CommonService;
use Input;
use Validator;
use Auth;
use Mail;
use DB;

class BuyersLogoutController extends Controller {
  








    /**
    *
    *   getAllLogouts
    *       - Loads All of the Logouts
    *
    *   URL Params:
    *       - limit:     (INT) The Page Limit (Default: 15)
    *       - page:      (INT) Pages to Load (Default: 1)
    *
    *
    *   Returns (JSON):
    *       1. The Logout List
    *
    **/
    public function getAllLogouts( $limit = 15 , $page = 1 ){

        $query  = BuyersLogout::with('store')->where( 'published' , 1 );

        //Return the Data
        return [ 
            'total' => $query->count(),
            'data'  => $query->take( $limit )->skip( ( ( $page - 1 ) * $limit ) )->get()->each(function( &$item ){

                $item['date'] = strtotime( $item['submitted'] ) * 1000;

            })
        ];

    }   
    













    /**
    *
    *   getLogout
    *       - Loads a Single Logout
    *
    *   URL Params:
    *       - id:        (INT) The Logout ID to Load
    *
    *
    *   Returns (JSON):
    *       1. The News Logouts
    *
    **/
    public function getLogout( $id ){

        $logout     = BuyersLogout::with('store')->where( 'id' , $id )->get()->first();

        if( $logout ){

            $logout['date'] = strtotime( $logout['submitted'] ) * 1000 ;

        }

        //Load the Logout Data
        return [
            'result' => 1,
            'data'   => $logout
        ];

    }    
    













    /**
    *
    *   getStoreLogout
    *       - Loads a Single, Unpublished Logout based on teh Store ID
    *
    *   URL Params:
    *       - storeid:        (INT) The Store ID to Load
    *
    *
    *   Returns (JSON):
    *       1. The News Logouts
    *
    **/
    public function getStoreLogout( $storeid ){

        //Load the Logout Data
        return [
            'result' => 1,
            'data'   => BuyersLogout::where( 'store_id' , $storeid )->where( 'published' , 0 )->first()
        ];

    }  
  








    /**
    *
    *   getLogoutReport
    *       - Loads the Logout Report
    *
    *   POST Params:
    *       - store_id:         (INT) The Store ID to Lookup
    *       - user_id:          (INT) The User ID to Lookup
    *       - promotion_id:     (INT) The Promotion ID to Lookup
    *       - start:            (Date) The Start Date
    *       - end:              (Date) The End Date
    *
    *
    *   Returns (JSON):
    *       1. The Report Data
    *
    **/
    public function getLogoutReport(){


        $response       = [];
        $validator      = Validator::make( Input::all() , [
            'store_id'      => 'exists:stores,id',
            'user_id'       => 'exists:users,id',
            'start'         => ( Input::get('end') ? 'before:' . Input::get('end') : '' ),
            'end'           => ( Input::get('start') ? 'after:' . Input::get('start') : '' )
        ]);

        if( $validator->fails() ){

            //Validator Failed, Return
            return [ 'result' => 0 , 'errors' => $validator->errors()->all() ];

        }else{


            /**
            *
            *   Total Results
            *
            **/
            $response['total'] = BuyersLogout::where(function($query){

                $query->where('published' , 1 );

                if( Input::get('start') )       $query->where( 'start' , '>=' , Input::get('start') . '00:00:00' );
                if( Input::get('end') )         $query->where( 'start' , '<=' , Input::get('end') . '23:59:59' );
                if( Input::get('store_id') )    $query->where( 'store_id' , Input::get('store_id') );

            })->count();

            /**
            *
            *   Monthly Data
            *
            **/
            $response['monthly'] = 
            DB::table('buyerslogouts')
                ->select([
                    DB::raw('SUM(sales) AS sales'),
                    DB::raw('YEAR(start) AS year'),
                    DB::raw('MONTH(start) AS month')
                ])
                ->where(function( $query ){

                    $query->where('published' , 1 );

                    if( Input::get('start') )       $query->where( 'start' , '>=' , Input::get('start') . '00:00:00' );
                    if( Input::get('end') )         $query->where( 'start' , '<=' , Input::get('end') . '23:59:59' );
                    if( Input::get('store_id') )    $query->where( 'store_id' , Input::get('store_id') );


                })
                ->groupBy(DB::raw('YEAR(start), MONTH(start)'))
                ->get();





            /**
            *
            *   Accumulative Data
            *
            **/
            $response['accumulative'] = 
            DB::table('buyerslogouts')
                ->select([
                    DB::raw('SUM(sales) AS sum_total_sales'),
                    DB::raw('AVG(sales) AS average_total_sales')

                ])
                ->where(function( $query ){

                    $query->where('published' , 1 );

                    if( Input::get('start') )       $query->where( 'start' , '>=' , Input::get('start') . '00:00:00' );
                    if( Input::get('end') )         $query->where( 'start' , '<=' , Input::get('end') . '23:59:59' );
                    if( Input::get('store_id') )    $query->where( 'store_id' , Input::get('store_id') );


                })
                ->first();



            //Return the Data
            return [
                'result' => 1,
                'data'   => $response
            ];

        }

    }   













    /**
    *
    *   saveLogout
    *       - Save the Logout
    *
    *   Params ($_PUT):
    *       - store_id:         (INT) The Store ID for the Logout
    *       - start:            (DateTime) The Start Date of the Logout
    *       - end:              (DateTime) The End Date of the Logout
    *       - location:         (String) Tomorrows Location
    *       - recap:            (String) The Recap for the Day
    *       - sales:            (String) The Total Sales for the Day        
    *
    *   Returns (JSON):
    *       Returns the [ result => ( 1 = Success, 0 = Failure ) ]
    *
    **/
    public function saveLogout(){  

        $data           = array_merge([
            'store_id'      => ''
        ],Input::all());
        $validator      = Validator::make( $data, [
            'store_id'      => 'required|exists:stores,id',
            'start'         => ( Input::get('end') ? 'before:' . Input::get('end') : '' ),
            'end'           => ( Input::get('start') ? 'after:' . Input::get('start') : '' ),
        ]);

        if( $validator->fails() ){

            //Validator Failed, Return
            return [ 'result' => 0 , 'errors' => $validator->errors()->all() ];

        }else{

            try {

                //Make sure the store exists
                if( !Auth::user()->inStore( $data['store_id'] ) ){

                    return [ 'result' => 0 , 'errors' => [ 'Invalid Store' ] ];

                }

                //Get any Existing logouts
                $logout = BuyersLogout::where(function($query) use ($data){

                    $query->where( 'store_id' , $data['store_id'] );
                    $query->where( 'published' , 0 );

                })->get()->first();

                //If we don't have a logout
                if( !$logout ){

                    $logout = BuyersLogout::create([
                        'store_id'      => $data['store_id'],
                        'published'     => 0
                    ]);

                }

                if(!empty($data['lymtd'])){
                    $data['lymtd'] = preg_replace( '/[^0-9.]/', '' , $data['lymtd'] );
                }

                if(!empty($data['mtd'])){
                    $data['mtd'] = preg_replace( '/[^0-9.]/', '' , $data['mtd'] );
                }

                if(!empty($data['sales'])){
                    $data['sales'] = preg_replace( '/[^0-9.]/', '' , $data['sales'] );
                }
                
                //Save the Data                
                $logout->update($data);

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
    *   sendLogout
    *       - Create a Menu Item
    *
    *   Params ($_PUT):
    *       - store_id:         (INT) The Store ID for the Logout
    *       - start:            (DateTime) The Start Date of the Logout
    *       - end:              (DateTime) The End Date of the Logout
    *       - location:         (String) Tomorrows Location
    *       - recap:            (String) The Recap for the Day
    *       - sales:            (String) The Total Sales for the Day
    *           
    *
    *   Returns (JSON):
    *       Returns the [ result => ( 1 = Success, 0 = Failure ) ]
    *
    **/
    public function sendLogout(){

        $data        = Input::all();
        $validator   = Validator::make( $data , [
            'store_id'      => 'required|exists:stores,id',
            'start'         => 'required|' . ( Input::get('end') ? 'before:' . Input::get('end') : '' ),
            'end'           => 'required|' . ( Input::get('start') ? 'after:' . Input::get('start') : '' ),
            'location'      => 'required',
            'sales'         => 'required'
        ]);

        if( $validator->fails() ){

            //Validator Failed, Return
            return [ 'result' => 0 , 'errors' => $validator->errors()->all() ];

        }else{

            $logout = BuyersLogout::with('store')->where( 'store_id' , $data['store_id'] )->where( 'published' , 0 )->firstOrFail();

            if( $logout ){

                try {

                    
                    $logout->published = 1;
                    $logout->submitted = date('Y-m-d H:i:s');                    
                    $logout->save();

                    //Send the Email
                    $this->send( $logout , 'New' );


                    //Create the Notification
                    $logout->notifications()->create(array(

                        'icon'      => 'sign-out',
                        'type'      => 'New Logout',
                        'details'   => $logout->store->name . ' ' . date( 'M d, Y h:ia' , strtotime( $logout->submitted ) ),
                        'url'       => '/logouts/view/' . $logout->id

                    ))->send([

                        'permissions' => [ Permission::where('slug' , 'buyerslogouts.view' )->first()->id ]

                    ]);

                    //Update the Search Criteria
                    $logout->search([

                        'title'       => 'Logout: ' . $logout->store->name . ' - ' . date('M d, Y h:ia'),
                        'query'       => $logout->store->name . ' ' . date( 'Y-m-d H:i:s' , strtotime( $logout->submitted ) ),
                        'url'         => '/logouts/view/' . $logout->id

                    ])->assign([

                        'permissions' => [ Permission::where('slug' , 'buyerslogouts.view' )->first()->id ]

                    ]);


                    //Return Success
                    return [ 'result' => 1 ];
        
                } catch(Exception $e ){

                    //Return Failure
                    return [ 'result' => 0 , 'errors' => [ 'An Unknown Error Occured' ] ];

                }

            }else{

                return [ 'result' => 0 , 'errors' => [ 'An Unknown Error Occured' ] ];

            }

        }

    } 














    /**
    *
    *   editLogout
    *       - Edits an Existing Menu Item
    *
    *   Params ($_POST):
    *       - store_id:         (INT) The Store ID for the Logout
    *       - start:            (DateTime) The Start Date of the Logout
    *       - end:              (DateTime) The End Date of the Logout
    *       - location:         (String) Tomorrows Location
    *       - recap:            (String) The Recap for the Day
    *       - sales:            (String) The Total Sales for the Day
    *           
    *
    *   Returns (JSON):
    *       Returns the [ result => ( 1 = Success, 0 = Failure ) ]
    *
    **/
    public function editLogout( $logoutid ){  

        $data        = Input::all();
        $validator   = Validator::make( $data , [
            'start'         => 'required|' . ( Input::get('end') ? 'before:' . Input::get('end') : '' ),
            'end'           => 'required|' . ( Input::get('start') ? 'after:' . Input::get('start') : '' ),
            'location'      => 'required',
            'lymtd'         => 'required',
            'mtd'           => 'required',
            'sales'         => 'required'
        ]);

        if( $validator->fails() ){

            //Validator Failed, Return
            return [ 'result' => 0 , 'errors' => $validator->errors()->all() ];

        }else{

            //Get the Logout
            $logout = BuyersLogout::with('store')->find( $logoutid );

            try {

                //Save the Logout
                $logout->start          = $data['start'];
                $logout->end            = $data['end'];
                $logout->recap          = $data['recap'];
                $logout->location       = $data['location'];
                $logout->lymtd          = preg_replace( '/[^0-9.]/', '' , $data['lymtd'] );
                $logout->mtd            = preg_replace( '/[^0-9.]/', '' , $data['mtd'] );
                $logout->sales          = preg_replace( '/[^0-9.]/', '' , $data['sales'] );
                $logout->save();

                //Send the Logout
                $this->send( $logout , 'Updated' );


                //Create the Notification
                $logout->notifications()->create(array(

                    'icon'      => 'sign-out',
                    'type'      => 'Logout Updated',
                    'details'   => $logout->store->name . ' ' . date( 'M d, Y h:ia' , strtotime( $logout->submitted ) ),
                    'url'       => '/logouts/view/' . $logoutid

                ))->send([

                    'permissions' => [ Permission::where('slug' , 'buyerslogouts.view' )->first()->id ]

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
    *   deleteLogout
    *       - Delete an Existing Logout
    *
    *   Params (URL):
    *       - recipeid:                   (INT) The Logout ID
    *
    *   Returns (JSON):
    *       1. (Bool) Returns True / False
    *
    **/
    public function deleteLogout( $promoid ){  


        if( $logout = BuyersLogout::with('store')->find( $promoid ) ){

            //Delete the Search
            $logout->search()->delete();

            //Delete the Notification
            $logout->notifications()->delete();

            //Create the Notification
            $logout->notifications()->create([

                'icon'      => 'sign-out',
                'type'      => 'Buyer Logout Deleted',
                'details'   => $logout->store->name . ' ' . date( 'M d, Y h:ia' , strtotime( $logout->submitted ) ),
                'url'       => null
           
            ])->send([
         
                'permissions' => [ Permission::where('slug' , 'buyerslogouts.delete' )->first()->id ],
          
            ]);

            //Delete the Store
            $logout->delete();

            //Return Success
            return [ 'result' => 1 ];

        }

        //Return Failed
        return [ 'result' => 0 , 'errors' => [ 'That Buyer Logout Doesn\'t exist' ] ];

    }   













    /**
    *
    *   send
    *       - Sends the Logout Email
    *
    *   Params:
    *      $logout:     (Object) The Created / Updated Logout
    *      $type:       (String) The Type of Logout Sent
    *           
    *
    *   Returns (JSON):
    *       Returns the [ result => ( 1 = Success, 0 = Failure ) ]
    *
    **/
    private function send( $logout , $type ){


        $Recipients = User::where(function($query) use ($logout){

            //Get the recipient roles
            $query->where(function($query){

                //Get all the Roles with Recipient ALL status
                $roles = Role::whereHas( 'permissions' , function($query){

                    $query->where( 'slug' , 'buyerslogouts.recipients.all' );

                })->get()->lists('id');

                if(!empty( $roles )){

                    //Has the Roles
                    $query->whereHas( 'roles' , function($query) use ($roles){

                        $query->whereIn( 'role_id' , $roles );

                    });

                }

            });


            //Get the recipient roles
            $query->orWhere(function($query) use ($logout){

                //Get the Roels with Recipient Single status
                $roles = Role::whereHas( 'permissions' , function($query){
               
                    $query->where( 'slug' , 'buyerslogouts.recipients.single' );
               
                })->get()->lists('id');

                if(!empty( $roles )){

                    //Has the Store ID
                    $query->whereHas( 'stores' , function($query) use ($roles, $logout){

                        $query->where( 'store_id' , $logout->store_id );

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
                $query->where( 'slug' , 'buyerslogouts.recipients.all' );

            });


            //Get the Single Role Permission Users
            $query->orWhere(function($query) use ($logout){

                //Has the Store
                $query->whereHas( 'stores' , function($query) use ($logout){

                    $query->where( 'store_id' , $logout->store_id );

                });

                //Has the User Permisison
                $query->whereHas( 'userPermissions' , function($query){

                    $query->where( 'slug' , 'buyerslogouts.recipients.single' );

                });

            });


        })->get();

        //Get the Logout Data
        $data = $logout->toArray();

        //Get Last Years Sales
        $previous = 
        DB::table('buyerslogouts')
            ->select( 
                DB::raw('SUM(sales) AS sales')
            )->where(function( $query ) use ($data){

                $query->where( 'published' , 1 );
                $query->where( 'store_id' , $data['store_id'] );

                //Get the Date Range
                $query->where( 'start'  , '>=' , ( date('Y') - 1 ) . '-01-01 00:00:00' );
                $query->where( 'start'  , '<=' , ( date('Y') - 1 ) . date('-m-d', strtotime( $data['start'] ) ) . ' 23:59:59' ); 

            })->first();

        //Get This Years Sales
        $current =  
        DB::table('buyerslogouts')
            ->select( 
                DB::raw('SUM(sales) AS sales')
            )->where(function( $query ) use ($data){

                $query->where( 'published' , 1 );
                $query->where( 'store_id' , $data['store_id'] );

                //Get the Date Range
                $query->where( 'start'  , '>=' , date('Y') . '-01-01 00:00:00' );
                $query->where( 'start'  , '<=' , date('Y-m-d' , strtotime( $data['start'] ) ) . ' 23:59:59' ); 

            })->first();

        //Set the Data
        $data['last_year_sales'] = $previous->sales;
        $data['this_year_sales'] = $current->sales;
        $data['name']            = \Auth::user()->firstname . ' ' . \Auth::user()->lastname;

        /**
        * Get all recipients including administrators
        */
        $Recipients = CommonService::getUsersForSendingMails($Recipients);

        //Send the Email
        Mail::send([ 'html' => 'emails.buyerslogout'] , [ 'data' => $data , 'type' => $type ] , function($message) use ( $Recipients , $logout , $type ){

            //Setup Recipients
            foreach( $Recipients as $user ){
                if( $user->email ){

                    $user->fullname = ( !empty( $user->firstname ) && !empty( $user->lastname ) ? $user->firstname . ' ' . $user->lastname : null );

                    $message->to( $user->email , $user->fullname );

                }
            }

            //Set Subject
            $message->subject( env('COMPANY') . ' ' . $type . ' Logout: ' . date( 'M d, Y h:ia' , strtotime( $logout->submitted ) ) );

        });


    } 





}
