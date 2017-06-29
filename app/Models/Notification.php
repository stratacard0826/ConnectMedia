<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Notification extends Model {


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notifications';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['link_id', 'link_type', 'type', 'details', 'icon', 'url'];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];








    /**
    *
    *   users
    *       - Binds the Notification Model to the Users through a belongsToMany Relationship
    *
    *   Params:
    *       n/a
    *
    *   Returns:
    *       n/a
    *
    **/
    public function users(){
        return $this->belongsToMany('App\Models\User')->withTimestamps();
    }







    /**
    *
    *   send
    *       - Gets the belongsToMany Store Relation
    *
    *   Params:
    *       - stores:       (Array) The Store IDs / Objects to Include
    *       - roles:        (Array) The Role IDs / Objects to Include
    *       - permissions:  (Array) The Permission IDs / Objects to Include
    *       - exclude:      (Array) The User ID's to Exclude
    *
    *   Returns:
    *       n/a
    *
    **/
    public function send($data){

        if(!empty( $data )){

            $data = array_merge([
                'stores'        => [],
                'roles'         => [],
                'permissions'   => [],
                'exclude'       => []
            ],$data);

            //Query the Recipients
            $Recipients = User::where(function($query) use ($data) {

                //Add Stores if we have any
                if( !empty( $data['stores'] ) ){

                    $query->whereHas( 'stores' , function($query) use ($data) {

                        //Prepare the Query Field Data
                        if( is_object( $data['stores'] ) ){

                            $data['stores'] = array_column( $data['stores']->toArray() , 'id' );

                        }

                        //Add the Query Field
                        $query->whereIn( 'store_id' , $data['stores'] );
                    
                    });

                }

                //If we have Roles or Permissions
                if( !empty( $data['roles'] ) || !empty( $data['permissions'] ) ){

                    $query->whereHas( 'roles' , function($query) use ($data) {

                        //Add the Roles
                        if( !empty( $data['roles'] ) ){

                            //Prepare the Query Field Data
                            if( is_object( $data['roles'] ) ){

                                $data['roles'] = array_column( $data['roles']->toArray() , 'id' );

                            }

                            //Add the Query Field
                            $query->whereIn( 'role_id' , $data['roles'] );

                        }

                        //Add the Permissions
                        if( !empty( $data['permissions'] ) ){

                            $query->whereHas( 'permissions' , function($query) use ($data) {

                                //Prepare the Query Field Data
                                if( is_object( $data['permissions'] ) ){

                                    $data['permissions'] = array_column( $data['permissions']->toArray() , 'id' );

                                }

                                //Add the Query Field
                                $query->whereIn( 'permission_id' , $data['permissions'] );

                            });

                        }

                    });

                }


            })->where(function($query) use ($data){

                //Exclude the Current User + Any Additional Users
                $query->whereNotIn('id', array_unique( array_merge([ Auth::user()->id ], $data['exclude']) ) );

            })->get()->toArray();

            //Get the User IDs 
            $Recipients = array_column( $Recipients , 'id' ) ;
        
            //Add any Additional User IDs
            if(!empty( $data['users'] )){

                $Recipients = array_merge( array_unique( $Recipients , $data['users'] ) );

            }

        }else{

            //Send to All Users
            $Recipients = User::all()->except( Auth::user()->id )->lists('id');

        }

        //SEnd the Notification
        $this->users()->attach( $Recipients );

    }

}
