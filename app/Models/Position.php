<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Search as PositionSearch;
use App\Contracts\Search as PositionSearchContract;

class Position extends Model {

	use PositionSearch;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'positions';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'description'];


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