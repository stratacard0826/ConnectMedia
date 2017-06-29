<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\User;
use App\Models\Notification;
use Bican\Roles\Models\Permission;
use DB;
use Validator;
use Geocoder;
use Input;

class StoreController extends Controller {






    /**
    *
    *   getStore
    *       - Loads All of the Stores
    *
    *   URL Params:
    *       - storeid:       (INT) The Store ID to Lookup
    *
    *
    *   Returns (JSON):
    *       1. The Store Data
    *
    **/
    public function getStore( $storeid = 1 ){

        return Store::find( $storeid ) ;

    }   






    /**
    *
    *   getStoreUsers
    *       - Loads All of the Store Users
    *
    *   URL Params:
    *       - storeid:       (INT) The Store ID to Lookup
    *
    *
    *   Returns (JSON):
    *       1. The Store Data
    *
    **/
    public function getStoreUsers( $storeid = 1 ){

        $store = Store::find( $storeid );

        if( $store ){

            return $store->users()->with('roles')->get();

        }

        return null;

    }   
    






    /**
    *
    *   getAllStores
    *       - Loads All of the Stores
    *
    *   URL Params:
    *       - limit:     The Page Limit (Default: 15)
    *       - page:      Pages to Load (Default: 1)
    *
    *   Returns (JSON):
    *       1. The list of stores
    *
    **/
    public function getAllStores( $limit = 15 , $page = null ){
        if( $page ){

            return [ 
                'total' => Store::count() , 
                'data'  => DB::table('stores')->take( $limit )->skip( ( ( $page - 1 ) * $limit ) )->get()
            ];

        }else{

            return Store::all(['id','name','address','city','province','postalcode','phone','latitude','longitude']);

        }
    }












    /**
    *
    *   findStore
    *       - Loads the Store based on the store name
    *
    *   Params ($_GET):
    *       - name:            (String) The Role Name
    *       - storeid:         (INT) Exclude the Store ID from the Search
    *
    *   Returns (JSON):
    *       Returns the User data or null
    *
    **/
    public function findStore(){    

        $name       = Input::get( 'name' , null );
        $storeid    = Input::get( 'storeid' , null );

        if( $name ){

            $query = Store::query();

            if( $name ) $query->where( 'slug' , '=' , Str::slug( $name , '.' ) );

            if( $storeid ) $query->where( 'id' , '!=' , $storeid );

            return $query->first();

        }

        return Response::make( array( 'result' => 0 , 'error' => 'Store Name must be passed as a GET variable' , 'code' => 'invalid-request' ) , 404 );

    }













    /**
    *
    *   addStore
    *       - Create a New Store
    *
    *   Params ($_PUT):
    *       - name:             (String) The Store's Name
    *       - description:      (String) The Store's Description
    *       - permissions:      (Array) The User's Permissions
    *
    *   Returns (JSON):
    *       Returns the [ result => ( 1 = Success, 0 = Failure ) ]
    *
    **/
    public function addStore(){  

        $data           = Input::all();
        $validator      = Validator::make( $data, [
            'name'                      => 'required|slug:stores,slug',
            'address'                   => 'required',
            'city'                      => 'required',
            'province'                  => 'required',
            'postalcode'                => 'required',
            'phone'                     => 'required'
        ],[ 
            'slug'                      => 'A similar store name already exists' 
        ]);

        if( $validator->fails() ){

            //Validator Return, Failed
            return [ 'result' => 0 , 'errors' => $validator->errors()->all() ];

        }else{

            //Get the Location by Address
            $response   = json_decode(Geocoder::geocode('json',[
                'address' => $data['address'] . ', ' . $data['city'] . ', ' . $data['province'] . ', ' . $data['postalcode']
            ]));

            //Make sure the Location Exists
            if( count( $response->results ) > 0 ){

                try {

                    //Create the Store
                    $store = Store::create(array_merge($data,[
                        'slug'       =>  Str::slug( $data['name'] , '.' ),
                        'latitude'   =>  $response->results[0]->geometry->location->lat,
                        'longitude'  =>  $response->results[0]->geometry->location->lng
                    ]));


                    //Create the Notificaiton
                    $store->notifications()->create([

                        'icon'      => 'map-marker',
                        'type'      => 'New Store',
                        'details'   => $store->name,
                        'url'       => '/admin/stores/edit/' . $store->id
                   
                    ])->send([
                  
                        'notification' => [ Permission::where('slug' , 'stores.edit' )->first()->id ]
                   
                    ]);



                    //Update the Search Criteria
                    $store->search([

                        'title'       => $data['name'] ,
                        'query'       => implode(' ',[
                            $data['address'],
                            $data['city'],
                            $data['province'],
                            $data['postalcode'],
                            $data['phone']
                        ]),
                        'url'         => '/admin/stores/edit/' . $store->id

                    ])->assign([

                        'permissions' => [ Permission::where('slug' , 'stores.edit' )->first()->id ]

                    ]);



                    //Return Success
                    return [ 'result' => 1 ];
        
                } catch(Exception $e ){

                    //Return Failed
                    return [ 'result' => 0 , 'errors' => [ 'An Unknown Error Occured' ] ];

                }

            }

            //Return Failed
            return [ 'result' => 0 , 'errors' => [ 'The Address could not be verified' ] ];

        }

    } 










    /**
    *
    *   editStore
    *       - Create a New Store
    *
    *   Params ($_POST):
    *       - name:             (String) The Store's Name
    *       - description:      (String) The Store's Description
    *       - permissions:      (Array) The User's Permissions
    *
    *   Returns (JSON):
    *       Returns the [ result => ( 1 = Success, 0 = Failure ) ]
    *
    **/
    public function editStore( $storeid ){  

        $data       = Input::all();
        $validator  = Validator::make( $data, [
            'name'                      => 'required|slug:stores,slug,' . $storeid,
            'address'                   => 'required',
            'city'                      => 'required',
            'province'                  => 'required',
            'postalcode'                => 'required',
            'phone'                     => 'required'
        ],[ 
            'slug'                      => 'A similar store name already exists' 
        ]);

        if( $validator->fails() ){

            //Return Success
            return [ 'result' => 0 , 'errors' => $validator->errors()->all() , 'data' => $data ];

        }else{

            //Lookup the Address in Google
            $response   = json_decode(Geocoder::geocode('json',[
                'address' => $data['address'] . ', ' . $data['city'] . ', ' . $data['province'] . ', ' . $data['postalcode']
            ]));

            //Ensure the Listing Exists
            if( count( $response->results ) > 0 ){

                //Find the Listing
                $store = Store::find( $storeid );

                try {

                    //Update the Listing
                    $store->name         = $data['name'];
                    $store->slug         = Str::slug( $data['name'] , '.' );
                    $store->address      = $data['address'];
                    $store->province     = $data['province'];
                    $store->postalcode   = $data['postalcode'];
                    $store->phone        = $data['phone'];
                    $store->save();


                    //Return Success
                    $store->notifications()->create([

                        'icon'      => 'map-marker',
                        'type'      => 'Store Updated',
                        'details'   => $store->name,
                        'url'       => '/admin/stores/edit/' . $store->id
                   
                    ])->send([
                  
                        'permissions' => [ Permission::where('slug' , 'stores.edit' )->first()->id ]
                  
                    ]);



                    //Update the Search Criteria
                    $store->search([

                        'title'       => $data['name'] ,
                        'query'       => implode(' ',[
                            $data['address'],
                            $data['city'],
                            $data['province'],
                            $data['postalcode'],
                            $data['phone']
                        ])

                    ]);


                    //Return Success
                    return [ 'result' => 1 ];

                } catch( Exception $e ){

                    //Return Failed
                    return [ 'result' => 0 , 'errors' => [ 'An Unknown Error Occured' ] ];

                }

            }

            //Return Failed
            return [ 'result' => 0 , 'errors' => [ 'The Address could not be verified' ] ];

        }

    }  











    /**
    *
    *   deleteStore
    *       - Delete an Existing User
    *
    *   Params (URL):
    *       - storeid:                   (String) The Store ID
    *
    *   Returns (JSON):
    *       1. (Bool) Returns True / False
    *
    **/
    public function deleteStore( $storeid ){  

        if( $store = Store::find( $storeid ) ){

            //Delete the Store
            $store->search()->delete();

            //Empty the Notifications
            $store->notifications()->delete();

            //Create the Notification
            $store->notifications()->create([

                'icon'      => 'map-marker',
                'type'      => 'Store Deleted',
                'details'   => $store->name,
                'url'       => null
            
            ])->send([
           
                'permissions' => [ Permission::where('slug' , 'stores' )->first()->id ]
           
            ]);

            //Delete the Store
            $store->delete();

            return [ 'result' => 1 ];

        }

        //Return Failed
        return [ 'result' => 0 , 'errors' => [ 'That Store Doesn\'t exist' ] ];

    }     









}
