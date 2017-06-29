<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'reminders';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['item_id', 'timecount', 'type', 'role_id', "period", "module_name"];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];


    /**
    *   event
    *       - Loads the Belongs to Relationship Event
    *
    *   URL Params:
    *       n/a
    *
    *
    *   Returns (Object):
    *       The Event
    **/
    public function event(){

        return $this->belongsTo('App\Models\Event', 'item_id', 'id');

    }

}
