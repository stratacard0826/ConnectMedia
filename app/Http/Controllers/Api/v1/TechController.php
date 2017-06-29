<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Bican\Roles\Models\Permission;
use Bican\Roles\Models\Role;
use App\Models\Notification;
use App\Models\Attachment;
use App\Models\Store;
use App\Models\Tech;
use App\Models\User;
use App\Services\CommonService;
use Validator;
use Input;
use Mail;
use Auth;

class TechController extends Controller
{


    /**
    *
    *   getProduct
    *       - Loads All of the Products
    *
    *   URL Params:
    *       - promotionid:       (INT) The Product ID to Lookup
    *
    *
    *   Returns (JSON):
    *       1. The Product Data
    *
    **/
    public function getProduct( $productid = 1 ){
        $product = Tech::with([ 'specifications' , 'attachment' , 'stores' , 'roles' ])->where(function($query){

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

        })->find( $productid )->toArray();

        //$products['attachment'] = Attachment::URL( Attachment::resize( $product['attachment']['filename'] , 300 , null , 'public' ) ) ;

        foreach( $product['specifications'] as $item => $specification ){

            $product['specifications'][ $item ] = [ 'key' => $specification['pivot']['key'] , 'value' => $specification['pivot']['value'] ];

        }

        //set the Attachment ID
        $product['attachment']['attachment_id'] = $product['attachment_id'];

        return [
            'result' => 1,
            'data'   => $product
        ];

    }   
    










    /**
    *
    *   getAllProducts
    *       - Loads All of the Products
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
    public function getAllProducts( $limit = 15 , $page = null ){
        if( $page ){

            $products = Tech::with([ 'specifications' , 'attachment' ])->where(function($query){

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

            })->take( $limit )->skip( ( ( $page - 1 ) * $limit ) )->get()->toArray();

            foreach( $products as $key => $product ){

                $products[ $key ]['attachment'] = Attachment::URL( Attachment::resize( $product['attachment']['filename'] , 300 , null , 'public' ) ) ;

                foreach( $product['specifications'] as $item => $specification ){

                    $products[ $key ]['specifications'][ $item ] = [ 'key' => $specification['pivot']['key'] , 'value' => $specification['pivot']['value'] ];

                }

            }

            return [ 
                'result'    => 1,
                'total'     => Tech::count() , 
                'data'      => $products
            ];

        }else{

            $products = Tech::all([ 'specifications' , 'attachment' ])->where(function($query){

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

            })->toArray();

            foreach( $products as $key => $product ){

                $products[ $key ]['attachment'] = Attachment::URL( Attachment::resize( Attachment::path( $product['attachment']['filename'] ) , 300 , null , 'public' ) ) ;

                foreach( $product['specifications'] as $item => $specification ){

                    $products[ $key ]['specifications'][ $item ] = [ 'key' => $specification['pivot']['key'] , 'value' => $specification['pivot']['value'] ];

                }

            }

            return [
                'result' => 1,
                'data'   => $products
            ];

        }
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

            $Attachment = Attachment::store( Input::file('file') , 'public' );

            return [ 'result' => 1 , 'attachment_id' => $Attachment->id ];

        }

        return [ 'result' => 0 , 'error' => 'Invalid File Uploaded' , 'code' => 'invalid-file' ];
    
    }  












    /**
    *
    *   addProduct
    *       - Create a New Product
    *
    *   Params ($_PUT):
    *       - name:             (String) The Product's Name
    *       - description:      (String) The Product's Description
    *       - permissions:      (Array) The User's Permissions
    *
    *   Returns (JSON):
    *       Returns the [ result => ( 1 = Success, 0 = Failure ) ]
    *
    **/
    public function addProduct(){  

        $data           = Input::all();
        $validator      = Validator::make( $data, [
            'attachment'                => 'required',
            'attachment.attachment_id'  => 'required',
            'name'                      => 'required|filled',
            'notes'                     => 'required|filled'
        ]);

        if( $validator->fails() ){

            //Return Failure
            return [ 'result' => 0 , 'errors' => $validator->errors()->all() ];

        }else{

            try {


                //Create the new Product
                $product = Tech::create([
                    'user_id'       => Auth::user()->id,
                    'attachment_id' => Input::get('attachment')['attachment_id'],
                    'name'          => $data['name'],
                    'notes'         => $data['notes']
                ]);


                //Add the Specifications
                foreach( $data['specifications'] as $specification ){

                    $product->specifications()->attach( $product , [
                        'key'       => $specification['key'],
                        'value'     => $specification['value']
                    ] );

                }


                //Assign Permissions
                if( !empty( $data['roles'] ) ){

                    foreach( Role::find( $data['roles'] ) as $role ){
          
                        $product->roles()->attach( $role );

                    }

                }


                //Assign Stores
                if( !empty( $data['stores'] ) ){

                    foreach( Store::find( $data['stores'] ) as $store ){
                     
                        $product->stores()->attach( $store );

                    }

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

                    $data['attachment'] = Attachment::URL( Attachment::resize( Attachment::grab([ Input::get('attachment')['attachment_id'] ])[0]['filename'] , 470 , null , 'public' ) ) ;

                    /**
                    * Get all users including administrators
                    */
                    $Recipients = CommonService::getUsersForSendingMails($Recipients);

                    //Send the Email
                    Mail::send([ 'html' => 'emails.tech'] , [ 'data' => $data , 'type' => 'New' ] , function($message) use ( $data , $Recipients ){

                        //Setup Recipients
                        foreach( $Recipients as $user ){
                            if( $user->email ){

                                $user->fullname = ( !empty( $user->firstname ) && !empty( $user->lastname ) ? $user->firstname . ' ' . $user->lastname : null );

                                $message->to( $user->email , $user->fullname );

                            }
                        }

                        //Set Subject
                        $message->subject( 'Tech Talk: ' . $data['name'] . ' - ' . env('COMPANY') );

                    });

                }


                //Create the Notification
                $product->notifications()->create([

                    'icon'      => 'cogs',
                    'type'      => 'New Tech Talk Product',
                    'details'   => $data['name'],
                    'url'       => '/tech/edit/' . $product->id
              
                ])->send([
            
                    'permissions' => [ Permission::where('slug' , 'tech.edit' )->first()->id ]
            
                ]);




                //Update the Search Criteria
                $product->search([

                    'title'     => 'Tech Talk: ' . $data['name'] ,
                    'query'     => $data['name'] . ' ' . $data['notes'],
                    'url'       => '/tech/edit/' . $product->id

                ])->assign([

                    'permissions' => [ Permission::where('slug' , 'tech.edit' )->first()->id ],

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
    *   editProduct
    *       - Create a New Product
    *
    *   Params ($_POST):
    *       - name:             (String) The Product's Name
    *       - description:      (String) The Product's Description
    *
    *   Returns (JSON):
    *       Returns the [ result => ( 1 = Success, 0 = Failure ) ]
    *
    **/
    public function editProduct( $productid ){  
        
        $data           = Input::all();
        $validator      = Validator::make( $data, [
            'attachment'                => 'required',
            'attachment.attachment_id'  => 'required',
            'name'                      => 'required|filled',
            'notes'                     => 'required|filled'
        ]);

        if( $validator->fails() ){

            //Return Failure
            return [ 'result' => 0 , 'errors' => $validator->errors()->all() , 'data' => $data ];

        }else{

            //Get the Product
            $product = Tech::find( $productid );

            try {

                //Update the Products
                $product->name          = $data['name'];
                $product->notes         = $data['notes'];
                $product->attachment_id = Input::get('attachment')['attachment_id'];
                $product->save();

                $product->specifications()->detach();

                //Add the Specifications
                foreach( $data['specifications'] as $specification ){

                    $product->specifications()->attach( $product , [
                        'key'       => $specification['key'],
                        'value'     => $specification['value']
                    ] );

                }

                $product->roles()->detach();

                //Assign Permissions
                if( !empty( $data['roles'] ) ){

                    foreach( Role::find( $data['roles'] ) as $role ){
          
                        $product->roles()->attach( $role );

                    }

                }


                $product->stores()->detach();

                //Assign Stores
                if( !empty( $data['stores'] ) ){

                    foreach( Store::find( $data['stores'] ) as $store ){
                     
                        $product->stores()->attach( $store );

                    }

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

                    $data['attachment'] = Attachment::URL( Attachment::resize( Attachment::grab([ Input::get('attachment')['attachment_id'] ])[0]['filename'] , 470 , null , 'public' ) ) ;

                    /**
                    * Get all users including administrators
                    */
                    $Recipients = CommonService::getUsersForSendingMails($Recipients);

                    //Send the Email
                    Mail::send([ 'html' => 'emails.tech'] , [ 'data' => $data , 'type' => 'New' ] , function($message) use ( $data , $Recipients ){

                        //Setup Recipients
                        foreach( $Recipients as $user ){
                            if( $user->email ){

                                $user->fullname = ( !empty( $user->firstname ) && !empty( $user->lastname ) ? $user->firstname . ' ' . $user->lastname : null );

                                $message->to( $user->email , $user->fullname );

                            }
                        }

                        //Set Subject
                        $message->subject( 'Tech Talk: ' . $data['name'] . ' - ' . env('COMPANY') );

                    });

                }
                

                //Create the Notification
                $product->notifications()->create([

                    'icon'      => 'cogs',
                    'type'      => 'Tech Talk Product Updated',
                    'details'   => $data['name'],
                    'url'       => '/tech/edit/' . $product->id
                
                ])->send([
                
                    'permissions' => [ Permission::where('slug' , 'tech.edit' )->first()->od ]
                
                ]);


                //Update the Search Criteria
                $product->search([

                    'title'     => $product->name ,
                    'query'     => $product->name . ' ' . $product->notes
                
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
    *   deleteProduct
    *       - Delete an Existing User
    *
    *   Params (URL):
    *       - promotionid:                   (String) The Product ID
    *
    *   Returns (JSON):
    *       1. (Bool) Returns True / False
    *
    **/
    public function deleteProduct( $productid ){  

        if( $product = Tech::find( $productid ) ){

            //Delete the Search
            $product->search()->delete();

            //Clear the Notifications
            $product->notifications()->delete();

            //Send Notification
            $product->notifications()->create([

                'icon'      => 'cogs',
                'type'      => 'Tech Product Deleted',
                'details'   => $product->name,
                'url'       => null
           
            ])->send([
           
                'permissions'   => [ Permission::where('slug' , 'tech' )->first()->id ]
           
            ]);

            //Delete the Product
            $product->delete();

            //Return Success
            return [ 'result' => 1 ];

        }

        //Return Failure
        return [ 'result' => 0 , 'errors' => [ 'That Tech Product Doesn\'t Exist' ] ];

    }      


}
