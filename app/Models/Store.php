<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Search as StoreSearch;
use App\Contracts\Search as SearchContract;

class Store extends Model implements SearchContract {

    use StoreSearch;


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'stores';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','slug','address','city','province','postalcode','phone','latitude','longitude'];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];



    






    /**
    *
    *   users
    *       - Loads the Belongs to Many Relationship Users
    *
    *   URL Params:
    *       n/a
    *
    *
    *   Returns (Object):
    *       1. The Store Users
    *
    **/
    public function users(){

        return $this->belongsToMany('App\Models\User')->withTimestamps();
   
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
