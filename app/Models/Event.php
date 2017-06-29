<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Search as EventSearch;
use App\Contracts\Search as EventSearchContract;

class Event extends Model implements EventSearchContract {

    use EventSearch;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'event';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id' , 'name' , 'details' , 'start' , 'end'];

    

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
    *
    *   URL Params:
    *       n/a
    *
    *
    *   Returns (Object):
    *       1. The Reminders
    *
    **/
    public function reminders(){

        return $this->hasMany('App\Models\Reminder', 'item_id', 'id')->where('module_name', 'event');
   
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

        return $this->belongsToMany('App\Models\Attachment','event_attachment')->withTimestamps();

    }

}
