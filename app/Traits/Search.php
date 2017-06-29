<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait Search {
   





    /**
    *
    *   search
    *       - Extends the Search Functionality
    *
    *   Reference:
    *       App\Contracts
    *
    *   Params:
    *       $save           (Object) The Search Fields to Save
    *
    *   Returns:
    *       The Polymorphic Search Table Reference
    *
    **/
    public function search($save = null){

        if( $save ){

            $search = $this->morphOne('App\Models\Search', 'link')->getResults();

            //Prepare the Query Field (Maximum 1000 characters)
            if( !empty( $save['query'] ) ){

                $save['query'] = substr( $save['query'] , 0 , 1000 );

            }

            //Update the Search
            if( !empty( $search ) ){

                foreach( $save as $key => $val ){
                    $search->{ $key } = $val;
                }

                $search->save();

                return $search;

            }

            //Create a New Search
            return $this->morphOne('App\Models\Search', 'link')->create($save);

        }

        //Return the Morph Relation
        return $this->morphOne('App\Models\Search', 'link');

    }







}
