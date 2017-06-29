<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Bican\Roles\Models\Permission;
use App\Models\Report;
use App\Models\Store;
use App\Models\Attachment;
use App\Models\Notification;
use App\Models\User;
use App\Services\CommonService;
use Input;
use Validator;
use Auth;
use Mail;

class ReportController extends Controller {
  




    //Redirect to Root
    protected $redirectPath = '/';





    /**
    *
    *   getAllReports
    *       - Loads All of the Reports
    *
    *   URL Params:
    *       - limit:     (INT) The Page Limit (Default: 15)
    *       - page:      (INT) Pages to Load (Default: 1)
    *
    *
    *   Returns (JSON):
    *       1. The Report List
    *
    **/
    public function getAllReports( $limit = 15 , $page = 1 ){

        $data = Report::take( $limit )->skip( ( ( $page - 1 ) * $limit ) )->get();

        foreach( $data as $key => $val ){
            $data[ $key ]['starttime']  = strtotime( $val['start'] . ' 23:59:59' );
            $data[ $key ]['endtime']    = strtotime( $val['end'] . ' 23:59:59' );
        }

        //Return the Data
        return [ 
            'total' => Report::count() , 
            'data'  => $data
        ];

    }   
    













    /**
    *
    *   getReport
    *       - Loads a Single Report
    *
    *   URL Params:
    *       - id:        (INT) The Report ID to Load
    *
    *
    *   Returns (JSON):
    *       1. The Report
    *
    **/
    public function getReport( $id ){

        if( Report::find( $id )->count() ){

            $user_stores        = \Auth::user()->stores()->get()->lists('id')->toArray();
            $reports            = (Object)Report::with(['stores'])->find( $id )->toArray();
            $reports->starttime = strtotime( $reports->start . ' 23:59:59' );
            $reports->endtime   = strtotime( $reports->end . ' 23:59:59' );
            $reports->start     = date('Y-m-d', $reports->starttime );
            $reports->end       = date('Y-m-d', $reports->endtime );
            $reports->name      = $reports->start . ' to ' . $reports->end ;
            $reports->files     = [];
            $ids                = [];
            $order              = -1;

            //Save the Stores and Delete
            $stores = $reports->stores;
            unset( $reports->stores );

            //Get the Files
            foreach( $stores as $key => $store ){

                $file   = null;
                $order  = $store['pivot']['order'];
                $ids[]  = $store['id'];

                //Only return Existing Stores
                if( in_array( $store['id'] , $user_stores ) ){

                    $file                       = Attachment::grab([ $store['pivot']['attachment_id'] ], 'public' );
                    $file[0]->status            = 'success';
                    $file[0]->attachment_id     = $store['pivot']['attachment_id'];
                    $file                       = $file[0]->toArray();

                }

                unset( $store['pivot'] );

                $reports->files[] = [
                    'file'      => $file,
                    'store'     => $store,
                    'order'     => $order,
                    'exists'    => true
                ];

            }

            //Get any Missing Stores
            $stores = Store::whereNotIn('id' , $ids)->get()->toArray();

            //Add the Store
            foreach( $stores as $key => $store ){

                $reports->files[] = [
                    'file'      => null,
                    'store'     => $store,
                    'order'     => ++$order,
                    'exists'    => false
                ];

            }

            //Load the Article Data
            return [
                'result' => 1,
                'data'   => $reports
            ];

        }

        //Return Fail
        return [
            'result'    => 0
        ];

    }  
    













    /**
    *
    *   getLastReport
    *       - Loads the Last Report
    *
    *   URL Params:
    *       n/a
    *
    *
    *   Returns (JSON):
    *       1. The Report
    *
    **/
    public function getLastReport(){

        $report = Report::orderBy('id', 'DESC');

        return ( $report->count() > 0 ? $this->getReport( $report->first()->id ) : [ 'result' => 0 ] );

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

            $Attachment = Attachment::store( Input::file('file') , 'public' );

            return [ 'result' => 1 , 'attachment_id' => $Attachment->id ];

        }

        return [ 'result' => 0 , 'error' => 'Invalid File Uploaded' , 'code' => 'invalid-file' ];
    
    }














    /**
    *
    *   addReport
    *       - Create a new Report
    *
    *   Params ($_PUT):
    *       - start:            (String) The Start Date
    *       - end:              (String) The End Date
    *       - files:            (Object)
    *           - attachment_id:        (INT) The Attachment ID to Add
    *           - order:                (INT) The Performance Order
    *           - store_id:             (INT) The Store ID to add
    *           
    *
    *   Returns (JSON):
    *       Returns the [ result => ( 1 = Success, 0 = Failure ) ]
    *
    **/
    public function addReport(){  

        $recipients     = [];
        $attachments    = Input::get('files');
        $data           = array_merge([
            'start'              => '',
            'end'                => '',
            'files'              => []
        ],Input::all());
        $validator      = Validator::make( $data, [
            'start'         => 'required|before:' . Input::get('end'),
            'end'           => 'required|after:' . Input::get('start'),
            'files'         => 'required|min:1'
        ]);

        if( $validator->fails() ){

            //Validator Failed, Return
            return [ 'result' => 0 , 'errors' => $validator->errors()->all() ];

        }else{

            //Check for an existing Date Range
            $existing = Report::where(function($query) use ($data){

                $query->where(function($query) use ($data){

                    $query->where( 'start' , '>=' , $data['start'] );
                    $query->where( 'start' , '<' , $data['end'] );

                });

                $query->orWhere(function($query) use ($data){

                    $query->where( 'end' , '>' , $data['start'] );
                    $query->where( 'end' , '<=' , $data['end'] );

                });

            })->count();

            if( $existing > 0 ){

                //Validator Failed, Return
                return [ 'result' => 0 , 'errors' => ['A Report Already Exists in that Date Range'] ];

            }else{

                try {

                    //Name of Report
                    $name = date('M d Y', strtotime($data['start'])) . ' to ' . date('M d Y', strtotime($data['end']));

                    //Insert the News Article
                    $report = Report::create([
                        'user_id'           => Auth::user()->id,
                        'start'             => $data['start'],
                        'end'               => $data['end']
                    ]);


                    //Attach the Files
                    if( count( $attachments ) > 0 ){

                        foreach( $attachments as $key => $file ){
                            if(!empty( $file['file'] ) && !empty($file['file']['attachment_id'] )){

                                $attachments[ $key ] = $file['file']['attachment_id'];

                                $report->attachStore( $file['store']['id'] , [ 'attachment_id' => $file['file']['attachment_id'] , 'order' => $file['order'] ] );
                            

                            }
                        }

                    }


                    //Create the Notification
                    $report->notifications()->create(array(

                        'icon'      => 'area-chart',
                        'type'      => 'New Report',
                        'details'   => $name,
                        'url'       => '/reports/view/' . $report->id

                    ))->send([

                        'permissions' => [ Permission::where('slug' , 'reports.view' )->first()->id ]

                    ]);



                    //Update the Search Criteria
                    $report->search([

                        'title'       => 'Weekly Report: ' . $name,
                        'query'       => $name,
                        'url'         => '/reports/view/' . $report->id

                    ])->assign([

                        'permissions' => [ Permission::where('slug' , 'reports.view' )->first()->id ]

                    ]);




                    //Return Success
                    return [ 'result' => 1 ];
        
                } catch(Exception $e ){

                    //Return Failure
                    return [ 'result' => 0 , 'errors' => [ 'An Unknown Error Occured' ] ];

                }

            }

        }

    } 














    /**
    *
    *   editReport
    *       - Edits an Existing Report
    *
    *   Params ($_POST):
    *       - start:            (String) The Start Date
    *       - end:              (String) The End Date
    *       - files:            (Object)
    *           - attachment_id:        (INT) The Attachment ID to Add
    *           - order:                (INT) The Performance Order
    *           - store_id:             (INT) The Store ID to add
    *           
    *
    *   Returns (JSON):
    *       Returns the [ result => ( 1 = Success, 0 = Failure ) ]
    *
    **/
    public function editReport( $reportid ){  

        $recipients     = [];
        $attachments    = Input::get('files');
        $data           = array_merge([
            'start'              => '',
            'end'                => '',
            'files'              => []
        ],Input::all());
        $validator      = Validator::make( $data, [
            'start'         => 'required|before:' . Input::get('end'),
            'end'           => 'required|after:' . Input::get('start'),
            'files'         => 'required|min:1'
        ]);

        if( $validator->fails() ){

            //Validator Failed, Return
            return [ 'result' => 0 , 'errors' => $validator->errors()->all() ];

        }else{

            //Check for an existing Date Range
            $existing = Report::where(function($query) use ($data){

                $query->where(function($query) use ($data){

                    $query->where( 'start' , '>=' , $data['start'] );
                    $query->where( 'start' , '<' , $data['end'] );

                });

                $query->orWhere(function($query) use ($data){

                    $query->where( 'end' , '>' , $data['start'] );
                    $query->where( 'end' , '<=' , $data['end'] );

                });

            })->where( 'id' , '!=' , $reportid )->count();

            if( $existing > 0 ){

                //Validator Failed, Return
                return [ 'result' => 0 , 'errors' => ['A Report Already Exists in that Date Range'] ];

            }else{


                //Get the Report
                $report = Report::find( $reportid );

                try {

                    //Name of Report
                    $name = date('M d Y', strtotime($data['start'])) . ' to ' . date('M d Y', strtotime($data['end']));
                    $ids  = [];

                    //Update the Report
                    $report->start    = $data['start'];
                    $report->end      = $data['end'];
                    $report->save();


                    $report->detachAllStores();

                    //Attach the Files
                    if( count( $attachments ) > 0 ){

                        foreach( $attachments as $key => $file ){

                            if(!empty( $file['file'] ) && !empty($file['file']['attachment_id'] )){
                                
                                $ids[] = $file['store']['id'];

                                $attachments[ $key ] = $file['file']['attachment_id'];

                                $report->attachStore( $file['store']['id'] , [ 'attachment_id' => $file['file']['attachment_id'] , 'order' => $file['order'] ] );

                            }
                        }

                    }                    


                    if( !empty( $ids ) ){

                        //Get the Email Recipients
                        $Recipients = User::whereHas('stores' , function($query) use ($ids) {

                            $query->whereIn( 'store_id' , $ids );
                            
                        })->get();

                        /**
                        * Get all users including administrators
                        */
                        $Recipients = CommonService::getUsersForSendingMails($Recipients);
                        
                        //Send the Email
                        Mail::send([ 'html' => 'emails.report' ] , [ 'id' => $reportid , 'name' => $name ] , function($message) use ( $name , $Recipients ){

                            //Setup Recipients
                            foreach( $Recipients as $user ){
                                if( $user->email ){

                                    $user->fullname = ( !empty( $user->firstname ) && !empty( $user->lastname ) ? $user->firstname . ' ' . $user->lastname : null );

                                    $message->to( $user->email , $user->fullname );

                                }
                            }

                            //Set Subject
                            $message->subject( 'Weekly Report (Update): ' . $name . ' - ' . env('COMPANY') );

                        });

                    }


                    //Create the Notification
                    $report->notifications()->create(array(

                        'icon'      => 'area-chart',
                        'type'      => 'Report Updated',
                        'details'   => $name,
                        'url'       => '/reports/view/' . $reportid

                    ))->send([

                        'permissions' => [ Permission::where('slug' , 'reports.view' )->first()->id ]

                    ]);


                    //Update the Search Criteria
                    $report->search([

                        'title'       => $name,
                        'query'       => $name

                    ])->assign([

                        'permissions' => [ Permission::where('slug' , 'reports.view' )->first()->id ]

                    ]);


                    //Return Success
                    return [ 'result' => 1 ];
    
                } catch(Exception $e ){

                    //Return Failure
                    return [ 'result' => 0 , 'errors' => [ 'An Unknown Error Occured' ] ];

                }

            }

        }

    } 











    /**
    *
    *   deleteReport
    *       - Delete an Existing Report
    *
    *   Params (URL):
    *       - reportid:                   (INT) The Report ID
    *
    *   Returns (JSON):
    *       1. (Bool) Returns True / False
    *
    **/
    public function deleteReport( $reportid ){  


        if( $report = Report::find( $reportid ) ){

            //Name of Report
            $name = date('M d Y', strtotime($report->start)) . ' to ' . date('M d Y', strtotime($report->end));

            //Delete the Search
            $report->search()->delete();

            //Delete the Notification
            $report->notifications()->delete();

            //Create the Notification
            $report->notifications()->create([

                'icon'      => 'area-chart',
                'type'      => 'Report Deleted',
                'details'   => $name,
                'url'       => null
           
            ])->send([
         
                'permissions' => [ Permission::where('slug' , 'reports.delete' )->first()->id ],
          
            ]);

            //Delete the Store
            $report->delete();

            //Return Success
            return [ 'result' => 1 ];

        }

        //Return Failed
        return [ 'result' => 0 , 'errors' => [ 'That Article Doesn\'t exist' ] ];

    }   


}
