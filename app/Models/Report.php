<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Search as ReportSearch;
use App\Contracts\Search as ReportSearchContract;

class Report extends Model implements ReportSearchContract {

	use ReportSearch;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'reports';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['start', 'end'];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];


    














    /**
    *
    *   stores
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
    public function stores(){

        return $this->belongsToMany('App\Models\Store', 'reports_files')->withPivot('attachment_id', 'order')->withTimestamps();

    }


    














    /**
    *
    *   stores
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

        return $this->belongsToMany('App\Models\Attachment', 'reports_files')->withTimestamps();

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
    *   attachStore
    *       - Attaches the Reports to a list of Stores
    *
    *   Params:
    *       - stores:        (Object) The Stores to Attach to the Promotion
    *       - data:         (Object) The Attachment Data
    *           - name:         (String) The Attachment Name
    *
    *   Returns:
    *       n/a
    *
    **/
    public function attachStore( $file , $data ){

        $this->stores()->attach( $file , $data );

    }



    






    /**
    *
    *   detachAllStores
    *       - Detaches all of the Report Stores
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

}
