<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Bican\Roles\Models\Permission;
use App\Models\Recipe;
use App\Models\Attachment;
use App\Models\Notification;
use App\Models\User;
use Input;
use Validator;
use Auth;

class MenuController extends Controller {









    /**
    *
    *   getAllRecipes
    *       - Loads All of the Recipes
    *
    *   URL Params:
    *       - limit:     (INT) The Page Limit (Default: 15)
    *       - page:      (INT) Pages to Load (Default: 1)
    *
    *
    *   Returns (JSON):
    *       1. The Recipe List
    *
    **/
    public function getAllRecipes( $limit = 15 , $page = 1 ){

        $query  = Recipe::where('link_type','menu');

        if( $search = Input::get('q') ){

            $query->where( 'slug' , 'like' , '%' . Str::slug( $search ) . '%' );

        }

        $data   = $query->take( $limit )->skip( ( ( $page - 1 ) * $limit ) )->get();
        $total  = $query->count();

        $ids    = $data->lists('media_id')->toArray();
        $media  = Attachment::grab( $ids , 'public' );

        foreach( $data as $index => $recipe ){

            $data[ $index ]->status_class = Str::slug( $recipe->status );
            $data[ $index ]->status_date  = strtotime( date('Y-m-d' , strtotime( $recipe->status_date ) ) . ' 23:59:59' );

            foreach( $media as $item ){

                if( $recipe['media_id'] == $item->id ){
                
                    if( $item->mime->type == 'image' && in_array( $item->mime->subtype , Attachment::$images ) ){

                        $data[ $index ]->image = Attachment::URL( Attachment::resize( $item->filename , 75 , 50 , 'public' ) );

                    }else{

                        $data[ $index ]->image = Attachment::URL( Attachment::resize( Attachment::thumbnail( $item->filename , 'public' ) , 75 , 50 , 'public' ) );

                
                    }
                
                }
            
            }
        
        };

        //Return the Data
        return [ 
            'total' => $total , 
            'data'  => $data
        ];

    }   
    













    /**
    *
    *   getRecipe
    *       - Loads a Single Recipe
    *
    *   URL Params:
    *       - id:        (INT) The Recipe ID to Load
    *
    *
    *   Returns (JSON):
    *       1. The News Articles
    *
    **/
    public function getRecipe( $id ){

        $pivots = [ 'media' , 'directions' , 'ingredients' , 'redflag' , 'serveware' , 'sides' , 'faq' ];
        $data   = Recipe::with( $pivots )->find( $id )->toArray();

        foreach( $pivots as $pivot ){
            if( $pivot != 'media' ){

                $obj = [];

                foreach( $data[ $pivot ] as $row ){

                    $obj[] = $row['pivot'];

                }

                $data[ $pivot ] = $obj;

            }
        }


        //Prepare the Media Data
        if(!empty( $data['media'] )){

            foreach( $data['media'] as $i => $file ){

                $data['media'][ $i ]['checked']         = (int)( $file['id'] == $data['media_id'] );
                $data['media'][ $i ]['name']            = $file['pivot']['name'];
                $data['media'][ $i ]['attachment_id']   = $file['id'];
                $data['media'][ $i ]['status']          = 'success';
                $data['media'][ $i ]['created_at_time'] = strtotime($file['created_at']);

                if( $file['mime']['type'] == 'image' && in_array( $file['mime']['subtype'] , Attachment::$images ) ){

                    $data['media'][ $i ]['image']       = Attachment::URL( $file['filename'] );
                    $data['media'][ $i ]['thumbnail']   = Attachment::URL( Attachment::resize( $file['filename'] , 228 , 152 , 'public' ) );
                    $data['media'][ $i ]['file']        = Attachment::URL( $file['filename'] );

                }else{

                    $data['media'][ $i ]['image']       = Attachment::URL( Attachment::thumbnail( $file['filename'] , 'public' ) );
                    $data['media'][ $i ]['thumbnail']   = Attachment::URL( Attachment::resize( Attachment::thumbnail( $file['filename'] , 'public' ) , 228 , 152 , 'public' ) );
                    $data['media'][ $i ]['file']        = Attachment::URL( $file['filename'] );

                }

                if( $file['id'] == $data['media_id'] ){

                    $data['primary_media'] = $data['media'][ $i ] ;

                }

            }

        }else
        if(!empty( $data['media_id'] )){

            $media = Attachment::grab( [ $data['media_id'] ] , 'public' )->first();

            if( $media->mime->type == 'image' && in_array( $media->mime->subtype , Attachment::$images ) ){

                $data['primary_media'] = array_merge([
                    'image'     => Attachment::URL( $media->filename ),
                    'thumbnail' => Attachment::URL( Attachment::resize( $media->filename , 228 , 152 , 'public' ) )
                ],$media->toArray());
                
            }else{

                $data['primary_media'] = array_merge([
                    'image'     => Attachment::URL( Attachment::thumbnail( $media->filename , 'public' ) ),
                    'thumbnail' => Attachment::URL( Attachment::resize( Attachment::thumbnail( $media->filename , 'public' ) , 228 , 152 , 'public' ) )
                ],$media->toArray());

            }

        }

        //Set the Status Class
        $data['status_class']   = Str::slug( $data['status'] );
        $data['status_date']    = strtotime( date('Y-m-d' , strtotime( $data['status_date'] ) ) . ' 23:59:59' );


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
    *       - Sends Feedback for a Recipe
    *
    *   URL Params:
    *       - recipeid:  (INT) The Recipe ID to Load
    *
    *
    *   Returns (JSON):
    *       1. The News Articles
    *
    **/
    public function sendFeedback( $recipeid ){

        $recipients     = [];
        $data           = array_merge([
            'id'        => $recipeid,
            'type'      => '',
            'message'   => ''
        ],Input::all());
        $validator      = Validator::make( $data, [
            'id'            => 'required|integer|exists:recipes',
            'type'          => 'required|min:1|max:100',
            'message'       => 'required|min:1',
        ]);
        if( $validator->fails() ){

            //Validator Failed, Return
            return [ 'result' => 0 , 'errors' => $validator->errors()->all() ];

        }else{

            //Get the Recipe
            $Recipe = Recipe::find( $recipeid );

            //Store the Feedback
            $Recipe->addFeedback([
                'subject'   => $Recipe['name'] . ' (' . $Recipe['type'] . ')',
                'name'      => $Recipe['name'],
                'relation'  => 'menu',
                'type'      => $data['type'],
                'message'   => $data['message']
            ]);


            return [ 'result' => 1 ];

        }

    }














    /**
    *
    *   addRecipe
    *       - Create a Menu Item
    *
    *   Params ($_PUT):
    *       - name:             (String) The Menu Item Name
    *       - description:      (String) The Menu Item Description
    *       - type:             (String) The Menu Item Type
    *       - elapsed:          (INT) The Total Cooking Time
    *       - calories:         (INT) The Total Calories
    *       - gluten:           (Bool) Does the Menu Item contain Gluten
    *       - gluten_notes:     (String) Notes Regarding what parts of the Food has Gluten
    *       - sides:            (Array) The Menu Item Sides
    *           - name:             (String) The Name of the Side
    *           - notes:            (String) Notes Regarding the Side
    *           - volume:           (INT) The Total Volume of the Side
    *           - unit:             (String) The Type of Unit for the Side Portion
    *       - redflag:          (Array) The Menu Item Red Flagged Items
    *           - name:             (String) The Red Flag Item Name
    *           - notes:            (String) Notes Regarding the Red Flag Item
    *       - ingredients:      (Array) The Menu Item Ingredients
    *           - name:             (String) The Name of the Side
    *           - notes:            (String) Notes Regarding the Side
    *           - volume:           (INT) The Total Volume of the Side
    *           - unit:             (String) The Type of Unit for the Side Portion
    *       - directions:       (Array) The Menu Item Cooking Directions
    *           - step:             (String) Cooking Instructions Broken down by Step
    *       - files:            (Array) The Media Files Uploaded
    *           - attachment_id     (INT) The Attachment ID of the Media File
    *       - primary_media:    (INT) The Primary Media Attachment ID
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
    public function addRecipe(){  

        $recipients     = [];
        $attachments    = Input::get('files');
        $data           = array_merge([
            'primary_media'      => null,
            'name'               => '',
            'description'        => '',
            'type'               => '',
            'status'             => '',
            'status_date'        => '',
            'calories'           => '',
            'gluten_free'        => '',
            'gluten_free_notes'  => '',
            'prep_time'          => '',
            'cook_time'          => '',
            'total_time'         => '',
            'directions'         => [],
            'faq'                => [],
            'files'              => [],
            'ingredients'        => [],
            'redflag'            => [],
            'sides'              => []
        ],Input::all());
        $validator      = Validator::make( $data, [
            'name'          => 'required|min:1|max:100',
            'description'   => 'required|min:1',
            'type'          => 'required',
            'status'        => 'required'
        ]);

        if( $validator->fails() ){

            //Validator Failed, Return
            return [ 'result' => 0 , 'errors' => $validator->errors()->all() ];

        }else{

            try {

                //Insert the News Article
                $recipe = Recipe::create([
                    'link_type'         => 'menu',
                    'user_id'           => Auth::user()->id,
                    'media_id'          => $data['primary_media'],
                    'name'              => $data['name'],
                    'slug'              => Str::slug( $data['name'] ),
                    'description'       => $data['description'],
                    'type'              => $data['type'],
                    'status'            => $data['status'],
                    'status_date'       => $data['status_date'],
                    'calories'          => (int)$data['calories'],
                    'gluten_free'       => (int)$data['gluten_free'],
                    'gluten_free_notes' => $data['gluten_free_notes'],
                    'prep_time'         => (int)$data['prep_time'],
                    'cook_time'         => (int)$data['cook_time'],
                    'total_time'        => ( (int)$data['prep_time'] + (int)$data['cook_time'] )
                ]);

                //Attach the Files
                if( count( $attachments ) > 0 ){

                    foreach( $attachments as $key => $file ){
                        if(!empty( $file['attachment_id'] )){

                            $attachments[ $key ] = $file['attachment_id'];

                            $recipe->attachMedia( $file['attachment_id'] , [ 'name' => $file['custom_name'] ] );
                        
                            //Set the Primary Media ID
                            if(!empty( $file['checked'] )){
                                
                                $recipe->media_id = $file['attachment_id'];
                                $recipe->save();

                            }

                        }
                    }

                }


                //Assign Directions
                if( !empty( $data['directions'] ) ){

                    foreach( $data['directions'] as $index => $direction ){

                        $recipe->attachDirection( $recipe->id , array_merge( [
                            'direction' => '',
                            'order'     => ( $index + 1 )
                        ] , $direction ) );

                    }

                }


                //Assign Ingredients
                if( !empty( $data['ingredients'] ) ){

                    foreach( $data['ingredients'] as $ingredient ){

                        $recipe->attachIngredient( $recipe->id , array_merge([ 
                            'link_type' => 'menu',
                            'name'      => '',
                            'notes'     => '',
                            'volume'    => '',
                            'unit'      => ''
                        ] , $ingredient ) );

                    }

                }


                //Assign RedFlag
                if( !empty( $data['redflag'] ) ){

                    foreach( $data['redflag'] as $redflag ){

                        $recipe->attachRedFlag( $recipe->id , array_merge( [
                            'link_type' => 'menu',
                            'name'      => '',
                            'notes'     => ''
                        ] , $redflag ) );

                    }

                }


                //Assign Serveware
                if( !empty( $data['serveware'] ) ){

                    foreach( $data['serveware'] as $serveware ){

                        $recipe->attachServeware( $recipe->id , array_merge( [
                            'link_type' => 'menu',
                            'name'      => '',
                            'notes'     => ''
                        ] , $serveware ) );

                    }

                }


                //Assign Sides
                if( !empty( $data['sides'] ) ){

                    foreach( $data['sides'] as $side ){

                        $recipe->attachSide( $recipe->id , array_merge( [
                            'link_type' => 'menu',
                            'name'      => '',
                            'notes'     => '',
                            'volume'    => '',
                            'unit'      => ''
                        ] , $side ) );

                    }

                }

                //Assign FAQ
                if( !empty( $data['faq'] ) ){

                    foreach( $data['faq'] as $question ){

                        $recipe->attachFAQ( $recipe->id , array_merge( [
                            'category'   => '',
                            'question'   => '',
                            'answer'     => ''
                        ] , $question ) );

                    }

                }


                //Create the Notification
                $recipe->notifications()->create(array(

                    'icon'      => 'dinner-o',
                    'type'      => 'New Menu item',
                    'details'   => $data['name'],
                    'url'       => '/menu/view/' . $recipe->id

                ))->send([

                    'permissions' => [ Permission::where('slug' , 'menu.view' )->first()->id ]

                ]);





                //Update the Search Criteria
                $recipe->search([

                    'title'       => $data['name'],
                    'query'       => strip_tags( $data['description'] ),
                    'url'         => '/menu/view/' . $recipe->id

                ])->assign([

                    'permissions' => [ Permission::where('slug' , 'menu.view' )->first()->id ]

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
    *   editRecipe
    *       - Edits an Existing Menu Item
    *
    *   Params ($_PUT):
    *       - name:             (String) The Menu Item Name
    *       - description:      (String) The Menu Item Description
    *       - type:             (String) The Menu Item Type
    *       - elapsed:          (INT) The Total Cooking Time
    *       - calories:         (INT) The Total Calories
    *       - gluten:           (Bool) Does the Menu Item contain Gluten
    *       - gluten_notes:     (String) Notes Regarding what parts of the Food has Gluten
    *       - sides:            (Array) The Menu Item Sides
    *           - name:             (String) The Name of the Side
    *           - notes:            (String) Notes Regarding the Side
    *           - volume:           (INT) The Total Volume of the Side
    *           - unit:             (String) The Type of Unit for the Side Portion
    *       - redflag:          (Array) The Menu Item Red Flagged Items
    *           - name:             (String) The Red Flag Item Name
    *           - notes:            (String) Notes Regarding the Red Flag Item
    *       - ingredients:      (Array) The Menu Item Ingredients
    *           - name:             (String) The Name of the Side
    *           - notes:            (String) Notes Regarding the Side
    *           - volume:           (INT) The Total Volume of the Side
    *           - unit:             (String) The Type of Unit for the Side Portion
    *       - directions:       (Array) The Menu Item Cooking Directions
    *           - step:             (String) Cooking Instructions Broken down by Step
    *       - files:            (Array) The Media Files Uploaded
    *           - attachment_id     (INT) The Attachment ID of the Media File
    *       - primary_media:    (INT) The Primary Media Attachment ID
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
    public function editRecipe( $recipeid ){  

        $recipients     = [];
        $attachments    = Input::get('files');
        $data           = array_merge([
            'id'                 => (int)$recipeid,
            'primary_media'      => null,
            'name'               => '',
            'description'        => '',
            'type'               => '',
            'status'             => '',
            'status_date'        => '',
            'calories'           => '',
            'gluten_free'        => '',
            'gluten_free_notes'  => '',
            'prep_time'          => '',
            'cook_time'          => '',
            'total_time'         => '',
            'directions'         => [],
            'faq'                => [],
            'files'              => [],
            'ingredients'        => [],
            'redflag'            => [],
            'sides'              => []
        ],Input::all());
        $validator      = Validator::make( $data, [
            'id'            => 'required|integer|exists:recipes',
            'name'          => 'required|min:1|max:100',
            'description'   => 'required|min:1',
            'type'          => 'required',
            'status'        => 'required'
        ]);

        if( $validator->fails() ){

            //Validator Failed, Return
            return [ 'result' => 0 , 'errors' => $validator->errors()->all() ];

        }else{

            //Get the Recipe
            $recipe = Recipe::find( $recipeid );

            try {

                //Update the Recipe
                $recipe->name               = $data['name'];
                $recipe->description        = $data['description'];
                $recipe->slug               = Str::slug( $data['name'] );
                $recipe->type               = $data['type'];
                $recipe->status             = $data['status'];
                $recipe->status_date        = $data['status_date'];
                $recipe->calories           = $data['calories'];
                $recipe->gluten_free        = $data['gluten_free'];
                $recipe->gluten_free_notes  = $data['gluten_free_notes'];
                $recipe->prep_time          = $data['prep_time'];
                $recipe->cook_time          = $data['cook_time'];
                $recipe->total_time         = ( (int)$data['prep_time'] + (int)$data['cook_time'] );
                $recipe->save();




                $recipe->detachAllMedia();

                //Attach the Files
                if( count( $attachments ) > 0 ){

                    foreach( $attachments as $key => $file ){
                        if(!empty( $file['attachment_id'] )){

                            $attachments[ $key ] = $file['attachment_id'];

                            $recipe->attachMedia( $file['attachment_id'] , [ 'name' => $file['custom_name'] ] );
                        
                            //Set the Primary Media ID
                            if(!empty( $file['checked'] )){
                                
                                $recipe->media_id = $file['attachment_id'];
                                $recipe->save();

                            }

                        }
                    }

                }


                $recipe->detachAllDirections();

                //Assign Directions
                if( !empty( $data['directions'] ) ){

                    foreach( $data['directions'] as $index => $direction ){

                        $recipe->attachDirection( $recipe->id , array_merge( [
                            'direction' => '',
                            'order'     => ( $index + 1 )
                        ] , $direction ) );

                    }

                }


                $recipe->detachAllIngredients();

                //Assign Ingredients
                if( !empty( $data['ingredients'] ) ){

                    foreach( $data['ingredients'] as $ingredient ){

                        $recipe->attachIngredient( $recipe->id , array_merge([ 
                            'link_type' => 'menu',
                            'name'      => '',
                            'notes'     => '',
                            'volume'    => '',
                            'unit'      => ''
                        ] , $ingredient ) );

                    }

                }


                $recipe->detachAllRedFlag();

                //Assign RedFlag
                if( !empty( $data['redflag'] ) ){

                    foreach( $data['redflag'] as $redflag ){

                        $recipe->attachRedFlag( $recipe->id , array_merge( [
                            'link_type' => 'menu',
                            'name'      => '',
                            'notes'     => ''
                        ] , $redflag ) );

                    }

                }


                $recipe->detachAllServeware();

                //Assign Serveware
                if( !empty( $data['serveware'] ) ){

                    foreach( $data['serveware'] as $serveware ){

                        $recipe->attachServeware( $recipe->id , array_merge( [
                            'link_type' => 'menu',
                            'name'      => '',
                            'notes'     => ''
                        ] , $serveware ) );

                    }

                }


                $recipe->detachAllSides();

                //Assign Sides
                if( !empty( $data['sides'] ) ){

                    foreach( $data['sides'] as $side ){

                        $recipe->attachSide( $recipe->id , array_merge( [
                            'link_type' => 'menu',
                            'name'      => '',
                            'notes'     => '',
                            'volume'    => '',
                            'unit'      => ''
                        ] , $side ) );

                    }

                }


                $recipe->detachAllFAQ();

                //Assign FAQ
                if( !empty( $data['faq'] ) ){

                    foreach( $data['faq'] as $question ){

                        $recipe->attachFAQ( $recipe->id , array_merge( [
                            'category'   => '',
                            'question'   => '',
                            'answer'     => ''
                        ] , $question ) );

                    }

                }


                //Create the Notification
                $recipe->notifications()->create(array(

                    'icon'      => 'dinner-o',
                    'type'      => 'Menu Item Updated',
                    'details'   => $recipe->name,
                    'url'       => '/menu/view/' . $recipeid

                ))->send([

                    'permissions' => [ Permission::where('slug' , 'menu.view' )->first()->id ]

                ]);





                //Update the Search Criteria
                $recipe->search([

                    'title'       => $data['name'],
                    'query'       => strip_tags( $data['description'] )

                ])->assign([

                    'permissions' => [ Permission::where('slug' , 'menu.view' )->first()->id ]

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
    *   deleteRecipe
    *       - Delete an Existing Recipe
    *
    *   Params (URL):
    *       - recipeid:                   (INT) The Recipe ID
    *
    *   Returns (JSON):
    *       1. (Bool) Returns True / False
    *
    **/
    public function deleteRecipe( $recipeid ){  


        if( $recipe = Recipe::find( $recipeid ) ){

            //Delete the Search
            $recipe->search()->delete();

            //Clear the Notifications
            $recipe->notifications()->delete();

            //Create the Notification
            $recipe->notifications()->create([

                'icon'      => 'dinner-o',
                'type'      => 'Menu Item Deleted',
                'details'   => $recipe->name,
                'url'       => null
           
            ])->send([
         
                'permissions' => [ Permission::where('slug' , 'menu.delete' )->first()->id ],
          
            ]);

            //Delete the Store
            $recipe->delete();

            //Return Success
            return [ 'result' => 1 ];

        }

        //Return Failed
        return [ 'result' => 0 , 'errors' => [ 'That Article Doesn\'t exist' ] ];

    }    



}
