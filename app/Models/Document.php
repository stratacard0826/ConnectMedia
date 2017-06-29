<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Search as PromoSearch;
use App\Contracts\Search as PromoSearchContract;

class Document extends Model implements PromoSearchContract {

    use PromoSearch;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'documents';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['attachment_id' , 'slug', 'name'];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];


    














    /**
    *
    *   files
    *       - Loads the Belongs to Many Relationship Attachments
    *
    *   URL Params:
    *       n/a
    *
    *
    *   Returns (Object):
    *       1. The Promotion Attachment
    *
    **/
    public function files(){

        return $this->belongsTo('App\Models\Attachment','attachment_id');

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
