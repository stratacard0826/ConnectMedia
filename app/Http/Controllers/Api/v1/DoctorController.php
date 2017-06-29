<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Notification;
use Bican\Roles\Models\Permission;
use Response;
use Validator;
use Geocoder;
use Input;

class DoctorController extends Controller {






    /**
    *
    *   getDoctor
    *       - Loads All of the Doctors
    *
    *   URL Params:
    *       - doctorid:       (INT) The Doctor ID to Lookup
    *
    *
    *   Returns (JSON):
    *       1. The Doctor Data
    *
    **/
    public function getDoctor( $doctorid = 1 ){

        return Doctor::find( $doctorid ) ;

    }   
    






    /**
    *
    *   getAllDoctors
    *       - Loads All of the Doctors
    *
    *   URL Params:
    *       - limit:     The Page Limit (Default: 15)
    *       - page:      Pages to Load (Default: 1)
    *
    *   Returns (JSON):
    *       1. The list of doctors
    *
    **/
    public function getAllDoctors( $limit = 15 , $page = null ){
        if( $page ){

            return [ 
                'total' => Doctor::count() , 
                'data'  => Doctor::take( $limit )->skip( ( ( $page - 1 ) * $limit ) )->get()
            ];

        }else{

            return Doctor::all();

        }
    }












    /**
    *
    *   findDoctor
    *       - Loads the Doctor based on the email
    *
    *   Params ($_GET):
    *       - email:            (String) The Doctor Email
    *       - doctorid:         (INT) Exclude the Doctor ID from the Search
    *
    *   Returns (JSON):
    *       Returns the User data or null
    *
    **/
    public function findDoctor(){    

        $email      = Input::get( 'email' , null );
        $doctorid   = Input::get( 'doctorid' , null );

        if( $email ){

            $query = Doctor::query();

            if( $email ) $query->where( 'email' , $email );

            if( $doctorid ) $query->where( 'id' , '!=' , $doctorid );

            return $query->first();

        }

        return Response::make( array( 'result' => 0 , 'error' => 'Doctor Email must be passed as a GET variable' , 'code' => 'invalid-request' ) , 404 );

    }













    /**
    *
    *   addDoctor
    *       - Create a New Doctor
    *
    *   Params ($_PUT):
    *       - name:             (String) The Doctor's Name
    *       - description:      (String) The Doctor's Description
    *       - permissions:      (Array) The User's Permissions
    *
    *   Returns (JSON):
    *       Returns the [ result => ( 1 = Success, 0 = Failure ) ]
    *
    **/
    public function addDoctor(){  

        $data           = Input::all();
        $validator      = Validator::make( $data, [
            'firstname'                 => 'required',
            'lastname'                  => 'required',
            'email' 					=> 'required|email|unique:doctors,email',
            'address'                   => 'required',
            'city'                      => 'required',
            'province'                  => 'required',
            'postalcode'                => 'required',
            'phone'                     => 'required'
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

                    //Create the Doctor
                    $doctor = Doctor::create(array_merge($data,[
                        'latitude'   =>  $response->results[0]->geometry->location->lat,
                        'longitude'  =>  $response->results[0]->geometry->location->lng
                    ]));


                    //Create the Notificaiton
                    $doctor->notifications()->create([

                        'icon'      => 'user-md',
                        'type'      => 'New Doctor',
                        'details'   => $doctor->firstname . ' ' . $doctor->lastname,
                        'url'       => '/medical/doctors/edit/' . $doctor->id
                   
                    ])->send([
                  
                        'notification' => [ Permission::where('slug' , 'doctors.edit' )->first()->id ]
                   
                    ]);



                    //Update the Search Criteria
                    $doctor->search([

                        'title'       => $data['firstname'] . ' ' . $data['lastname'] ,
                        'query'       => implode(' ',[
                        	$data['email'],
                            $data['address'],
                            $data['city'],
                            $data['province'],
                            $data['postalcode'],
                            $data['phone']
                        ]),
                        'url'         => '/medical/doctors/edit/' . $doctor->id

                    ])->assign([

                        'permissions' => [ Permission::where('slug' , 'doctors.edit' )->first()->id ]

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
    *   editDoctor
    *       - Create a New Doctor
    *
    *   Params ($_POST):
    *       - name:             (String) The Doctor's Name
    *       - description:      (String) The Doctor's Description
    *       - permissions:      (Array) The User's Permissions
    *
    *   Returns (JSON):
    *       Returns the [ result => ( 1 = Success, 0 = Failure ) ]
    *
    **/
    public function editDoctor( $doctorid ){  

        $data       = Input::all();
        $validator  = Validator::make( $data, [
            'firstname'                 => 'required',
            'lastname'                  => 'required',
            'email' 					=> 'required|email|unique:doctors,email,' . $doctorid,
            'address'                   => 'required',
            'city'                      => 'required',
            'province'                  => 'required',
            'postalcode'                => 'required',
            'phone'                     => 'required'
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
                $doctor = Doctor::find( $doctorid );

                try {

                    //Update the Listing
                    $doctor->firstname    = $data['firstname'];
                    $doctor->lastname     = $data['lastname'];
                    $doctor->email 		  = $data['email'];
                    $doctor->address      = $data['address'];
                    $doctor->province     = $data['province'];
                    $doctor->postalcode   = $data['postalcode'];
                    $doctor->phone        = $data['phone'];
                    $doctor->latitude     = $response->results[0]->geometry->location->lat;
                    $doctor->longitude    = $response->results[0]->geometry->location->lng;
                    $doctor->save();


                    //Return Success
                    $doctor->notifications()->create([

                        'icon'      => 'user-md',
                        'type'      => 'Doctor Updated',
                        'details'   => $doctor->firstname . ' ' . $doctor->lastname,
                        'url'       => '/medical/doctors/edit/' . $doctor->id
                   
                    ])->send([
                  
                        'permissions' => [ Permission::where('slug' , 'doctors.edit' )->first()->id ]
                  
                    ]);



                    //Update the Search Criteria
                    $doctor->search([

                        'title'       => $data['firstname'] . ' ' . $data['lastname'] ,
                        'query'       => implode(' ',[
                        	$data['email'],
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
    *   deleteDoctor
    *       - Delete an Existing User
    *
    *   Params (URL):
    *       - doctorid:                   (String) The Doctor ID
    *
    *   Returns (JSON):
    *       1. (Bool) Returns True / False
    *
    **/
    public function deleteDoctor( $doctorid ){  

        if( $doctor = Doctor::find( $doctorid ) ){

            //Delete the Doctor
            $doctor->search()->delete();

            //Empty the Notifications
            $doctor->notifications()->delete();

            //Create the Notification
            $doctor->notifications()->create([

                'icon'      => 'user-md',
                'type'      => 'Doctor Deleted',
                'details'   => $doctor->firstname . ' ' . $doctor->lastname,
                'url'       => null
            
            ])->send([
           
                'permissions' => [ Permission::where('slug' , 'doctors' )->first()->id ]
           
            ]);

            //Delete the Doctor
            $doctor->delete();

            return [ 'result' => 1 ];

        }

        //Return Failed
        return [ 'result' => 0 , 'errors' => [ 'That Doctor Doesn\'t exist' ] ];

    }     









    //
}
