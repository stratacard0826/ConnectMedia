<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Search as NewsSearch;
use App\Contracts\Search as NewsSearchContract;

class News extends Model implements NewsSearchContract {

    use NewsSearch;
    


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'news';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id' , 'event_id' , 'subject' , 'article'];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];



    






    /**
    *
    *   attachRole
    *       - Attaches the News Article to a list of roles
    *
    *   Params:
    *       - role:        (Object) The Roles to Attach to the Article
    *
    *
    *   Returns:
    *       n/a
    *
    **/
    public function attachRole( $role ){

        $this->roles()->attach( $role );

    }



    






    /**
    *
    *   attachStore
    *       - Attaches the News Article to a list of stores
    *
    *   Params:
    *       - store:        (Object) The Stores to Attach to the Article
    *
    *
    *   Returns:
    *       n/a
    *
    **/
    public function attachStore( $store ){

        $this->stores()->attach( $store );

    }



    






    /**
    *
    *   attachFile
    *       - Attaches the News Article to a list of files
    *
    *   Params:
    *       - files:        (Object) The Files to Attach to the Article
    *
    *
    *   Returns:
    *       n/a
    *
    **/
    public function attachFile( $file ){

        $this->attachments()->attach( $file );

    }



    






    /**
    *
    *   attachEdit
    *       - Attaches the News Article Update to a User
    *
    *   Params:
    *       - users:        (Object) The User who Updated the Article
    *
    *
    *   Returns:
    *       n/a
    *
    **/
    public function attachEdit( $user ){

        $this->updates()->attach( $user );

    }



    






    /**
    *
    *   detachAllFiles
    *       - Detaches all of the articles Files
    *
    *   Params:
    *       n/a
    *
    *
    *   Returns:
    *       n/a
    *
    **/
    public function detachAllFiles(){

        $this->attachments()->detach();

    }



    






    /**
    *
    *   detachAllRoles
    *       - Detaches all of the articles Roles
    *
    *   Params:
    *       n/a
    *
    *
    *   Returns:
    *       n/a
    *
    **/
    public function detachAllRoles(){

        $this->roles()->detach();

    }



    






    /**
    *
    *   detachAllStores
    *       - Detaches all of the articles Stores
    *
    *   Params:
    *       n/a
    *
    *
    *   Returns:
    *       n/a
    *
    **/
    public function detachAllStores(){

        $this->stores()->detach();

    }



    






    /**
    *
    *   event
    *       - Loads the Belongs To Event Relationship
    *
    *   URL Params:
    *       n/a
    *
    *
    *   Returns (Object):
    *       1. The Article Roles
    *
    **/
    public function event(){

        return $this->belongsTo( 'App\Models\Event' , 'event_id' );
   
    }



    






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

        return $this->belongsToMany('Bican\Roles\Models\Role')->withTimestamps();
   
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

        return $this->belongsToMany('App\Models\Store')->withTimestamps();

    }


    






    /**
    *
    *   attachments
    *       - Loads the Belongs to Many Relationship Attachments
    *
    *   URL Params:
    *       n/a
    *
    *
    *   Returns (Object):
    *       1. The Article Attachments
    *
    **/
    public function attachments(){

        return $this->belongsToMany('App\Models\Attachment','news_attachment')->withTimestamps();

    }


    






    /**
    *
    *   users
    *       - Loads the Article Author
    *
    *   URL Params:
    *       n/a
    *
    *
    *   Returns (Object):
    *       1. The Article Attachments
    *
    **/
    public function author(){

        return $this->belongsTo('App\Models\user','user_id');

    }


    






    /**
    *
    *   users
    *       - Loads the Article Author
    *
    *   URL Params:
    *       n/a
    *
    *
    *   Returns (Object):
    *       1. The Article Attachments
    *
    **/
    public function updates(){

        return $this->belongsToMany('App\Models\user','news_updates')->withTimestamp();

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

}
