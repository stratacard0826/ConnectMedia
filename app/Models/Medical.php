<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Search as MedicalSearch;
use App\Contracts\Search as MedicalSearchContract;

class Medical extends Model implements MedicalSearchContract {

	use MedicalSearch;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'medical';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['creator_id', 'store_id' , 'doctor_id' , 'customer' , 'notes'];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];














    /**
    *
    *   products
    *       - Loads the Product Relationship
    *
    *   URL Params:
    *       n/a
    *
    *
    *   Returns (Object):
    *       1. The Products to Load
    *
    **/
    public function products(){

        return $this->belongsToMany('App\Models\Medical', 'medical_products' , 'medical_id')->withPivot( 'product' )->withTimestamps();

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
    *   doctor
    *       - Loads the Doctor Relationship
    *
    *   URL Params:
    *       n/a
    *
    *
    *   Returns (Object):
    *       1. The Doctor to Load
    *
    **/
    public function doctor(){

        return $this->hasOne('App\Models\Doctor', 'id' , 'doctor_id');

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
     *   creator
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
    public function creator(){

        return $this->hasOne('App\Models\User', 'id' , 'creator_id');

    }

}
