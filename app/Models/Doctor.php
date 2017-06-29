<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Search as DoctorSearch;
use App\Contracts\Search as DoctorSearchContract;

class Doctor extends Model
{

    use DoctorSearch;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'doctors';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['firstname','lastname','email','address','city','province','postalcode','phone','latitude','longitude'];


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





}
