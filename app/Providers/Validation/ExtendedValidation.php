<?php

namespace App\Providers\Validation;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Validator;
use Illuminate\Support\Str;

class ExtendedValidation extends Validator {


    /**
    *
    *   validateSlug
    *       - Validates the Slug based on the passed column
    *
    *   Params:
    *       - $attribute:       (String) The name of the field passed
    *       - $value:           (String) The Value of the Parameter
    *       - $parameters:      (Array) The Parameters passed in the Validation Request (after :)
    *
    **/
    public function validateSlug($attribute, $value, $parameters){

        list($connection, $table) = $this->parseTable($parameters[0]);

        $column = isset($parameters[1]) ? $parameters[1] : $attribute;

        list($slugColumn, $id) = [null, null];

        if (isset($parameters[2])) {
            list($slugColumn, $id) = $this->getUniqueIds($parameters);

            if (strtolower($id) == 'null') {
                $id = null;
            }
        }

        //Prepare the Slug to Lookup
        $value = Str::slug( $value , '.' );

        $verifier = $this->getPresenceVerifier();

        if( !is_null($connection) ) $verifier->setConnection($connection);

        return $verifier->getCount(

            $table, $column, $value, $id, $slugColumn

        ) == 0;

    }



}