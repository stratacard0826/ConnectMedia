<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Search as RoleSearch;
use App\Contracts\Search as RoleSearchContract;
use Bican\Roles\Models\Role as BicanRole;

class Role extends BicanRole implements RoleSearchContract {

	use RoleSearch;

    /**
    *
    *	ROLE
    *		- Extends the Bican Roles & Adds the "SearchContract" to the Model
    *
    *	REFERENCE:
    * 		Contracts\Search
    * 		Traits\Search
    *		Bican\Roles\Models\Role
    *
    **/


    














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
