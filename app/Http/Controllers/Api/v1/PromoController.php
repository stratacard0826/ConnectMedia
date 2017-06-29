<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Bican\Roles\Models\Permission;
use App\Models\Promo;
use App\Models\Attachment;
use App\Models\Notification;
use App\Models\User;
use Input;
use Validator;
use Auth;

class PromoController extends Controller {
  








    /**
    *
    *   getAllPromos
    *       - Loads All of the Promos
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
    public function getAllPromos( $limit = 15 , $page = 1 ){

        $query  = Promo::with('files');

        if( $search = Input::get('q') ){

            $query->where( 'slug' , 'like' , '%' . Str::slug( $search ) . '%' );

        }

        if( $document = Input::get('document') ){

            $query->whereHas( 'files' , function($query) use ($document){

                $query->where('category' , $document );

            } );

        }

        if( $sort = Input::get('sort') ){

            $query->orderBy( $sort , 'DESC' );

        }

        $data   = $query->take( $limit )->skip( ( ( $page - 1 ) * $limit ) )->get();
        $total  = $query->count();

        $ids    = $data->lists('attachment_id')->toArray();
        $files  = Attachment::grab( $ids , 'public' );


        foreach( $data as $index => $promotion ){

            $list                   = [];

            foreach( $promotion->files as $file ){
                if(!empty( $file->pivot->category )){

                    $list[ $file->pivot->category ] = true;

                }
            }

            $data[ $index ]->documents = array_keys( $list );

            foreach( $files as $item ){

                if( $promotion['attachment_id'] == $item->id ){
                
                    if( $item->mime->type == 'image' && in_array( $item->mime->subtype , Attachment::$images ) ){

                        $data[ $index ]->image = Attachment::URL( Attachment::resize( $item->filename , 155 , 152 , 'public' ) );

                    }else{

                        $thumbnail = Attachment::thumbnail( $item->filename , 'public' );

                        if(file_exists( Attachment::path( $thumbnail , 'public' ) )){

                            $data[ $index ]->image = Attachment::URL( Attachment::resize( $thumbnail , 155 , 152 , 'public' ) );

                        }
                
                    }
                
                }
            
            }

            $this->getStatus( $data[ $index ] );
        
        };

        //Return the Data
        return [ 
            'total' => $total , 
            'data'  => $data
        ];

    }   
    













    /**
    *
    *   getPromo
    *       - Loads a Single Promo
    *
    *   URL Params:
    *       - id:        (INT) The Promo ID to Load
    *
    *
    *   Returns (JSON):
    *       1. The News Articles
    *
    **/
    public function getPromo( $id ){

        $data   = Promo::with([ 'files', 'faq' ])->find( $id )->toArray();


        //Prepare the Media Data
        if(!empty( $data['files'] )){

            foreach( $data['files'] as $i => $file ){

                $data['files'][ $i ]['checked']         = (int)( $file['id'] == $data['attachment_id'] );
                $data['files'][ $i ]['name']            = $file['pivot']['name'];
                $data['files'][ $i ]['attachment_id']   = $file['id'];
                $data['files'][ $i ]['category']        = $file['pivot']['category'];
                $data['files'][ $i ]['status']          = 'success';
                $data['files'][ $i ]['created_at_time'] = strtotime($file['created_at']);
                $data['files'][ $i ]['updated_at_time'] = strtotime($file['updated_at']);

                if( $file['mime']['type'] == 'image' && in_array( $file['mime']['subtype'] , Attachment::$images ) ){

                    $data['files'][ $i ]['image']       = Attachment::URL( $file['filename'] );
                    $data['files'][ $i ]['thumbnail']   = Attachment::URL( Attachment::resize( $file['filename'] , 155 , 152 , 'public' ) );
                    $data['files'][ $i ]['file']        = Attachment::URL( $file['filename'] );

                }else
                if(file_exists( Attachment::path( Attachment::thumbnail( $file['filename'], 'public' ), 'public' ) )){

                    $data['files'][ $i ]['image']       = Attachment::URL( Attachment::thumbnail( $file['filename'] , 'public' ) );
                    $data['files'][ $i ]['thumbnail']   = Attachment::URL( Attachment::resize( Attachment::thumbnail( $file['filename'] , 'public' ) , 155 , 152 , 'public' ) );
                    $data['files'][ $i ]['file']        = Attachment::URL( $file['filename'] );

                }

                if( $file['id'] == $data['attachment_id'] ){

                    $data['attachment_id'] = $data['files'][ $i ] ;

                }

            }

            foreach( $data['faq'] as $index => $pivot ){

                $data['faq'][ $index ] = $pivot['pivot'];

            }

        }else
        if(!empty( $data['attachment_id'] )){

            $file = Attachment::grab( [ $data['attachment_id'] ] , 'public' )->first();

            if( $file->mime->type == 'image' && in_array( $file->mime->subtype , Attachment::$images ) ){

                $data['attachment_id'] = array_merge([
                    'image'     => Attachment::URL( $file->filename ),
                    'thumbnail' => Attachment::URL( Attachment::resize( $file->filename , 275 , 270 , 'public' ) )
                ],$file->toArray());

            }else
            if(file_exists( Attachment::path( Attachment::thumbnail( $file['filename'], 'public' ), 'public' ) )){

                $data['attachment_id'] = array_merge([
                    'image'     => Attachment::URL( Attachment::thumbnail( $file->filename , 'public' ) ),
                    'thumbnail' => Attachment::URL( Attachment::resize( Attachment::thumbnail( $file->filename , 'public' ) , 275 , 270 , 'public' ) )
                ],$file->toArray());

            }

        }

        //Get the Status Details
        $data = (object)$data;
        $this->getStatus( $data );


        //Load the Article Data
        return [
            'result' => 1,
            'data'   => $data
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
    *   sendFeedback
    *       - Sends Feedback for a Promo
    *
    *   URL Params:
    *       - recipeid:  (INT) The Promo ID to Load
    *
    *
    *   Returns (JSON):
    *       1. The News Articles
    *
    **/
    public function sendFeedback( $promoid ){

        $recipients     = [];
        $data           = array_merge([
            'id'        => $promoid,
            'type'      => '',
            'feedback'  => ''
        ],Input::all());
        $validator      = Validator::make( $data, [
            'id'            => 'required|integer|exists:promos',
            'type'          => 'required|min:1|max:100',
            'message'       => 'required|min:1',
        ]);
        if( $validator->fails() ){

            //Validator Failed, Return
            return [ 'result' => 0 , 'errors' => $validator->errors()->all() ];

        }else{

            //Get the Promo
            $Promo = Promo::find( $promoid );

            $Promo->addFeedback([
                'subject'   => $Promo['name'],
                'name'      => $Promo['name'],
                'type'      => $data['type'],
                'message'   => $data['feedback']
            ]);

            return [ 'result' => 1 ];

        }

    }














    /**
    *
    *   addPromo
    *       - Create a Menu Item
    *
    *   Params ($_PUT):
    *       - name:             (String) The Menu Item Name
    *       - description:      (String) The Menu Item Description
    *       - date:             (String) The Menu Item Type
    *       - files:            (Array) The Media Files Uploaded
    *           - attachment_id     (INT) The Attachment ID of the Media File
    *           - custom_name       (String) The Customizable File Name
    *           - category          (String) The File Category
    *       - attachment_id:    (INT) The Primary Media Attachment ID
    *       - faq:              (Array) The Menu Item FAQ
    *           - category:         (String) The FAQ Item Category
    *           - question:         (String) The FAQ Item Question
    *           - answer:           (String) The FAQ Item Answer
    *           
    *
    *   Returns (JSON):
    *       Returns the [ result => ( 1 = Success, 0 = Failure ) ]
    *
    **/
    public function addPromo(){  

        $recipients     = [];
        $attachments    = Input::get('files');
        $data           = array_merge([
            'user_id'            => '',
            'attachment_id'      => null,
            'name'               => '',
            'slug'               => '',
            'description'        => '',
            'start'              => '',
            'end'                => '',
            'faq'                => [],
            'files'              => []
        ],Input::all());
        $validator      = Validator::make( $data, [
            'name'          => 'required|min:1|max:100',
            'description'   => 'required|min:1',
            'start'         => 'required|before:' . Input::get('end'),
            'end'           => 'required|after:' . Input::get('start'),
        ]);

        if( $validator->fails() ){

            //Validator Failed, Return
            return [ 'result' => 0 , 'errors' => $validator->errors()->all() ];

        }else{

            try {

                //Insert the News Article
                $promo = Promo::create([
                    'user_id'           => Auth::user()->id,
                    'attachment_id'     => $data['attachment_id'],
                    'name'              => $data['name'],
                    'slug'              => Str::slug( $data['name'] ),
                    'description'       => $data['description'],
                    'start'             => $data['start'],
                    'end'               => $data['end']
                ]);

                //Attach the Files
                if( count( $attachments ) > 0 ){

                    foreach( $attachments as $key => $file ){
                        if(!empty( $file['attachment_id'] )){

                            $attachments[ $key ] = $file['attachment_id'];

                            $promo->attachFile( $file['attachment_id'] , [ 'name' => $file['custom_name'], 'category' => ( !empty( $file['category'] ) ? $file['category'] : 'Print' ) ] );
                        
                            //Set the Primary Media ID
                            if(!empty( $file['checked'] )){
                                
                                $promo->attachment_id = $file['attachment_id'];
                                $promo->save();

                            }

                        }
                    }

                }


                //Assign FAQ
                if( !empty( $data['faq'] ) ){

                    foreach( $data['faq'] as $question ){

                        $promo->attachFAQ( $promo->id , array_merge( [
                            'category'   => '',
                            'question'   => '',
                            'answer'     => ''
                        ] , $question ) );

                    }

                }


                //Create the Notification
                $promo->notifications()->create(array(

                    'icon'      => 'bar-chart',
                    'type'      => 'New Promotion',
                    'details'   => $data['name'],
                    'url'       => '/promo/view/' . $promo->id

                ))->send([

                    'permissions' => [ Permission::where('slug' , 'promos.view' )->first()->id ]

                ]);





                //Update the Search Criteria
                $promo->search([

                    'title'       => $data['name'],
                    'query'       => strip_tags( $data['description'] ),
                    'url'         => '/promo/view/' . $promo->id

                ])->assign([

                    'permissions' => [ Permission::where('slug' , 'promos.view' )->first()->id ]

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
    *   editPromo
    *       - Edits an Existing Menu Item
    *
    *   Params ($_POST):
    *       - name:             (String) The Menu Item Name
    *       - description:      (String) The Menu Item Description
    *       - date:             (String) The Menu Item Type
    *       - files:            (Array) The Media Files Uploaded
    *           - attachment_id     (INT) The Attachment ID of the Media File
    *           - custom_name       (String) The Customizable File Name
    *           - category          (String) The File Category
    *       - attachment_id:    (INT) The Primary Media Attachment ID
    *       - faq:              (Array) The Menu Item FAQ
    *           - category:         (String) The FAQ Item Category
    *           - question:         (String) The FAQ Item Question
    *           - answer:           (String) The FAQ Item Answer
    *           
    *
    *   Returns (JSON):
    *       Returns the [ result => ( 1 = Success, 0 = Failure ) ]
    *
    **/
    public function editPromo( $promoid ){  

        $recipients     = [];
        $attachments    = Input::get('files');
        $data           = array_merge([
            'user_id'            => '',
            'attachment_id'      => null,
            'name'               => '',
            'slug'               => '',
            'description'        => '',
            'start'              => '',
            'end'                => '',
            'faq'                => [],
            'files'              => []
        ],Input::all());
        $validator      = Validator::make( $data, [
            'name'          => 'required|min:1|max:100',
            'description'   => 'required|min:1',
            'start'         => 'required|before:' . Input::get('end'),
            'end'           => 'required|after:' . Input::get('start'),
        ]);

        if( $validator->fails() ){

            //Validator Failed, Return
            return [ 'result' => 0 , 'errors' => $validator->errors()->all() ];

        }else{

            //Get the Promo
            $promo = Promo::find( $promoid );

            try {

                //Update the Promo
                $promo->name               = $data['name'];
                $promo->description        = $data['description'];
                $promo->slug               = Str::slug( $data['name'] );
                $promo->start              = $data['start'];
                $promo->end                = $data['end'];
                $promo->save();


                $promo->detachAllFiles();

                //Attach the Files
                if( count( $attachments ) > 0 ){

                    foreach( $attachments as $key => $file ){

                        $attachments[ $key ] = $file['attachment_id'];

                        $promo->attachFile( $file['attachment_id'] , [ 'name' => $file['custom_name'], 'category' => ( !empty( $file['category'] ) ? $file['category'] : 'Print' ) ] );
                    
                        //Set the Primary Media ID
                        if(!empty( $file['checked'] )){
                            
                            $promo->attachment_id = $file['attachment_id'];
                            $promo->save();

                        }

                    }

                }


                $promo->detachAllFAQ();

                //Assign FAQ
                if( !empty( $data['faq'] ) ){

                    foreach( $data['faq'] as $question ){

                        $promo->attachFAQ( $promo->id , array_merge( [
                            'category'   => '',
                            'question'   => '',
                            'answer'     => ''
                        ] , $question ) );

                    }

                }


                //Create the Notification
                $promo->notifications()->create(array(

                    'icon'      => 'bar-chart',
                    'type'      => 'Promotion Updated',
                    'details'   => $promo->name,
                    'url'       => '/promos/view/' . $promoid

                ))->send([

                    'permissions' => [ Permission::where('slug' , 'promos.view' )->first()->id ]

                ]);





                //Update the Search Criteria
                $promo->search([

                    'title'       => $data['name'],
                    'query'       => strip_tags( $data['description'] )

                ])->assign([

                    'permissions' => [ Permission::where('slug' , 'promos.view' )->first()->id ]

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
    *   deletePromo
    *       - Delete an Existing Promo
    *
    *   Params (URL):
    *       - recipeid:                   (INT) The Promo ID
    *
    *   Returns (JSON):
    *       1. (Bool) Returns True / False
    *
    **/
    public function deletePromo( $promoid ){  


        if( $promo = Promo::find( $promoid ) ){

            //Delete the Search
            $promo->search()->delete();

            //Delete the Notification
            $promo->notifications()->delete();

            //Create the Notification
            $promo->notifications()->create([

                'icon'      => 'bar-chart',
                'type'      => 'Menu Item Deleted',
                'details'   => $promo->name,
                'url'       => null
           
            ])->send([
         
                'permissions' => [ Permission::where('slug' , 'promos.delete' )->first()->id ],
          
            ]);

            //Delete the Store
            $promo->delete();

            //Return Success
            return [ 'result' => 1 ];

        }

        //Return Failed
        return [ 'result' => 0 , 'errors' => [ 'That Article Doesn\'t exist' ] ];

    }   











    /**
    *
    *   getStatus
    *       - Get the Current Status of a Promotion
    *
    *   Params (URL):
    *       - $promotion:   (Object) The Promotion Object
    *           - start:        (DateTime) The Start Date of the Promotion
    *           - end:          (DateTime) The End Date of the Promotion
    *
    *   Returns (JSON):
    *       1. (Bool) Returns True / False
    *
    **/
    private function getStatus( &$promotion ){  

        $promotion->start_time    = strtotime( date('Y-m-d' , strtotime( $promotion->start ) ) . ' 23:59:59' );
        $promotion->end_time      = strtotime( date('Y-m-d' , strtotime( $promotion->end ) ) . ' 23:59:59' );
        $times                    = (object)[
            'phasein'      => strtotime( $promotion->start_time  . ' - 1 week' ),
            'phaseout'     => strtotime( $promotion->end_time    . ' + 1 week' )
        ];


        if( time() > $times->phasein && $promotion->start_time > time() ){

            $promotion->status_class   = 'phase-in';
            $promotion->status         = 'Coming Soon';

        }else
        if( time() > $times->phaseout && $promotion->end_time > time() ){

            $promotion->status_class   = 'phase-out';
            $promotion->status         = 'Ending Soon';

        }else
        if( $promotion->start_time < time() && $promotion->end_time > time() ){

            $promotion->status_class   = 'active';
            $promotion->status         = 'Current';

        }else
        if( $promotion->end_time < time() ){

            $promotion->status_class   = 'concluded';
            $promotion->status         = 'Concluded';

        }else{

            $promotion->status_class   = 'upcoming';
            $promotion->status         = 'Upcoming';

        }

        return $promotion;

    }


}
