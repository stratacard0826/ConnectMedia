<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use App\Models\User;
use App\Models\Notification;
use Auth;
use Input;
use Validator;
use Hash;

class ProfileController extends Controller {














    /**
    *
    *   editProfile
    *       - Update an Existing User
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
    *
    *   Returns (JSON):
    *       Returns the User data or null
    *
    **/
    public function editProfile(){  

        $data       = array_merge([
            'id'        => Auth::user()->id,
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
            'username'                  => 'min:3|max:50',
            'email'                     => 'required|unique:users,email,' . Auth::user()->id,
            'password'                  => 'confirmed|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$/'
        ]);

        if( $validator->fails() ){

            //Validation Failed
            return [ 'result' => 0 , 'errors' => $validator->errors()->all() ];

        }else{

            //Get the User
            $user = Auth::user();

            try {

                //Setup the User
                if( !empty( $data['password'] ) ){

                    $user->password = Hash::make( $data['password'] );

                }

                $user->firstname    = $data['firstname'];
                $user->lastname     = $data['lastname'];
                $user->username     = ( !empty( $data['username'] ) ? $data['username'] : null );
                $user->email        = $data['email'];
                $user->city         = $data['city'];
                $user->province     = $data['province'];
                $user->phone        = $data['phone'];
                $user->save();


                //Send the Notification
                $user->notifications()->create([

                    'icon'      => 'user',
                    'type'      => 'Profile Updated' ,
                    'details'   =>  $data['firstname'] . ' ' . $data['lastname'] ,
                    'url'       => '/admin/users/edit/' . $user->id
               
                ])->send([            
               
                    'permissions'   => [ Permission::where('slug' , 'users.edit' )->first()->id ]
               
                ]);




                //Update the Search Criteria
                $user->search([

                    'title'       => $user->firstname . ' ' . $user->lastname ,
                    'query'       => implode(' ',[
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
}
