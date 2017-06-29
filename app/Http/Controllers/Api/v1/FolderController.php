<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Bican\Roles\Models\Permission;
use App\Models\Notification;
use App\Models\Folder;
use App\User;
use DB;
use Input;
use Validator;

class FolderController extends Controller {




    /**
    *
    *   getFolder
    *       - Loads All of the Folders
    *
    *   URL Params:
    *       - folderid:       (INT) The Folder ID to Lookup
    *
    *
    *   Returns (JSON):
    *       1. The Folder Data
    *
    **/
    public function getFolder( $folderid = 1 ){

        return Folder::find( $folderid ) ;

    }   
    










    /**
    *
    *   getAllFolders
    *       - Loads All of the Folders
    *
    *   URL Params:
    *       - limit:     The Page Limit (Default: 15)
    *       - page:      Pages to Load (Default: null)
    *
    * 	GET Params:
    * 		- children:  Returns Folders of the Child ID
    *
    *
    *   Returns (JSON):
    *       1. The User Looked up (If $userid was passed)
    *       2. The Current User Session Data (If $userid was not passed)
    *       3. Null
    *
    **/
    public function getAllFolders( $limit = 15 , $page = null ){

    	$query = Folder::where(function($query){

    		if( Input::exists('children') ){

    			if( $folder = Input::get('children') ){

	    			$query->where( 'parent_id' , Input::get('children') );

	    		}else{

	    			$query->whereNull( 'parent_id' );

	    		}

    		}

    	});


        if( $page ){

            $data = [ 
                'total' => $query->count() , 
                'data'  => $query->take( $limit )->skip( ( ( $page - 1 ) * $limit ) )->get()->toArray()
            ];

        }else{

            $data = [
                'result' => 1,
                'data'   => $query->select(['id','parent_id', 'name', 'description'])->get()->toArray()
            ];

        }

        $obj 	= [];
        $loop 	= 0;
       
        while( count($data['data']) > 0 ){

        	foreach( $data['data'] as $key => $val ){

        		if(empty( $val['parent_id'] ) || ( Input::get('children') == $val['parent_id'] )){

        			$obj[ $val['id'] ] = $val;

        			unset( $data['data'][ $key ] );

        		}else{

        			$recursive = function( &$arr ) use ( &$data , $key , $val , &$recursive){
        				foreach( $arr as $id => &$item ){

        					if(isset( $item['children'] )){

        						$recursive( $item['children'] );

        					}

        					if( $item['id'] == $val['parent_id'] ){

        						if(!isset( $item['children'] )) $item['children'] = [];

        						$item['children'][] = $val ;

        						unset( $data['data'][ $key ] );

        					}

        					unset( $item );

        				}
        			};

        			$recursive( $obj );


        		}

        	}

    		$loop++;
    		if( $loop > 50 ) break;

        }

        return array_merge( $data , [ 'data' => $obj ]);

    }    










    /**
    *
    *   findFolder
    *       - Loads the Folder based on the folder name
    *
    *   Params ($_GET):
    *       - name:            (String) The Folder Name
    *       - folderid:           (INT) Exclude the User ID from the Search
    *
    *   Returns (JSON):
    *       Returns the User data or null
    *
    **/
    public function findFolder(){        

        $name       	= Input::get( 'name' , null );
        $folderid     = Input::get( 'folderid' , null );

        if( $name ){

            $query = Folder::query();

            if( $name ) $query->where( 'slug' , '=' , Str::slug( $name , '.' ) );

            if( $folderid ) $query->where( 'id' , '!=' , $folderid );

            return $query->first();

        }

        return Response::make( array( 'result' => 0 , 'error' => 'Folder Name must be passed as a GET variable' , 'code' => 'invalid-request' ) , 404 );

    }












    /**
    *
    *   addFolder
    *       - Create a New Folder
    *
    *   Params ($_PUT):
    *       - name:             (String) The Folder's Name
    *       - description:      (String) The Folder's Description
    *
    *   Returns (JSON):
    *       Returns the [ result => ( 1 = Success, 0 = Failure ) ]
    *
    **/
    public function addFolder(){  

        $data           = Input::all();
        $validator      = Validator::make( $data, [
            'name' => 'required|slug:document_folders,slug'
        ]);

        if( $validator->fails() ){

            //Return Failure
            return [ 'result' => 0 , 'errors' => $validator->errors()->all() ];

        }else{

            try {

            	//Preset the Data Depth
            	$data['depth'] = 0;

            	//Get the Folder Depth
            	if(!empty( $data['parent_id'] )){

            		$data['depth'] = Folder::find( $data['parent_id'] )->depth + 1;

            	}
    
                //Create the new Folder
                $folder = Folder::create([
                	'parent_id' 		=> @$data['parent_id'],
                	'name' 				=> $data['name'],
                    'slug'  			=> Str::slug( $data['name'] , '.' ),
                    'depth' 			=> $data['depth'],
                    'description' 		=> $data['description']
                ]);



                //Create the Notification
                $folder->notifications()->create([

                    'icon'      => 'folder',
                    'type'      => 'New Document Folder',
                    'details'   => $folder->name,
                    'url'       => '/documents/folders/edit/' . $folder->id
              
                ])->send([
            
                    'permissions' => [ Permission::where('slug' , 'folders.edit' )->first()->id ]
            
                ]);


                //Update the Search Criteria
                $folder->search([

                    'title'     => $data['name'] ,
                    'query'     => ( !empty( $data['description'] ) ? $data['description'] : $data['name'] ),
                    'url'       => '/documents/folders/edit/' . $folder->id

                ])->assign([

                    'permissions' => [ Permission::where('slug' , 'folders.edit' )->first()->id ],

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
    *   editFolder
    *       - Create a New Folder
    *
    *   Params ($_POST):
    *       - name:             (String) The Folder's Name
    *       - description:      (String) The Folder's Description
    *
    *   Returns (JSON):
    *       Returns the [ result => ( 1 = Success, 0 = Failure ) ]
    *
    **/
    public function editFolder( $folderid ){  
        
        $data       = array_merge([
            'id'            => (int)$folderid,
            'name'          => '',
            'description'   => ''
        ],Input::all());

        $validator  = Validator::make( $data, [
            'id'                        => 'required|int|exists:document_folders,id',
            'name'                      => 'required|slug:document_folders,slug,' . (int)$folderid
        ]);

        if( $validator->fails() ){

            //Return Failure
            return [ 'result' => 0 , 'errors' => $validator->errors()->all() , 'data' => $data ];

        }else{

            //Get the Folder
            $folder = Folder::find( $folderid );

            try {

            	if( $data['parent_id'] != $folderid ){
	            
	                $folder->parent_id = $data['parent_id'];

	            }


                //Update the Folders
                $folder->name         = $data['name'];
                $folder->slug         = Str::slug( $data['name'] , '.' );
                $folder->description  = @$data['description'];
                $folder->save();

                //Create the Notification
                $folder->notifications()->create([

                    'icon'      => 'folder',
                    'type'      => 'Document Folder Updated',
                    'details'   => $folder->name,
                    'url'       => '/documents/folders/edit/' . $folder->id
                
                ])->send([
                
                    'permissions' => [ Permission::where('slug' , 'folders.edit' )->first()->id ]
                
                ]);


                //Update the Search Criteria
                $folder->search([

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
    *   deleteFolder
    *       - Delete an Existing User
    *
    *   Params (URL):
    *       - folderid:                   (String) The Folder ID
    *
    *   Returns (JSON):
    *       1. (Bool) Returns True / False
    *
    **/
    public function deleteFolder( $folderid ){  

        if( $folder = Folder::find( $folderid ) ){

            //Delete the Search
            $folder->search()->delete();

            //Clear the Notifications
            $folder->notifications()->delete();

            //Send Notification
            $folder->notifications()->create([

                'icon'      => 'folder',
                'type'      => 'Folder Deleted',
                'details'   => $folder->name,
                'url'       => null
           
            ])->send([
           
                'permissions'   => [ Permission::where('slug' , 'folders' )->first()->id ]
           
            ]);

            //Delete the Folder
            $folder->delete();

            //Return Success
            return [ 'result' => 1 ];

        }

        //Return Failure
        return [ 'result' => 0 , 'errors' => [ 'That Folder Doesn\'t Exist' ] ];

    }     





    
}
