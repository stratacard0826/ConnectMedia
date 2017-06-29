<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Bican\Roles\Models\Permission;
use App\Models\Store;
use App\Models\Role;

class Search extends Model {


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'search';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'title' , 'query' , 'url' ];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['link_id'];
    
    











    /**
    *
    *   assign
    *       - Assigns a Role
    *
    *   Params:
    *       - roles 			(INT | Array) The Roles to Assign
    * 		- permissions 		(INT | Array) The Permissions to Assign
    * 		- stores 			(INT | Array) The Stores to Assign
    *
    *
    *   Returns (Object):
    *       1. The Search Roles
    *
    **/
    public function assign($data){
    	if( $this->id ){


    		//Setup the Role Permissions
        	$this->roles()->detach();

	        if( !empty( $data['roles'] ) ){

                foreach( Role::find( $data['roles'] ) as $role ){
      
                    $this->roles()->attach( $role );

                }

	        }


			//Setup the Search Permissions	        	
	        $this->permissions()->detach();

	        if( !empty( $data['permissions'] ) ){

	        	foreach( Permission::find( $data['permissions'] ) as $permission ){

	        		$this->permissions()->attach( $permission );

	        	}

	        }


	        //Setup the Search Stores
	        $this->stores()->detach();

	        if( !empty( $data['stores'] ) ) {

	        	foreach( Store::find( $data['stores'] ) as $store ){

	        		$this->stores()->attach( $store );

	        	}

	        }

   
   		}else{

   			//Invalid Use of Assign
   			throw new Exception('You can only assign roles after obtaining the search item (i.e. Search::Find(1)->assign([])');

   		}
    }
    
    


    










    /**
    *
    *   roles
    *       - Loads the Belongs to Many Relationship for Roles
    *
    *   URL Params:
    *       n/a
    *
    *
    *   Returns (Object):
    *       1. The Search Roles
    *
    **/
    public function roles(){

        return $this->belongsToMany('Bican\Roles\Models\Role', 'search_role')->withTimestamps();
   
    }


    










    /**
    *
    *   stores
    *       - Loads the Belongs to Many Relationship Stores
    *
    *   URL Params:
    *       n/a
    *
    *
    *   Returns (Object):
    *       1. The Search Stores
    *
    **/
    public function stores(){

        return $this->belongsToMany('App\Models\Store', 'search_store')->withTimestamps();
   
    }


    










    /**
    *
    *   permission
    *       - Loads the Belongs to Many Relationship Permissions
    *
    *   URL Params:
    *       n/a
    *
    *
    *   Returns (Object):
    *       1. The Store Users
    *
    **/
    public function permissions(){

        return $this->belongsToMany('Bican\Roles\Models\Permission', 'search_permission')->withTimestamps();
   
    }






}
