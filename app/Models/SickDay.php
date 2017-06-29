<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Search as SickSearch;
use App\Contracts\Search as SickSearchContract;

class SickDay extends Model {

	use SickSearch;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sickdays';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'store_id' , 'user_id' , 'date' , 'details' ];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];


    














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
    *   store
    *       - Loads the Store Relationship
    *
    *   URL Params:
    *       n/a
    *
    *
    *   Returns (Object):
    *       1. The Store to Load
    *
    **/
    public function store(){

        return $this->hasOne('App\Models\Store', 'id' , 'store_id');

    }


    














    /**
    *
    *   user
    *       - Loads the User Relationship
    *
    *   URL Params:
    *       n/a
    *
    *
    *   Returns (Object):
    *       1. The User to Load
    *
    **/
    public function user(){

        return $this->hasOne('App\Models\User', 'id' , 'user_id');

    }





}
