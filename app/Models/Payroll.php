<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Search as PayrollSearch;
use App\Contracts\Search as PayrollSearchContract;
use App\Traits\Feedback as PayrollFeedback;
use App\Contracts\Feedback as PayrollFeedbackContract;

class Payroll extends Model {

	use PayrollSearch;
    use PayrollFeedback;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'payrolls';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['store_id', 'start', 'end'];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];










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
    *   submission
    *       - Loads the Submission Relationship
    *
    *   URL Params:
    *       n/a
    *
    *
    *   Returns (Object):
    *       1. The Store to Load
    *
    **/
    public function submission(){

        return $this->belongsToMany('App\Models\Payroll', 'payroll_submission' , 'payroll_id')->withPivot( 'user_id' , 'position_id' , 'date' , 'hours' , 'rate' )->withTimestamps();

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
