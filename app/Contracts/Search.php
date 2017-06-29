<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;

interface Search {

    

    /**
    *
    *   search
    *       - Extends the Search Functionality
    *
    *   Params:
    *       $save           (Object) The Search Fields to Save
    *
    *   Returns:
    *       The Polymorphic Search Table Reference
    *
    **/
    public function search($save = null);

}
