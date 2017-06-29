<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Bican\Roles\Models\Permission;
use App\Models\Notification;
use App\Models\Promotion;
use Input;
use Validator;

class PromotionController extends Controller {




    /**
    *
    *   getPromotion
    *       - Loads All of the Promotions
    *
    *   URL Params:
    *       - promotionid:       (INT) The Promotion ID to Lookup
    *
    *
    *   Returns (JSON):
    *       1. The Promotion Data
    *
    **/
    public function getPromotion( $promotionid = 1 ){

        return [
            'result' => 1,
            'data'   => Promotion::find( $promotionid )
        ];

    }   
    










    /**
    *
    *   getAllPromotions
    *       - Loads All of the Promotions
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
    public function getAllPromotions( $limit = 15 , $page = null ){
        if( $page ){

            return [ 
                'result'    => 1,
                'total'     => Promotion::count() , 
                'data'      => Promotion::take( $limit )->skip( ( ( $page - 1 ) * $limit ) )->get()
            ];

        }else{

            return [
                'result' => 1,
                'data'   => Promotion::all(['id','name'])
            ];

        }
    }      










    /**
    *
    *   findPromotion
    *       - Loads the Promotion based on the promotion name
    *
    *   Params ($_GET):
    *       - name:            (String) The Promotion Name
    *       - promotionid:           (INT) Exclude the User ID from the Search
    *
    *   Returns (JSON):
    *       Returns the User data or null
    *
    **/
    public function findPromotion(){        

        $name       = Input::get( 'name' , null );
        $promotionid     = Input::get( 'promotionid' , null );

        if( $name ){

            $query = Promotion::query();

            if( $name ) $query->where( 'slug' , '=' , Str::slug( $name , '.' ) );

            if( $promotionid ) $query->where( 'id' , '!=' , $promotionid );

            return $query->first();

        }

        return Response::make( array( 'result' => 0 , 'error' => 'Promotion Name must be passed as a GET variable' , 'code' => 'invalid-request' ) , 404 );

    }












    /**
    *
    *   addPromotion
    *       - Create a New Promotion
    *
    *   Params ($_PUT):
    *       - name:             (String) The Promotion's Name
    *       - description:      (String) The Promotion's Description
    *       - permissions:      (Array) The User's Permissions
    *
    *   Returns (JSON):
    *       Returns the [ result => ( 1 = Success, 0 = Failure ) ]
    *
    **/
    public function addPromotion(){  

        $data           = Input::all();
        $validator      = Validator::make( $data, [
            'name'                      => 'required|slug:promotions,slug',
            'description'               => 'required'
        ]);

        if( $validator->fails() ){

            //Return Failure
            return [ 'result' => 0 , 'errors' => $validator->errors()->all() ];

        }else{

            try {
    
                //Create the new Promotion
                $promotion = Promotion::create(array_merge($data,[
                    'slug'  => Str::slug( $data['name'] , '.' )
                ]));


                //Create the Notification
                $promotion->notifications()->create([

                    'icon'      => 'star',
                    'type'      => 'New Promotion',
                    'details'   => $promotion->name,
                    'url'       => '/admin/promotions/edit/' . $promotion->id
              
                ])->send([
            
                    'permissions' => [ Permission::where('slug' , 'promotions.edit' )->first()->id ]
            
                ]);




                //Update the Search Criteria
                $promotion->search([

                    'title'     => $data['name'] ,
                    'query'     => $data['description'],
                    'url'       => '/admin/promotions/edit/' . $promotion->id

                ])->assign([

                    'permissions' => [ Permission::where('slug' , 'promotions.edit' )->first()->id ],

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
    *   editPromotion
    *       - Create a New Promotion
    *
    *   Params ($_POST):
    *       - name:             (String) The Promotion's Name
    *       - description:      (String) The Promotion's Description
    *
    *   Returns (JSON):
    *       Returns the [ result => ( 1 = Success, 0 = Failure ) ]
    *
    **/
    public function editPromotion( $promotionid ){  
        
        $data       = array_merge([
            'id'            => (int)$promotionid,
            'name'          => '',
            'description'   => ''
        ],Input::all());

        $validator  = Validator::make( $data, [
            'id'                        => 'required|int|exists:promotions,id',
            'name'                      => 'required|slug:promotions,slug,' . (int)$promotionid,
            'description'               => 'required'
        ]);

        if( $validator->fails() ){

            //Return Failure
            return [ 'result' => 0 , 'errors' => $validator->errors()->all() , 'data' => $data ];

        }else{

            //Get the Promotion
            $promotion = Promotion::find( $promotionid );

            try {

                //Update the Promotions
                $promotion->name         = $data['name'];
                $promotion->slug         = $data['slug'];
                $promotion->description  = $data['description'];
                $promotion->save();

                //Create the Notification
                $promotion->notifications()->create([

                    'icon'      => 'star',
                    'type'      => 'Promotion Updated',
                    'details'   => $promotion->name,
                    'url'       => '/admin/promotions/edit/' . $promotion->id
                
                ])->send([
                
                    'permissions' => [ Permission::where('slug' , 'promotions.edit' )->first()->od ]
                
                ]);


                //Update the Search Criteria
                $promotion->search([

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
    *   deletePromotion
    *       - Delete an Existing User
    *
    *   Params (URL):
    *       - promotionid:                   (String) The Promotion ID
    *
    *   Returns (JSON):
    *       1. (Bool) Returns True / False
    *
    **/
    public function deletePromotion( $promotionid ){  

        if( $promotion = Promotion::find( $promotionid ) ){

            //Delete the Search
            $promotion->search()->delete();

            //Clear the Notifications
            $promotion->notifications()->delete();

            //Send Notification
            $promotion->notifications()->create([

                'icon'      => 'star',
                'type'      => 'Promotion Deleted',
                'details'   => $promotion->name,
                'url'       => null
           
            ])->send([
           
                'permissions'   => [ Permission::where('slug' , 'promotions' )->first()->id ]
           
            ]);

            //Delete the Promotion
            $promotion->delete();

            //Return Success
            return [ 'result' => 1 ];

        }

        //Return Failure
        return [ 'result' => 0 , 'errors' => [ 'That Promotion Doesn\'t Exist' ] ];

    }     






}
