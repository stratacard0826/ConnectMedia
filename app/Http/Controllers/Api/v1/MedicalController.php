<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Bican\Roles\Models\Permission;
use Bican\Roles\Models\Role;
use App\Models\Notification;
use App\Models\Medical;
use App\Models\User;
use App\Services\CommonService;
use Input;
use Validator;
use Mail;

class MedicalController extends Controller {




    /**
    *
    *   getReferral
    *       - Loads All of the Referrals
    *
    *   URL Params:
    *       - promotionid:       (INT) The Referral ID to Lookup
    *
    *
    *   Returns (JSON):
    *       1. The Referral Data
    *
    **/
    public function getReferral( $referralid = 1 ){

    	$referral = Medical::with([ 'doctor' , 'store' , 'products' ])->find( $referralid );

		foreach( $referral->products as $index => $product ){

			$referral->products[ $index ] = [ 'product' => $product->pivot->product ];

    	}

        return [
            'result' => 1,
            'data'   => $referral
        ];

    }   
    










    /**
    *
    *   getAllReferrals
    *       - Loads All of the Referrals
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
    public function getAllReferrals( $limit = 15 , $page = null ){
        if( $page ){

        	$referral = Medical::with([ 'doctor' , 'store' , 'products', 'creator' ])->take( $limit )->skip( ( ( $page - 1 ) * $limit ) )->get();

	    	foreach( $referral as $index => $val ){

                $referral[ $index ]->date = ( strtotime( $val->created_at ) * 1000 );
                
	    		foreach( $referral[ $index ]->products as $index2 => $product ){

	    			$referral[ $index ]->products[ $index2 ] = [ 'product' => $product->pivot->product ];
	    
		    	}
	    	}

            return [ 
                'result'    => 1,
                'total'     => Medical::count() , 
                'data'      => $referral
            ];

        }else{

            return [
                'result' => 1,
                'data'   => Medical::all(['id','customer'])
            ];

        }
    }    












    /**
    *
    *   addReferral
    *       - Create a New Referral
    *
    *   Params ($_PUT):
    *       - name:             (String) The Referral's Name
    *       - description:      (String) The Referral's Description
    *       - permissions:      (Array) The User's Permissions
    *
    *   Returns (JSON):
    *       Returns the [ result => ( 1 = Success, 0 = Failure ) ]
    *
    **/
    public function addReferral(){  

        $data           = Input::all();
        $validator      = Validator::make( $data, [
            'doctor_id'  			=> 'required|exists:doctors,id',
            'store_id' 	 			=> 'required|exists:stores,id',
            'products.*.product' 	=> 'required',
            'customer' 				=> 'required'
        ]);

        if( $validator->fails() ){

            //Return Failure
            return [ 'result' => 0 , 'errors' => $validator->errors()->all() ];

        }else{

            try {


                //Create the new Referral
                $referral = Medical::create([
                    'creator_id' 	=> \Auth::user()->id,
                	'doctor_id' 	=> $data['doctor_id'],
                	'store_id' 		=> $data['store_id'],
                	'customer' 		=> $data['customer'],
                	'notes' 		=> $data['notes']
                ]);

                //Build the Products Array
                $products = [];


                //Save the Products Added
                foreach( $data['products'] as $product ){

                	$referral->products()->attach( $referral->id , [
                		'product' 	=> $product['product']
                	] );

                	$products[] = $product['product'];

                }


                //Get the Doctor Data
                $doctor = $referral->doctor()->first();

                //Get the Store Data
                $store = $referral->store()->first();


                //Send the Email
                $this->send( $referral , 'Created' );

                //Create the Notification
                $referral->notifications()->create([

                    'icon'      => 'heartbeat',
                    'type'      => 'New Medical Referral',
                    'details'   => $doctor->firstname . ' ' . $doctor->lastname . ' - ' . $store->name,
                    'url'       => '/medical/edit/' . $referral->id
              
                ])->send([
            
                    'permissions' => [ Permission::where('slug' , 'medical.edit' )->first()->id ]
            
                ]);




                //Update the Search Criteria
                $referral->search([

                    'title'     => date('Y-m-d', strtotime( $referral->created_at )) . ' - ' . $doctor->firstname . ' ' . $doctor->lastname . ' - ' . $store->name ,
                    'query'     => $data['customer'] . ' ' . implode( ' ' , $products ),
                    'url'       => '/medical/edit/' . $referral->id

                ])->assign([

                    'permissions' => [ Permission::where('slug' , 'medical.edit' )->first()->id ],

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
    *   editReferral
    *       - Create a New Referral
    *
    *   Params ($_POST):
    *       - name:             (String) The Referral's Name
    *       - description:      (String) The Referral's Description
    *
    *   Returns (JSON):
    *       Returns the [ result => ( 1 = Success, 0 = Failure ) ]
    *
    **/
    public function editReferral( $referralid ){  
        
        $data       = Input::all();
        $validator  = Validator::make( $data, [
            'doctor_id'  	=> 'required|exists:doctors,id',
            'store_id'	 	=> 'required|exists:stores,id',
            'customer' 		=> 'required'
        ]);

        if( $validator->fails() ){

            //Return Failure
            return [ 'result' => 0 , 'errors' => $validator->errors()->all() , 'data' => $data ];

        }else{

            //Get the Referral
            $referral = Medical::with([ 'doctor' , 'store' ])->find( $referralid );

            try {

                //Update the Referrals
                $referral->doctor_id    = $data['doctor_id'];
                $referral->store_id     = $data['store_id'];
                $referral->customer 	= $data['customer'];
                $referral->notes      	= $data['notes'];
                $referral->save();

                //Build the Products Array
                $products = [];

                $referral->products()->detach();

                //Save the Products Added
                foreach( $data['products'] as $product ){

                	$referral->products()->attach( $referral->id , [
                		'product' 	=> $product['product']
                	] );

                	$products[] = $product['product'];

                }


                //Get the Doctor Data
                $doctor = $referral->doctor()->first();

                //Get the Store Data
                $store = $referral->store()->first();

                //Send the Email
                $this->send( $referral , 'Updated' );

                //Create the Notification
                $referral->notifications()->create([

                    'icon'      => 'heartbeat',
                    'type'      => 'Medical Referral Updated',
                    'details'   => $doctor->firstname . ' ' . $doctor->lastname . ' - ' . $store->name,
                    'url'       => '/medical/edit/' . $referral->id
                
                ])->send([
                
                    'permissions' => [ Permission::where('slug' , 'medical.edit' )->first()->od ]
                
                ]);


                //Update the Search Criteria
                $referral->search([

                    'title'     => date('Y-m-d', strtotime( $referral->created_at )) . ' - ' . $doctor->firstname . ' ' . $doctor->lastname . ' - ' . $store->name ,
                    'query'     => $data['customer']. ' ' . implode( ' ' , $products )
                
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
    *   deleteReferral
    *       - Delete an Existing User
    *
    *   Params (URL):
    *       - promotionid:                   (String) The Referral ID
    *
    *   Returns (JSON):
    *       1. (Bool) Returns True / False
    *
    **/
    public function deleteReferral( $referralid ){  

        if( $referral = Medical::with([ 'doctor' , 'store' ])->find( $referralid ) ){

            //Delete the Search
            $referral->search()->delete();

            //Clear the Notifications
            $referral->notifications()->delete();

            //Send Notification
            $referral->notifications()->create([

                'icon'      => 'heartbeat',
                'type'      => 'Medical Referral Deleted',
                'details'   => $referral->doctor->firstname . ' ' . $referral->doctor->lastname . ' - ' . $referral->store->name,
                'url'       => null
           
            ])->send([
           
                'permissions'   => [ Permission::where('slug' , 'medical' )->first()->id ]
           
            ]);

            //Delete the Referral
            $referral->delete();

            //Return Success
            return [ 'result' => 1 ];

        }

        //Return Failure
        return [ 'result' => 0 , 'errors' => [ 'That Medical Referral Doesn\'t Exist' ] ];

    }      













    /**
    *
    *   send
    *       - Sends the Medical Referral Email
    *
    *   Params:
    *      $referral:       (Object) The Created / Updated Medical Referral
    *      $type:       (String) The Type of Logout Sent
    *           
    *
    *   Returns (JSON):
    *       Returns the [ result => ( 1 = Success, 0 = Failure ) ]
    *
    **/
    private function send( $referral , $type ){


        $Recipients = User::where(function($query) use ($referral){

            //Get the recipient roles
            $query->where(function($query){

                //Get all the Roles with Recipient ALL status
                $roles = Role::whereHas( 'permissions' , function($query){

                    $query->where( 'slug' , 'medical.recipients.all' );

                })->get()->lists('id');

                if(!empty( $roles )){

                    //Has the Roles
                    $query->whereHas( 'roles' , function($query) use ($roles){

                        $query->whereIn( 'role_id' , $roles );

                    });

                }

            });



            //CGet the "All" Role User Permissions
            $query->orWhereHas( 'userPermissions' , function($query){

                //Has Permission with All Recipients
                $query->where( 'slug' , 'medical.recipients.all' );

            });


        })->get();

        //Get the Doctor Data
        $doctor = $referral->doctor()->first();

        //Get the Store Data
        $store = $referral->store()->first();

        //Get the Products
        $products = $referral->products()->get();

        /**
        * Get all users including administrators
        */
        $Recipients = CommonService::getUsersForSendingMails($Recipients);

        //Send the Email Reference
        Mail::send([ 'html' => 'emails.medical' ] , [ 'data' => $referral , 'store' => $store , 'doctor' => $doctor , 'products' => $products , 'type' => $type ] , function($message) use ( $Recipients , $referral , $type ){

            //Setup Recipients
            foreach( $Recipients as $user ){
                if( $user->email ){

                    $user->fullname = ( !empty( $user->firstname ) && !empty( $user->lastname ) ? $user->firstname . ' ' . $user->lastname : null );

                    $message->to( $user->email , $user->fullname );

                }
            }

            //Set Subject
            $message->subject( env('COMPANY') . ' ' . $type . ' Medical Referral (' . $referral->store->name . ') ' . date( 'l, M d, Y' , strtotime( $referral->created_at ) ) );

        });

        //Send the Doctor's Email
        Mail::send([ 'html' => 'emails.medical' ] , [ 'data' => $referral , 'store' => $store , 'doctor' => $doctor , 'products' => $products , 'type' => $type ] , function($message) use ( $Recipients , $referral , $doctor , $type ){

            //Setup Recipients
            $message->to( $doctor->email , $doctor->firstname . ' ' . $doctor->lastname );

            //Set Subject
            $message->subject( env('COMPANY') . ' ' . $type . ' Medical Referral (' . $referral->store->name . ') ' . date( 'l, M d, Y' , strtotime( $referral->created_at ) ) );

        });


    } 


}
