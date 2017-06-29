<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Attachment;
use Response;
use Input;

class AttachmentController extends Controller
{




	/**
	*
	*	serveFile
	* 		- Serve an Attached File
	*
	*	Request Params:
	* 		- slug: 		(String) The Slug of the File to Load
	*
	* 	Returns (JSON):
	* 		1. The Attachment
	*
	**/
    public function serveFile( $slug ){

        $attachment = Attachment::where( 'slug' , $slug )->firstOrFail();

        return response()->download( Attachment::path( $attachment->filename , $attachment->disk ) );

    }
    













    /**
    *
    *   attach
    *       - Attaches a File
    *
    *   Request Params:
    *       file:       (FILE) The File to Attach     
    *
    *
    *   Returns (JSON):
    *       1. The News
    *
    **/
    public function attach(){
        if( Input::file('file') ){

            return [ 'result' => 1 , 'attachment_id' => Attachment::store( Input::file('file') )->id ];

        }

        return [ 'result' => 0 , 'error' => 'Invalid File Uploaded' , 'code' => 'invalid-file' ];
    
    }

}
