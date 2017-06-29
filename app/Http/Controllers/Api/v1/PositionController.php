<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Bican\Roles\Models\Permission;
use App\Models\Notification;
use App\Models\Position;
use App\User;
use DB;
use Input;
use Validator;

class PositionController extends Controller {




    /**
    *
    *   getPosition
    *       - Loads All of the Positions
    *
    *   URL Params:
    *       - positionid:       (INT) The Position ID to Lookup
    *
    *
    *   Returns (JSON):
    *       1. The Position Data
    *
    **/
    public function getPosition( $positionid = 1 ){

        return Position::find( $positionid ) ;

    }   
    










    /**
    *
    *   getAllPositions
    *       - Loads All of the Positions
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
    public function getAllPositions( $limit = 15 , $page = null ){
        if( $page ){

            return [ 
                'total' => Position::count() , 
                'data'  => DB::table('positions')->take( $limit )->skip( ( ( $page - 1 ) * $limit ) )->get()
            ];

        }else{

            return [
                'result' => 1,
                'data'   => Position::all(['id','name'])
            ];

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
    *   findPosition
    *       - Loads the Position based on the position name
    *
    *   Params ($_GET):
    *       - name:            (String) The Position Name
    *       - positionid:           (INT) Exclude the User ID from the Search
    *
    *   Returns (JSON):
    *       Returns the User data or null
    *
    **/
    public function findPosition(){        

        $name       	= Input::get( 'name' , null );
        $positionid     = Input::get( 'positionid' , null );

        if( $name ){

            $query = Position::query();

            if( $name ) $query->where( 'slug' , '=' , Str::slug( $name , '.' ) );

            if( $positionid ) $query->where( 'id' , '!=' , $positionid );

            return $query->first();

        }

        return Response::make( array( 'result' => 0 , 'error' => 'Position Name must be passed as a GET variable' , 'code' => 'invalid-request' ) , 404 );

    }












    /**
    *
    *   addPosition
    *       - Create a New Position
    *
    *   Params ($_PUT):
    *       - name:             (String) The Position's Name
    *       - description:      (String) The Position's Description
    *
    *   Returns (JSON):
    *       Returns the [ result => ( 1 = Success, 0 = Failure ) ]
    *
    **/
    public function addPosition(){  

        $data           = Input::all();
        $validator      = Validator::make( $data, [
            'name'                      => 'required|slug:positions,slug'
        ]);

        if( $validator->fails() ){

            //Return Failure
            return [ 'result' => 0 , 'errors' => $validator->errors()->all() ];

        }else{

            try {
    
                //Create the new Position
                $position = Position::create(array_merge($data,[
                    'slug'  => Str::slug( $data['name'] , '.' )
                ]));



                //Create the Notification
                $position->notifications()->create([

                    'icon'      => 'wrench',
                    'type'      => 'New Position',
                    'details'   => $position->name,
                    'url'       => '/admin/positions/edit/' . $position->id
              
                ])->send([
            
                    'permissions' => [ Permission::where('slug' , 'positions.edit' )->first()->id ]
            
                ]);




                //Update the Search Criteria
                $position->search([

                    'title'     => $data['name'] ,
                    'query'     => ( !empty( $data['description'] ) ? $data['description'] : $data['name'] ),
                    'url'       => '/admin/positions/edit/' . $position->id

                ])->assign([

                    'permissions' => [ Permission::where('slug' , 'positions.edit' )->first()->id ],

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
    *   editPosition
    *       - Create a New Position
    *
    *   Params ($_POST):
    *       - name:             (String) The Position's Name
    *       - description:      (String) The Position's Description
    *
    *   Returns (JSON):
    *       Returns the [ result => ( 1 = Success, 0 = Failure ) ]
    *
    **/
    public function editPosition( $positionid ){  
        
        $data       = array_merge([
            'id'            => (int)$positionid,
            'name'          => '',
            'description'   => ''
        ],Input::all());

        $validator  = Validator::make( $data, [
            'id'                        => 'required|int|exists:positions,id',
            'name'                      => 'required|slug:positions,slug,' . (int)$positionid
        ]);

        if( $validator->fails() ){

            //Return Failure
            return [ 'result' => 0 , 'errors' => $validator->errors()->all() , 'data' => $data ];

        }else{

            //Get the Position
            $position = Position::find( $positionid );

            try {

                //Update the Positions
                $position->name         = $data['name'];
                $position->slug         = $data['slug'];
                $position->description  = @$data['description'];
                $position->save();

                //Create the Notification
                $position->notifications()->create([

                    'icon'      => 'wrench',
                    'type'      => 'Position Updated',
                    'details'   => $position->name,
                    'url'       => '/admin/positions/edit/' . $position->id
                
                ])->send([
                
                    'permissions' => [ Permission::where('slug' , 'positions.edit' )->first()->od ]
                
                ]);


                //Update the Search Criteria
                $position->search([

                    'title'       => $data['name'] ,
                    'query'       => ( !empty( $data['description'] ) ? $data['description'] : $data['name'] )

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
    *   deletePosition
    *       - Delete an Existing User
    *
    *   Params (URL):
    *       - positionid:                   (String) The Position ID
    *
    *   Returns (JSON):
    *       1. (Bool) Returns True / False
    *
    **/
    public function deletePosition( $positionid ){  

        if( $position = Position::find( $positionid ) ){

            //Delete the Search
            $position->search()->delete();

            //Clear the Notifications
            $position->notifications()->delete();

            //Send Notification
            $position->notifications()->create([

                'icon'      => 'wrench',
                'type'      => 'Position Deleted',
                'details'   => $position->name,
                'url'       => null
           
            ])->send([
           
                'permissions'   => [ Permission::where('slug' , 'positions' )->first()->id ]
           
            ]);

            //Delete the Position
            $position->delete();

            //Return Success
            return [ 'result' => 1 ];

        }

        //Return Failure
        return [ 'result' => 0 , 'errors' => [ 'That Position Doesn\'t Exist' ] ];

    }     





}
