<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Auth\AuthManager;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use App\Models\User;
use App\Models\Store;
use App\Models\Notification;
use App\Services\CommonService;
use Auth;
use Input;
use Validator;
use Hash;
use DB;
use Mail;

class UserController extends Controller
{




    /**
    *
    *   getStatus
    *       - Checks if the Current User is Logged in
    *
    *   Params:
    *       n/a
    *
    *   Returns (JSON):
    *       1. The User Looked up (If $userid was passed)
    *       2. The Current User Session Data (If $userid was not passed)
    *       3. Null
    *
    **/
    public function getStatus(){    

        return [ 'result' => \Auth::check() ];

    }









    /**
    *
    *   getUser
    *       - Loads the User
    *
    *   Params:
    *       - $userid:      The UserID to Lookup (Default: NULL)
    *
    *   Returns (JSON):
    *       1. The User Looked up (If $userid was passed)
    *       2. The Current User Session Data (If $userid was not passed)
    *       3. Null
    *
    **/
    public function getUser( $userid=null ){   

        return $userid ? User::find( $userid ) : \Auth::user() ;

    }
    











    /**
    *
    *   getAllUsers
    *       - Loads All of the Users
    *
    *   URL Params:
    * 		- limit: 	 The Page Limit (Default: 15)
    *       - page:      Pages to Load (Default: null)
    *
    *
    *   Returns (JSON):
    *       1. The Total Users
    *       2. A List of Users Paginated
    *
    **/
    public function getAllUsers( $limit = 15 , $page = null ){
        if( $page ){

            return [ 
                'total' => User::count() , 
                'data'  => User::take( $limit )->skip( ( ( $page - 1 ) * $limit ) )->get()
            ];

        }else{

            return User::all([ 'id', 'firstname', 'lastname' ]);

        }

    }   












    /**
    *
    *   findUser
    *       - Loads the User based on the passed criteria ( username or email )
    *
    *   Params ($_GET):
    *       - email:            (String) The Email Address
    *       - username:         (String) The Username
    *       - userid:           (INT) Exclude the User ID from the Search
    *
    *   Returns (JSON):
    *       Returns the User data or null
    *
    **/
    public function findUser(){        

        $email      = Input::get( 'email' , null );
        $username   = Input::get( 'username' , null );
        $userid     = Input::get( 'userid' , null );

        if( $email || $username ){

            $query = User::query();

            if( $email ) $query->where( 'email' , '=' , $email );

            if( $username ) $query->where( 'username' , '=' , $username );

            if( $userid ) $query->where( 'id' , '!=' , $userid );

            return $query->first();

        }

        return Response::make( array( 'result' => 0 , 'error' => 'Either email or username must be passed as a GET variable' , 'code' => 'invalid-request' ) , 404 );

    }













    /**
    *
    *   getRoles
    *       - Loads the User Roles
    *
    *   Params:
    *       - $userid:      The UserID to Lookup (Default: NULL)
    *
    *   Returns (JSON):
    *       1. Returns all of the Users Assigned Roels
    *
    **/
    public function getRoles( $userid = null ){    

        //Return the Roles
        return User::find( ( $userid ? $userid : Auth::user()->id ) )->roles()->get() ;
        
    }












    /**
    *
    *   getStores
    *       - Loads the User Stores
    *
    *   Params:
    *       - $userid:      The UserID to Lookup (Default: NULL)
    *
    *   Returns (JSON):
    *       1. Returns all of the Users Assigned Roels
    *
    **/
    public function getStores( $userid = null ){    

        //Return the Stores
        return User::find( ( $userid ? $userid : Auth::user()->id ) )->stores()->get() ;
        
    }













    /**
    *
    *   getPermissions
    *       - Loads the User Roles
    *
    *   Params:
    *       - $userid:      The UserID to Lookup (Default: NULL)
    *
    *   Returns (JSON):
    *       1. Returns all of the Users Assigned Roels
    *
    **/
    public function getPermissions( $userid = null ){    

        //Return the Permissions
        return User::find( ( $userid ? $userid : Auth::user()->id ) )->getPermissions();

    }













    /**
    *
    *   getCustomPermissions
    *       - Loads the User's Custom Permissions (Not Associated to the Role)
    *
    *   Params:
    *       - $userid:      The UserID to Lookup (Default: NULL)
    *
    *   Returns (JSON):
    *       1. Returns all of the Users Assigned Roels
    *
    **/
    public function getCustomPermissions( $userid = null ){   

        //Return the Permissions
        return User::find( ( $userid ? $userid : Auth::user()->id ) )->userPermissions()->get();

    }












    /**
    *
    *   addUser
    *       - Create a New User
    *
    *   Params ($_PUT):
    *       - firstname:                (String) The User Firstname
    *       - lastname:                 (String) The User Lastname
    *       - username:                 (String) The User's Username
    *       - password:                 (String) The User's Password
    *       - password_confirmation     (String) A Password Confirmation
    *       - city:                     (String) The User's City
    *       - province:                 (String) The User's Province
    *       - phone:                    (String) The User's Phone
    *       - roles:                    (Array) The User's Roles
    *       - locations:                (Array) The Locations the User is added to
    *
    *   Returns (JSON):
    *       Returns the User data or null
    *
    **/
    public function addUser(){  

        $data       = Input::all();
        $validator  = Validator::make( $data, [
            'firstname'                 => 'required',
            'lastname'                  => 'required',
            'dob'                       => 'date',
            'username'                  => 'unique:users,username|min:3|max:50',
            'email'                     => 'required|unique:users,email',
            'password'                  => 'confirmed|min:6|regex:/^(?=.*[a-z])(?=.*[A-Z]).*$/'
        ]);

        if( $validator->fails() ){

            //Validation Failed
            return [ 'result' => 0 , 'errors' => $validator->errors()->all() ];

        }else{


            try {

                //Create the User
                $user = User::create(array_merge($data,[
                    'password'   => Hash::make( $data['password'] ),
                    'slug'       => Str::slug( $data['firstname'] . ' ' . $data['lastname'] . ' ' . $data['username'] ),
                    'username'   => ( !empty( $data['username'] ) ? $data['username'] : null )
                ]));


                //Attach the Roles
                if( !empty( $data['roles'] ) ){

                    $role = Role::find( $data['roles'] );
                     
                    $user->attachRole( $role );

                }


                //Attach the Permissions
                if( !empty( $data['permissions'] ) ){

                    $permissions = Permission::find( $data['permissions'] );

                    foreach( $permissions as $permission ){

                        $user->attachPermission( $permission );

                    }

                }


                //Attach the Stores
                if( count( $data['stores'] ) > 0 ){

                    $stores = Store::find( $data['stores'] );

                    foreach( $stores as $store ){

                        $user->attachStore( $store );

                    }

                }


                

                //Send the Notification
                $user->notifications()->create([

                    'icon'      => 'user',
                    'type'      => 'New User',
                    'details'   => $user->firstname . ' ' . $user->lastname ,
                    'url'       => '/admin/users/edit/' . $user->id
                
                ])->send([            
                
                    'permissions'   => [ Permission::where('slug' , 'users.edit' )->first()->id ],
                    'exclude'       => [ $user->id ]
                
                ]);

                



                //Add the Search Criteria
                $user->search([
                
                    'title'         => $user->firstname.' '.$user->lastname,
                    'query'         => implode(' ',[
                        $user->username,
                        $user->email,
                        $user->city,
                        $user->province,
                        $user->phone
                    ]),
                    'url'           => '/admin/users/edit/' . $user->id
                
                ])->assign([
                
                    'permissions'   => [ Permission::where('slug' , 'users.edit' )->first()->id ]
                
                ]);




                /**
                * Get all users including administrators
                */
                $Recipients = CommonService::getUsersForSendingMails(array($user));

                //Send the Email
                Mail::send([ 'html' => 'emails.registration'] , $data , function($message) use ($Recipients){

                    foreach($Recipients as $user){
                    //Add the User
                    $message->to( $user->email , $user->firstname . ' ' . $user->lastname );
                    }

                    //Set Subject
                    $message->subject( 'Welcome to ' . env('COMPANY') );

                });




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
    *   editUser
    *       - Update an Existing User
    *
    *   Params (URL):
    *       - userid:                   (String) The User ID
    *
    *   Params ($_POST):
    *       - firstname:                (String) The User Firstname
    *       - lastname:                 (String) The User Lastname
    *       - username:                 (String) The User's Username
    *       - password:                 (String) The User's Password
    *       - password_confirmation     (String) A Password Confirmation
    *       - city:                     (String) The User's City
    *       - province:                 (String) The User's Province
    *       - phone:                    (String) The User's Phone
    *       - roles:                    (Array) The User's Roles
    *       - locations:                (Array) The Locations the User is added to
    *
    *   Returns (JSON):
    *       Returns the User data or null
    *
    **/
    public function editUser( $userid ){  

        $data       = array_merge([
            'id'        => (int)$userid,
            'firstname' => '',
            'lastname'  => '',
            'username'  => '',
            'password'  => '',
            'city'      => '',
            'province'  => '',
            'phone'     => '',
            'roles'     => [],
            'stores'    => []
        ],Input::all());
        $validator  = Validator::make( $data, [
            'id'                        => 'required|integer|exists:users',
            'firstname'                 => 'required',
            'lastname'                  => 'required',
            'dob'                       => 'date',
            'username'                  => 'min:3|max:50',
            'email'                     => 'required|unique:users,email,' . (int)$userid,
            'password'                  => 'confirmed|min:6|regex:/^(?=.*[a-z])(?=.*[A-Z]).*$/'
        ]);

        if( $validator->fails() ){

            //Validation Failed
            return [ 'result' => 0 , 'errors' => $validator->errors()->all() ];

        }else{

            //Get the User
            $user = User::find( $userid );

            try {

                //Setup the User
                if( !empty( $data['password'] ) ){

                    $user->password = Hash::make( $data['password'] );

                }

                $user->firstname    = $data['firstname'];
                $user->lastname     = $data['lastname'];
                $user->dob          = $data['dob'];
                $user->username     = ( !empty( $data['username'] ) ? $data['username'] : null );
                $user->slug         = Str::slug( $data['firstname'] . ' ' . $data['lastname'] . ' ' . $data['username'] );
                $user->email        = $data['email'];
                $user->city         = $data['city'];
                $user->province     = $data['province'];
                $user->phone        = $data['phone'];
                $user->save();

    
                //Setup the Roles
                $user->detachAllRoles();

                if( !empty( $data['roles'] ) ){

                    $user->attachRole( Role::find( $data['roles'] ) );

                }


                //Setup the Permissions
                $user->detachAllPermissions();

                if( !empty( $data['permissions'] ) ){

                    $permissions = Permission::find( $data['permissions'] );

                    foreach( $permissions as $permission ){

                        $user->attachPermission( $permission );

                    }

                }

                //Setup the Stores
                $user->detachAllStores();

                if( count( $data['stores'] ) > 0 ){

                    $stores = Store::find( $data['stores'] );

                    foreach( $stores as $store ){

                        $user->attachStore( $store );

                    }

                }



                //Send the Notification
                $user->notifications()->create([

                    'icon'      => 'user',
                    'type'      => 'User Updated' ,
                    'details'   =>  $user->firstname . ' ' . $user->lastname ,
                    'url'       => '/admin/users/edit/' . $user->id
                
                ])->send([            
                
                    'permissions'   => [ Permission::where('slug' , 'users.edit' )->first()->id ]
                
                ]);



                //Update the Search Criteria
                $user->search([
                
                    'title' => $user->firstname.' '.$user->lastname,
                    'query' => implode(' ',[
                        $user->username,
                        $user->email,
                        $user->city,
                        $user->province,
                        $user->phone
                    ])
                
                ]);



                //Return Success
                return [ 'result' => 1 ];

            } catch( Exception $e ){

                //Return Failure
                return [ 'result' => 0 , 'errors' => [ 'An Unknown Error Occured' ] ];

            }

        }

    }     











    /**
    *
    *   deleteUser
    *       - Delete an Existing User
    *
    *   Params (URL):
    *       - userid:                   (String) The User ID
    *
    *   Returns (JSON):
    *       1. (Bool) Returns True / False
    *
    **/
    public function deleteUser( $userid ){  

        if( $user = User::find( $userid ) ){

            //Delete the Search
            $user->search()->delete();

            //Clear the Notifications
            $user->notifications()->delete();

            //Send the Notification
            $user->notifications()->create([

                'icon'      => 'user',
                'type'      => 'User Deleted' ,
                'details'   => $user->firstname . ' ' . $user->lastname ,
                'url'       => null
            
            ])->send([
           
                'permissions' => [ Permission::where('slug' , 'users' )->first()->id ]
           
            ]);

            //Delete the User
            $user->delete();

            //Return Success
            return [ 'result' => 1 ];

        }else{

            //Return Failure
            return [ 'result' => 0 , 'errors' => [ 'That User doesn\'t exist' ] ];

        }

    }     




}
