<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Bican\Roles\Models\Permission;
use App\Models\Notification;
use App\Models\Role;
use DB;
use Input;
use Validator;

class RoleController extends Controller {




    /**
    *
    *   getRole
    *       - Loads All of the Roles
    *
    *   URL Params:
    *       - roleid:       (INT) The Role ID to Lookup
    *
    *
    *   Returns (JSON):
    *       1. The Role Data
    *
    **/
    public function getRole( $roleid = 1 ){

        return Role::find( $roleid ) ;

    }   
    










    /**
    *
    *   getAllRoles
    *       - Loads All of the Roles
    *
    *   URL Params:
    *       - limit:     The Page Limit (Default: 15)
    *       - page:      Pages to Load (Default: null)
    *
    *
    *   Returns (JSON):
    *       1. The User Looked up (If $userid was passed)
    *       2. The Current User Session Data (If $userid was not passed)
    *       3. Null
    *
    **/
    public function getAllRoles( $limit = 15 , $page = null ){
        if( $page ){

            return [ 
                'total' => Role::count() , 
                'data'  => DB::table('roles')->take( $limit )->skip( ( ( $page - 1 ) * $limit ) )->get()
            ];

        }else{

            return Role::all(['id','name']);

        }
    }      
    











    /**
    *
    *   getAllPermissions
    *       - Loads All of the Permissions
    *
    *   Returns (JSON):
    *       1. The Permissions Total & List
    *
    **/
    public function getAllPermissions(){

        return [ 
            'total' => Permission::count() , 
            'data'  => Permission::all()
        ];

    }             
    











    /**
    *
    *   getRolePermissions
    *       - Loads All of the Role Permissions
    *
    *   Params (URL):
    *       - roleid:       (INT) The Role ID to Lookup
    *
    *   Returns (JSON):
    *       1. The Role's Permissions
    *
    **/
    public function getRolePermissions( $roleid ){

        return Role::find( $roleid )->permissions()->get();

    }    










    /**
    *
    *   findRole
    *       - Loads the Role based on the role name
    *
    *   Params ($_GET):
    *       - name:            (String) The Role Name
    *       - roleid:           (INT) Exclude the User ID from the Search
    *
    *   Returns (JSON):
    *       Returns the User data or null
    *
    **/
    public function findRole(){        

        $name       = Input::get( 'name' , null );
        $roleid     = Input::get( 'roleid' , null );

        if( $name ){

            $query = Role::query();

            if( $name ) $query->where( 'slug' , '=' , Str::slug( $name , '.' ) );

            if( $roleid ) $query->where( 'id' , '!=' , $roleid );

            return $query->first();

        }

        return Response::make( array( 'result' => 0 , 'error' => 'Role Name must be passed as a GET variable' , 'code' => 'invalid-request' ) , 404 );

    }












    /**
    *
    *   addRole
    *       - Create a New Role
    *
    *   Params ($_PUT):
    *       - name:             (String) The Role's Name
    *       - description:      (String) The Role's Description
    *       - permissions:      (Array) The User's Permissions
    *
    *   Returns (JSON):
    *       Returns the [ result => ( 1 = Success, 0 = Failure ) ]
    *
    **/
    public function addRole(){  

        $data           = Input::all();
        $validator      = Validator::make( $data, [
            'name'                      => 'required|slug:roles,slug',
            'description'               => 'required'
        ]);

        if( $validator->fails() ){

            //Return Failure
            return [ 'result' => 0 , 'errors' => $validator->errors()->all() ];

        }else{

            try {
    
                //Create the new Role
                $role = Role::create(array_merge($data,[
                    'slug'  => Str::slug( $data['name'] , '.' )
                ]));


                //Add the Permissions to the Role
                if( !empty( $data['permissions'] ) ){

                    foreach( Permission::find( $data['permissions'] ) as $permission ){
                     
                        $role->attachPermission( $permission );

                    }
                }

                //Create the Notification
                $role->notifications()->create([

                    'icon'      => 'lock',
                    'type'      => 'New Role',
                    'details'   => $role->name,
                    'url'       => '/admin/roles/edit/' . $role->id
              
                ])->send([
            
                    'permissions' => [ Permission::where('slug' , 'roles.edit' )->first()->id ]
            
                ]);




                //Update the Search Criteria
                $role->search([

                    'title'     => $data['name'] ,
                    'query'     => $data['description'],
                    'url'       => '/admin/roles/edit/' . $role->id

                ])->assign([

                    'permissions' => [ Permission::where('slug' , 'roles.edit' )->first()->id ],

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
    *   editRole
    *       - Create a New Role
    *
    *   Params ($_POST):
    *       - name:             (String) The Role's Name
    *       - description:      (String) The Role's Description
    *       - permissions:      (Array) The User's Permissions
    *
    *   Returns (JSON):
    *       Returns the [ result => ( 1 = Success, 0 = Failure ) ]
    *
    **/
    public function editRole( $roleid ){  
        
        $data       = array_merge([
            'id'            => (int)$roleid,
            'name'          => '',
            'description'   => '',
            'permissions'   => []
        ],Input::all());

        $validator  = Validator::make( $data, [
            'id'                        => 'required|int|exists:roles,id',
            'name'                      => 'required|slug:roles,slug,' . (int)$roleid,
            'description'               => 'required'
        ]);

        if( $validator->fails() ){

            //Return Failure
            return [ 'result' => 0 , 'errors' => $validator->errors()->all() , 'data' => $data ];

        }else{

            //Get the Role
            $role = Role::find( $roleid );

            try {

                //Update the Roles
                $role->name         = $data['name'];
                $role->slug         = $data['slug'];
                $role->description  = $data['description'];
                $role->save();
    

                // Don't Delete Administration Permissions
                if( $roleid != 1 ){

                    //Update the Permissions
                    $role->detachAllPermissions();

                    if( count( $data['permissions'] ) > 0 ){

                        foreach( Permission::find( $data['permissions'] ) as $permission ){
                         
                            $role->attachPermission( $permission );

                        }
                    }

                }

                //Create the Notification
                $role->notifications()->create([

                    'icon'      => 'lock',
                    'type'      => 'Role Updated',
                    'details'   => $role->name,
                    'url'       => '/admin/roles/edit/' . $role->id
                
                ])->send([
                
                    'permissions' => [ Permission::where('slug' , 'roles.edit' )->first()->od ]
                
                ]);


                //Update the Search Criteria
                $role->search([

                    'title'       => $data['name'] ,
                    'query'       => $data['description']

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
    *   deleteRole
    *       - Delete an Existing User
    *
    *   Params (URL):
    *       - roleid:                   (String) The Role ID
    *
    *   Returns (JSON):
    *       1. (Bool) Returns True / False
    *
    **/
    public function deleteRole( $roleid ){  

        if( $role = Role::find( $roleid ) ){

            //Delete the Search
            $role->search()->delete();

            //Clear the Notifications
            $role->notifications()->delete();

            //Send Notification
            $role->notifications()->create([

                'icon'      => 'lock',
                'type'      => 'Role Deleted',
                'details'   => $role->name,
                'url'       => null
           
            ])->send([
           
                'permissions'   => [ Permission::where('slug' , 'roles' )->first()->id ]
           
            ]);

            //Delete the Role
            $role->delete();

            //Return Success
            return [ 'result' => 1 ];

        }

        //Return Failure
        return [ 'result' => 0 , 'errors' => [ 'That Role Doesn\'t Exist' ] ];

    }     






}
