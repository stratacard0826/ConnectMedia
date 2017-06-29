<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Search as FolderSearch;
use App\Contracts\Search as FolderSearchContract;

class Folder extends Model {

	use FolderSearch;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'document_folders';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['parent_id', 'name', 'slug', 'description'];


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
