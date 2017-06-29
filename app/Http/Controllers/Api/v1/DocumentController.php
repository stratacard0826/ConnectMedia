<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Bican\Roles\Models\Permission;
use App\Models\Document;
use App\Models\Attachment;
use App\Models\Notification;
use App\Models\User;
use Input;
use Validator;
use Auth;

class DocumentController extends Controller {



    /**
    *
    *   getPagedFiles
    *       - Loads All of the Files
    *
    *   URL Params:
    *       - limit:     (INT) The Page Limit (Default: 15)
    *       - page:      (INT) Pages to Load (Default: 1)
    *
    *
    *   Returns (JSON):
    *       1. The Promo List
    *
    **/
    public function getPagedFiles( $limit = 15 , $page = 1 ){

        $query  = Document::with('files');

        if( $search = Input::get('q') ){

            $query->where(function($query) use ($search){ 

                $query->where('slug' , 'like' , '%' . Str::slug( $search ) . '%');

                $query->orWhere( 'id' , $search );

            });

        }

        if( $document = Input::get('document') ){

            $query->whereHas( 'files' , function($query) use ($document){

                $query->whereHas( 'mime' , function($query) use ($document){

                    $mimes = explode( ',' , $document );

                    foreach( $mimes as $mime ){

                        $query->where( 'mime' , 'LIKE' , '%' . $mime . '%' );

                    }

                } );

            } );

        }

        if( $sort = Input::get('sort') ){

            $query->orderBy( $sort , 'DESC' );

        }


        $data   = $query->take( $limit )->skip( ( ( $page - 1 ) * $limit ) )->get();
        $total  = $query->count();


        foreach( $data as $index => $file ){

            $item = $file->files;

            $data[ $index ]->created_at_time    = strtotime( $data[ $index ]->created_at );
            $data[ $index ]->filetype           = $item->mime->name;
            $data[ $index ]->mime               = $item->mime;
            $data[ $index ]->slug               = $item->slug;

            if( $item->mime->type == 'image' && in_array( $item->mime->subtype , Attachment::$images ) ){

                $data[ $index ]->image = Attachment::URL( Attachment::resize( $item->filename , 155 , 152 , 'public' ) );

            }else{

                $thumbnail = Attachment::thumbnail( $item->filename , 'public' );

                if(file_exists( Attachment::path( $thumbnail , 'public' ) )){

                    $data[ $index ]->image = Attachment::URL( Attachment::resize( $thumbnail , 155 , 152 , 'public' ) );

                }
        
            }
        
        
        }
    

        //Return the Data
        return [ 
            'total' => $total , 
            'data'  => $data
        ];

    }   
  








    /**
    *
    *   getAllFiles
    *       - Loads All of the Files
    *
    *   Returns (JSON):
    *       1. The Promo List
    *
    **/
    public function getAllFiles(){    

        $documents = Document::with('files')->get()->toArray();

        foreach( $documents as $index => $document ){

            $documents[ $index ]                = array_merge( $document , $document['files'] );
            $documents[ $index ]['custom_name'] = $document['name'];
            $documents[ $index ]['status']      = 'success';

            unset( $documents['files'] );

        }

        //Return the Data
        return [ 
            'data'  => $documents
        ];

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
    *   editPromo
    *       - Edits an Existing Menu Item
    *
    *   Params ($_POST):
    *       - files:            (Array) The Media Files Uploaded
    *           - attachment_id     (INT) The Attachment ID of the Media File
    *           - custom_name       (String) The Customizable File Name
    *           
    *
    *   Returns (JSON):
    *       Returns the [ result => ( 1 = Success, 0 = Failure ) ]
    *
    **/
    public function manageFiles(){  

        $attachments    = Input::get('files');
        $ids            = [];

        //Attach the Files
        if( count( $attachments ) > 0 ){

            foreach( $attachments as $key => $file ){
                if(!empty( $file['attachment_id'] )){

                    $document = Document::where( 'attachment_id' , $file['attachment_id'] )->get()->first();

                    if( !$document ){

                        $document = Document::create([
                            'attachment_id'     => $file['attachment_id'],
                            'slug'              => Str::slug( $file['custom_name'] ),
                            'name'              => $file['custom_name']
                        ]);

                        //Create the Notification
                        $document->notifications()->create(array(

                            'icon'      => 'folder',
                            'type'      => 'Document Added',
                            'details'   => $document->name,
                            'url'       => '/documents?q=' . $document->id

                        ))->send([

                            'permissions' => [ Permission::where('slug' , 'documents' )->first()->id ]

                        ]);

                        //Update the Search Criteria
                        $document->search([

                            'title'       => $document->name,
                            'query'       => strip_tags( $document->name )

                        ])->assign([

                            'permissions' => [ Permission::where('slug' , 'documents' )->first()->id ]

                        ]);

                    }else
                    if( $document->name != $file['custom_name'] ){

                        //Save the Document
                        $document->name = $file['custom_name'];
                        $document->slug = Str::slug( $file['custom_name'] );
                        $document->save();

                        //Create the Notification
                        $document->notifications()->create(array(

                            'icon'      => 'folder',
                            'type'      => 'Document Updated',
                            'details'   => $document->name,
                            'url'       => '/documents?q=' . $document->id

                        ))->send([

                            'permissions' => [ Permission::where('slug' , 'documents' )->first()->id ]

                        ]);

                        //Update the Search Criteria
                        $document->search([

                            'title'       => $document->name,
                            'query'       => strip_tags( $document->name )

                        ])->assign([

                            'permissions' => [ Permission::where('slug' , 'documents' )->first()->id ]

                        ]);


                    }

                    $ids[] = $document->id;

                }
            }

        }


        //Get all the ID's
        $deleted = Document::whereNotIn( 'id' , $ids )->get();

        //Loop trhough and remove the deleted documents
        foreach( $deleted as $file ){

            //Delete the Search
            $file->search()->delete();

            //Clear the Notifications
            $file->notifications()->delete();

            //Create the Notification
            $file->notifications()->create([

                'icon'      => 'folder',
                'type'      => 'Document Deleted',
                'details'   => $file->name,
                'url'       => null
           
            ])->send([
         
                'permissions' => [ Permission::where('slug' , 'documents' )->first()->id ],
          
            ]);

            //Delete the Store
            $file->delete();

        }


        //Return Success
        return [ 'result' => 1 ];



    } 



}
