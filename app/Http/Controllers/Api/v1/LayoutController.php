<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use View;
use Breadcrumbs;
use Response;

class LayoutController extends Controller {


    /**
    *
    *   getLayout
    *       - Returns the Layout
    *
    *   Params:
    *       - category:       (String) The Layout Name to Load
    *       - file:           (String) The Layout Filename to Load
    *
    *   Returns (HTML):
    *       1. Returns the Layout
    *
    **/
    public function getLayout( $category , $file ){
        if( View::exists( $category . '/' . $file ) ){

            return View::make( $category . '/' . $file );

        }

        return Response::make( array( 'result' => 0 , 'error' => 'Invalid Layout File' , 'code' => 'invalid-layout-file' ) , 404 );
    
    }

    
}
