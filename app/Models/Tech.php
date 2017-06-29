<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Search as TechSearch;
use App\Contracts\Search as TechSearchContract;

class Tech extends Model implements TechSearchContract {

    use TechSearch;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tech';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'id' , 'user_id' , 'attachment_id' , 'name' , 'notes' ];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];



    






    /**
    *
    *   roles
    *       - Loads the Belongs to Many Relationship Roles
    *
    *   URL Params:
    *       n/a
    *
    *
    *   Returns (Object):
    *       1. The Article Roles
    *
    **/
    public function roles(){

        return $this->belongsToMany( 'Bican\Roles\Models\Role' , 'tech_role' )->withTimestamps();
   
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
    *       1. The Article Roles
    *
    **/
    public function stores(){

        return $this->belongsToMany( 'App\Models\Store' , 'tech_store' )->withTimestamps();

    }


    














    /**
    *
    *   notifications
    *       - Loads the Belongs to Many Relationship Attachments
    *
    *   URL Params:
    *       n/a
    *
    *
    *   Returns (Object):
    *       1. The Notification to Create
    *
    **/
    public function notifications(){

        return $this->morphMany('App\Models\Notification', 'link');

    }



    



    






    /**
    *
    *   attachment
    *       - Loads the Belongs to One Attachment Relationship
    *
    *   URL Params:
    *       n/a
    *
    *
    *   Returns (Object):
    *       1. The Attachment
    *
    **/
    public function attachment(){

        return $this->belongsTo('App\Models\Attachment' , 'attachment_id' , 'id' );

    }
  









    /**
    *
    *   specifications
    *       - Loads the Belongs to Many Relationship Specifications
    *
    *   URL Params:
    *       n/a
    *
    *
    *   Returns (Object):
    *       1. The Tech Specifications
    *
    **/
    public function specifications(){

        return $this->belongsToMany('App\Models\Tech' , 'tech_specs' )->withPivot([ 'key' , 'value' ])->withTimestamps();
   
    }






}
