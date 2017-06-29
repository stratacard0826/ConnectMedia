<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Search as BuyersLogoutSearch;
use App\Contracts\Search as BuyersLogoutSearchContract;

class BuyersLogout extends Model implements BuyersLogoutSearchContract {

	use BuyersLogoutSearch;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'buyerslogouts';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'store_id' , 'start' , 'end' , 'location' , 'recap' , 'lymtd' , 'mtd' , 'sales' ];


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





}
