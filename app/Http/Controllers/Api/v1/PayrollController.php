<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use App\Models\Attachment;
use App\Models\Notification;
use App\Models\Payroll;
use App\Models\Position;
use App\Models\User;
use App\Models\Store;
use App\Services\CommonService;
use Storage;
use Input;
use Validator;
use Auth;
use Mail;

class PayrollController extends Controller {
  








    /**
    *
    *   getAllPayrolls
    *       - Loads All of the Payrolls
    *
    *   URL Params:
    *       - limit:     (INT) The Page Limit (Default: 15)
    *       - page:      (INT) Pages to Load (Default: 1)
    *
    *
    *   Returns (JSON):
    *       1. The Payroll List
    *
    **/
    public function getAllPayrolls( $limit = 15 , $page = 1 ){

        $query  = Payroll::with('store')->where( 'published' , 1 );

        //Return the Data
        return [ 
            'total' => $query->count(),
            'data'  => $query->take( $limit )->skip( ( ( $page - 1 ) * $limit ) )->get()->each(function( &$item ){

            	$item['starttime']	= strtotime( $item['start'] . ' 12:59:59' ) * 1000;
                $item['endtime'] 	= strtotime( $item['end'] . ' 12:59:59' ) * 1000;

            })
        ];

    }   
    













    /**
    *
    *   getPayroll
    *       - Loads a Single Payroll
    *
    *   URL Params:
    *       - id:        (INT) The Payroll ID to Load
    *
    *
    *   Returns (JSON):
    *       1. The News Payrolls
    *
    **/
    public function getPayroll( $id ){

        $payroll     = Payroll::where( 'id' , $id )->first();

        if(!empty( $payroll )){

        	$csv 					= $this->getCSV( $payroll );
        	$submissions 			= $payroll->submission()->get();
        	$payroll 				= $payroll->toArray();
        	$payroll['starttime']	= strtotime( $payroll['start'] . ' 12:59:59' ) * 1000;
            $payroll['endtime'] 	= strtotime( $payroll['end'] . ' 12:59:59' ) * 1000;
        	$payroll['hours'] 		= [];
        	$payroll['csv'] 		= $csv;

        	foreach( $submissions as $submission ){

        		if(!isset( $payroll['hours'][ $submission->pivot->user_id ] )){

        			$payroll['hours'][ $submission->pivot->user_id ] = [];

        		}

        		$data 												= $submission->pivot->toArray();
        		$data['time'] 										= strtotime( $data['date'] . ' 12:59:50' ) * 1000;
        		$payroll['hours'][ $submission->pivot->user_id ][] 	= $data;

        	}

        }

        //Load the Payroll Data
        return [
            'result' => 1,
            'data'   => $payroll
        ];

    }    
    













    /**
    *
    *   getStorePayroll
    *       - Loads a Single, Unpublished Payroll based on teh Store ID
    *
    *   URL Params:
    *       - storeid:        (INT) The Store ID to Load
    *
    *
    *   Returns (JSON):
    *       1. The News Payrolls
    *
    **/
    public function getStorePayroll( $storeid ){


        $payroll     = Payroll::where(function($query) use ($storeid){

        	$query->where( 'store_id' , $storeid );
        	$query->where( 'published' , 0 );

        })->first();

        if(!empty( $payroll )){

        	$submissions = $payroll->submission()->get();

        	$payroll = $payroll->toArray();

        	$payroll['hours'] = [];

        	foreach( $submissions as $submission ){

        		if(!isset( $payroll['hours'][ $submission->pivot->user_id ] )){

        			$payroll['hours'][ $submission->pivot->user_id ] = [];

        		}

        		$payroll['hours'][ $submission->pivot->user_id ][] = $submission->pivot->toArray();

        	}

        }

        //Load the Payroll Data
        return [
            'result' => 1,
            'data'   => $payroll
        ];

    }  













    /**
    *
    *   savePayroll
    *       - Save the Payroll
    *
    *   Params ($_PUT):
    *       - store_id:         (INT) The Store ID for the Payroll
    * 		- start: 			(Date) The Payroll Start Date
    * 		- end: 				(Date) The Payroll End Date
    * 		- payroll: 			(Object) The Payroll Object
    * 			- [ user.id ]		(Array) The User ID
    * 				- position_id: 		(INT) The Employee's Position
    * 				- date: 			(Date) The Work Date
    * 				- hours: 			(INT) The Total Hours Worked
    * 				- rate: 			(INT) The Hourly Payment Rate         
    *
    *   Returns (JSON):
    *       Returns the [ result => ( 1 = Success, 0 = Failure ) ]
    *
    **/
    public function savePayroll(){  

        $data           = array_merge([
            'store_id'            => '',
        ],Input::all());
        $validator      = Validator::make( $data, [
        	'store_id' 						=> 'required|exists:stores,id'
        ]);

        if( $validator->fails() ){

            //Validator Failed, Return
            return [ 'result' => 0 , 'errors' => $validator->errors()->all() ];

        }else{

            try {

                if( !Auth::user()->inStore( $data['store_id'] ) ){

                    return [ 'result' => 0 , 'errors' => [ 'Invalid Store' ] ];

                }

                $payroll         = Payroll::where(function($query) use ($data){

                    $query->where( 'store_id' , $data['store_id'] );
                    $query->where( 'published' , 0 );

                })->get()->first();

                if( !$payroll ){

                    $payroll = Payroll::create([
                        'store_id'      => $data['store_id'],
                        'published'     => 0
                    ]);

                }

                //Set the Start & End Date
                if(!empty( $data['start'] )) $payroll->start = $data['start'];
                if(!empty( $data['end'] )) $payroll->end = $data['end'];

                $payroll->save();

                $payroll->submission()->detach();

                if(!empty( $data['hours'] )){
	                foreach( $data['hours'] as $userid => $array ){
	                	foreach( $array as $item ){

	                		if(isset( $item['hours'] )){

	                			$item['hours'] = preg_replace( '/[^0-9.]+/', '' , $item['hours'] );

	                		}

	                		if(isset( $item['rate'] )){

	                			$item['rate'] = preg_replace( '/[^0-9.]+/', '' , $item['rate'] );

	                		}

	                		$payroll->submission()->attach( $payroll->id , [
	                			'user_id' 			=> $userid,
	                			'position_id' 		=> @$item['position_id'],
	                			'date' 				=> @$item['date'],
	                			'hours' 			=> @$item['hours'],
	                			'rate' 				=> @$item['rate']
	                		]);

	                	}
	                }
	            }


                //Return Success
                return [ 'result' => 1 ];
    
            } catch(Exception $e ){

                //Return Failure
                return [ 'result' => 0 , 'errors' => [ 'An Unknown Error Occured' ] ];

            }

        }

    } 













    /**
    *
    *   sendPayroll
    *       - Create a Menu Item
    *
    *   Params ($_PUT):
    *       - store_id:         (INT) The Store ID for the Payroll
    * 		- start: 			(Date) The Payroll Start Date
    * 		- end: 				(Date) The Payroll End Date
    * 		- payroll: 			(Object) The Payroll Object
    * 			- [ user.id ]		(Array) The User ID
    * 				- position_id: 		(INT) The Employee's Position
    * 				- date: 			(Date) The Work Date
    * 				- hours: 			(INT) The Total Hours Worked
    * 				- rate: 			(INT) The Hourly Payment Rate  
    *           
    *
    *   Returns (JSON):
    *       Returns the [ result => ( 1 = Success, 0 = Failure ) ]
    *
    **/
    public function addPayroll(){

        $data        = Input::all();
        $validator   = Validator::make( $data , [
        	'store_id' 						=> 'required|exists:stores,id',
        	'start' 						=> 'required|date|before:' . Input::get('end'),
        	'end' 							=> 'required|date|after:' . Input::get('start'),
        	'payroll.hours.*.position_id' 	=> 'required|exists:positions,id',
        	'payroll.hours.*.date' 			=> 'required|date|after:' . Input::get('start') . '|before:' . Input::get('end'),
        	'payroll.hours.*.hours' 		=> 'required|numeric',
        	'payroll.hours.*.rate' 			=> 'required'
        ]);

        if( $validator->fails() ){

            //Validator Failed, Return
            return [ 'result' => 0 , 'errors' => $validator->errors()->all() ];

        }else{

            $payroll = Payroll::with('store')->where( 'store_id' , $data['store_id'] )->where( 'published' , 0 )->first();

            if( $payroll ){

                try {

                    
                    $payroll->published = 1;
                    $payroll->submitted = date('Y-m-d H:i:s');
                    
                    $payroll->save();


	            	$this->send( $data  , 'New' , $this->csv( $data , $payroll ) );




                    //Create the Notification
                    $payroll->notifications()->create(array(

                        'icon'      => 'sign-out',
                        'type'      => 'New Payroll',
                        'details'   => $payroll->store->name . ': ' . $payroll->start . ' to ' . $payroll->end,
                        'url'       => '/payrolls/view/' . $payroll->id

                    ))->send([

                        'permissions' => [ Permission::where('slug' , 'payrolls.view' )->first()->id ]

                    ]);





                    //Update the Search Criteria
                    $payroll->search([

                        'title'     => 'Payroll: ' . $payroll->store->name . ' - ' . date( 'l, M d, Y' , strtotime( $payroll->start ) ) . ' to ' . date( 'l, M d, Y' , strtotime( $payroll->end ) ),
                        'query'  	=> $payroll->store->name . ': ' . $payroll->start . ' to ' . $payroll->end,
                        'url'       => '/payrolls/view/' . $payroll->id

                    ])->assign([

                        'permissions' => [ Permission::where('slug' , 'payrolls.view' )->first()->id ]

                    ]);


                    //Return Success
                    return [ 'result' => 1 ];
        
                } catch(Exception $e ){

                    //Return Failure
                    return [ 'result' => 0 , 'errors' => [ 'An Unknown Error Occured' ] ];

                }

            }else{

                return [ 'result' => 0 , 'errors' => [ 'An Unknown Error Occured' ] ];

            }

        }

    } 














    /**
    *
    *   editPayroll
    *       - Edits an Existing Menu Item
    *
    *   Params ($_POST):
    *       - store_id:         (INT) The Store ID for the Payroll
    * 		- start: 			(Date) The Payroll Start Date
    * 		- end: 				(Date) The Payroll End Date
    * 		- payroll: 			(Object) The Payroll Object
    * 			- [ user.id ]		(Array) The User ID
    * 				- position_id: 		(INT) The Employee's Position
    * 				- date: 			(Date) The Work Date
    * 				- hours: 			(INT) The Total Hours Worked
    * 				- rate: 			(INT) The Hourly Payment Rate
    *           
    *
    *   Returns (JSON):
    *       Returns the [ result => ( 1 = Success, 0 = Failure ) ]
    *
    **/
    public function editPayroll( $payrollid ){  

        $data        = Input::all();
        $validator   = Validator::make( $data , [
        	'store_id' 						=> 'required|exists:stores,id',
        	'start' 						=> 'required|date|before:' . Input::get('end'),
        	'end' 							=> 'required|date|after:' . Input::get('start'),
        	'payroll.hours.*.position_id' 	=> 'required|exists:positions,id',
        	'payroll.hours.*.date' 			=> 'required|date|after:' . Input::get('start') . '|before:' . Input::get('end'),
        	'payroll.hours.*.hours' 		=> 'required|numeric',
        	'payroll.hours.*.rate' 			=> 'required'
        ]);

        if( $validator->fails() ){

            //Validator Failed, Return
            return [ 'result' => 0 , 'errors' => $validator->errors()->all() ];

        }else{

            //Get the Payroll
            $payroll = Payroll::with('store')->find( $payrollid );

            try {

            	$payroll->start = $data['start'];
            	$payroll->end 	= $data['end'];

                $payroll->save();

                $payroll->submission()->detach();

                if(!empty( $data['hours'] )){
	                foreach( $data['hours'] as $userid => $array ){
	                	foreach( $array as $item ){

	                		$item['hours'] 	= preg_replace( '/[^0-9.]+/', '' , $item['hours'] );
	                		$item['rate'] 	= preg_replace( '/[^0-9.]+/', '' , $item['rate'] );

	                		$payroll->submission()->attach( $payroll->id , [
	                			'user_id' 			=> $userid,
	                			'position_id' 		=> $item['position_id'],
	                			'date' 				=> $item['date'],
	                			'hours' 			=> $item['hours'],
	                			'rate' 				=> $item['rate']
	                		]);

	                	}
	                }
	            }

	            $this->send( $data  , 'Updated' , $this->csv( $data , $payroll ) );


                //Create the Notification
                $payroll->notifications()->create(array(

                    'icon'      => 'sign-out',
                    'type'      => 'Payroll Updated',
                    'details'   => $payroll->store->name . ': ' . $payroll->start . ' to ' . $payroll->end,
                    'url'       => '/payrolls/view/' . $payrollid

                ))->send([

                    'permissions' => [ Permission::where('slug' , 'payrolls.view' )->first()->id ]

                ]);


                //Return Success
                return [ 'result' => 1 ];
    
            } catch(Exception $e ){

                //Return Failure
                return [ 'result' => 0 , 'errors' => [ 'An Unknown Error Occured' ] ];

            }

        }

    } 











    /**
    *
    *   deletePayroll
    *       - Delete an Existing Payroll
    *
    *   Params (URL):
    *       - recipeid:                   (INT) The Payroll ID
    *
    *   Returns (JSON):
    *       1. (Bool) Returns True / False
    *
    **/
    public function deletePayroll( $payrollid ){  


        if( $payroll = Payroll::with('store')->find( $payrollid ) ){

            //Delete the Search
            $payroll->search()->delete();

            //Delete the Notification
            $payroll->notifications()->delete();

            //Create the Notification
            $payroll->notifications()->create([

                'icon'      => 'sign-out',
                'type'      => 'Payroll Deleted',
                'details'   => $payroll->store->name . ': ' . $payroll->start . ' to ' . $payroll->end,
                'url'       => null
           
            ])->send([
         
                'permissions' => [ Permission::where('slug' , 'payrolls.delete' )->first()->id ],
          
            ]);

            //Delete the Store
            $payroll->delete();

            //Return Success
            return [ 'result' => 1 ];

        }

        //Return Failed
        return [ 'result' => 0 , 'errors' => [ 'That Payroll Doesn\'t exist' ] ];

    }  






    /**
    *
    *   downloadPayroll
    *       - Downloads the Payroll
    *
    *   Params:
    *       - payroll:          (Object) The Payroll Object
    *
    *
    *   Returns:
    *       - The Download
    *
    **/
    public function downloadPayroll( $payrollid ){ 


        $payroll = Payroll::with('store')->find( $payrollid )->firstOrFail();
        $csv     = $this->getCSV( $payroll );

        return response()->download( $csv->path );

    }








    /**
    *
    *   serveFile
    *       - Serve an Attached File
    *
    *   Request Params:
    *       - slug:         (String) The Slug of the File to Load
    *
    *   Returns (JSON):
    *       1. The Attachment
    *
    **/
    public function serveFile( $slug ){

        $attachment = Attachment::where( 'slug' , $slug )->firstOrFail();

        return response()->download( Attachment::path( $attachment->filename , $attachment->disk ) );

    }






    /**
    *
    *   getCSV
    *       - Acquires the CSV Name & Path
    *
    *   Params:
    * 		- payroll: 			(Object) The Payroll Object
    *
    *
    *   Returns (Array):
    * 		- name: 			(String) The CSV File Name
    * 		- path: 			(String) The Absolute Path to the CSV File
    * 		- url: 				(String) The URL to the CSV File
    *
    **/
    private function getCSV( $payroll ){

    	$filename = str_slug( 'Payroll ' . $payroll->store->name . ': ' . $payroll->start . ' to ' . $payroll->end ) . '.csv';

        //Prepare the Filename
        return (Object)[
        	
        	'name' => $filename ,

        	'path' 	=> Attachment::path( 'payroll/' . $filename , 'public' ),

        	'url' 	=> Attachment::URL( 'payroll/' . $filename , 'public' )

        ];

    }











    /**
    *
    *   csv
    *       - Generates the CSV
    *
    *   Params:
    * 		- data: 			(Object) The Data to Send
    *       	- store_id:         (INT) The Store ID for the Payroll
    * 			- start: 			(Date) The Payroll Start Date
    * 			- end: 				(Date) The Payroll End Date
    * 			- payroll: 			(Object) The Payroll Object
    * 				- [ user.id ]		(Array) The User ID
    * 					- position_id: 		(INT) The Employee's Position
    * 					- date: 			(Date) The Work Date
    * 					- hours: 			(INT) The Total Hours Worked
    * 					- rate: 			(INT) The Hourly Payment Rate
    *
    * 		- payroll: 			(Object) The Payroll Object
    *
    *   Returns (Array):
    * 		- name: 			(String) The CSV File Name
    * 		- path: 			(String) The Absolute Path to the CSV File
    * 		- url: 				(String) The URL to the CSV File
    *
    **/
    private function csv( $data , $payroll ){


	    $date 			 = strtotime($data['start']);
	    $end 			 = date( 'Y-m-d' , strtotime( $data['end'] . ' + 1 day' ) );
	    $csv 			 = [
	    	['Name']
	    ];

	    $i=0;

	    //Add the Dates to teh CSV
	    while( date( 'Y-m-d' , $date ) != $end ){

	    	$csv[0][] 	= '"' . date('l, M d, Y' , $date ) . '"';

		    //Prepare the Payroll Data
		    foreach( array_keys( $data['hours'] ) as $index => $userid ){

		    	$found = false;

		    	if(!isset( $csv[ $index + 1 ] )){

		    		$user = User::find( $userid )->first();

		    		$csv[ $index + 1 ] = [

		    			'"' . preg_replace( '/[^0-9a-z\s]/i', '' , $user->firstname . ' ' . $user->lastname ) . '"'

		    		];

		    	}

	    		foreach( $data['hours'][ $userid ] as $item ){
	    			if( $item['date'] == date( 'Y-m-d' , $date ) ){

	    				$found = true;

	    				$csv[ $index + 1 ][] = number_format( (float)$item['hours'] , 2 );

	    			}
	    		}


	    		if( !$found ){

	    			$csv[ $index + 1 ][] = '';

	    		}

	    	}


	    	$date 		= strtotime( date( 'Y-m-d' , $date ) . ' + 1 day' );

	    	$i++;
	    	if( $i == 100 ) die('FAILEd');
	    
	    }


	    foreach( array_keys( $data['hours'] ) as $index => $userid ){

	    	$hours 		= 0;
	    	$overtime 	= 0;
	    	$total 		= 0;

	    	foreach( $data['hours'][ $userid ] as $item ){

	    		$item['overtime']    = 0;
                $val['hours']        = preg_replace( '/[^0-9.]+/', '' , $item['hours']);
                $val['rate']         = preg_replace( '/[^0-9.]+/', '' , $item['rate']);

	    		if( $item['hours'] > 8 ){

	    			$item['overtime'] 	= ( $item['hours'] - 8 );
	    			$item['hours'] 		= 8;

	    		}

	    		$hours 		+= $item['hours'];
	    		$overtime 	+= $item['overtime'];
	    		$total 		+= ( ( $item['hours'] * $item['rate'] ) + ( $item['overtime'] * $item['rate'] ) );

	    	}

	    	$csv[ $index + 1 ][] = '$' . number_format( (float)$data['hours'][ $userid ][0]['rate'] , 2 );
	    	$csv[ $index + 1 ][] = number_format( (float)$hours , 2 );
	    	$csv[ $index + 1 ][] = number_format( (float)$overtime , 2 );
	    	$csv[ $index + 1 ][] = '$' . number_format( (float)$total , 2 );

	    }


	    $csv[0][] = 'Rate';
	    $csv[0][] = 'Hours';
	    $csv[0][] = 'Overtime';
	    $csv[0][] = 'Total';

	    foreach( $csv as $key => $row ){
	    	$csv[ $key ] = implode( ',' , $row );
	    }


	    //Get the CSV Details
	    $file = $this->getCSV( $payroll );

        //Make the Zip Directory
        Storage::disk('public')->makeDirectory( 'payroll' , 0755 , false , true );

        //Create the CSV File
        Storage::disk('public')->put( 'payroll/' . $file->name , implode( "\n" , $csv ) );

        //
        return $file;

    }













    /**
    *
    *   send
    *       - Send out the Payroll
    *
    *   Params:
    * 		- data: 			(Object) The Data to Send
    *       	- store_id:         (INT) The Store ID for the Payroll
    * 			- start: 			(Date) The Payroll Start Date
    * 			- end: 				(Date) The Payroll End Date
    * 			- payroll: 			(Object) The Payroll Object
    * 				- [ user.id ]		(Array) The User ID
    * 					- position_id: 		(INT) The Employee's Position
    * 					- date: 			(Date) The Work Date
    * 					- hours: 			(INT) The Total Hours Worked
    * 					- rate: 			(INT) The Hourly Payment Rate
    *
    * 		- type: 			(String) The Type of Email (New or Updated)
    * 		- csv: 				(Object)
    * 			- name: 			(String) The CSV File Name
    * 			- path: 			(String) The Absolute Path to the CSV File
    * 			- url: 				(String) The URL to the CSV File
    *
    *   Returns:
    *       n/a
    *
    **/
    private function send( $data , $type , $csv ){

    	$Recipients = User::where(function($query) use ($data){

	        //Get the recipient roles
	        $query->where(function($query){

	            //Get all the Roles with Recipient ALL status
	            $roles = Role::whereHas( 'permissions' , function($query){

	                $query->where( 'slug' , 'payrolls.recipients.all' );

	            })->get()->lists('id');

	            if(!empty( $roles )){

	                //Has the Roles
	                $query->whereHas( 'roles' , function($query) use ($roles){

	                    $query->whereIn( 'role_id' , $roles );

	                });

	            }

	        });


	        //Get the recipient roles
	        $query->orWhere(function($query) use ($data){

	            //Get the Roels with Recipient Single status
	            $roles = Role::whereHas( 'permissions' , function($query){
	           
	                $query->where( 'slug' , 'payrolls.recipients.single' );
	           
	            })->get()->lists('id');

	            if(!empty( $roles )){

	                //Has the Store ID
	                $query->whereHas( 'stores' , function($query) use ($roles, $data){

	                    $query->where( 'store_id' , $data['store_id'] );

	                });

	                //Has the Role ID
	                $query->whereHas( 'roles' , function($query) use ($roles){

	                    $query->whereIn( 'role_id' , $roles );

	                });

	            }

	        });



	        //CGet the "All" Role User Permissions
	        $query->orWhereHas( 'userPermissions' , function($query){

	            //Has Permission with All Recipients
	            $query->where( 'slug' , 'payrolls.recipients.all' );

	        });


	        //Get the Single Role Permission Users
	        $query->orWhere(function($query) use ($data){

	            //Has the Store
	            $query->whereHas( 'stores' , function($query) use ($data){

	                $query->where( 'store_id' , $data['store_id'] );

	            });

	            //Has the User Permisison
	            $query->whereHas( 'userPermissions' , function($query){

	                $query->where( 'slug' , 'payrolls.recipients.single' );

	            });

	        });


	    })->get();


	    //Set the Payroll
	    $data['users'] 	 = [];

	    //Prepare the Payroll Data
	    foreach( $data['hours'] as $userid => $hour ){

	    	$data['users'][ $userid ] 			= User::find( $userid )->first()->toArray();
	    	$data['users'][ $userid ]['total'] 	= [
	    		'hours' 	=> 0,
	    		'overtime' 	=> 0,
	    		'value' 	=> 0,
	    	];

	    	foreach( $hour as $key => $val ){

	    		$val['overtime']     = 0;
                $val['hours']        = preg_replace( '/[^0-9.]+/', '' , $val['hours']);
                $val['rate']         = preg_replace( '/[^0-9.]+/', '' , $val['rate']);

	    		if( $val['hours'] > 8 ){

	    			$val['overtime'] 	= ( $val['hours'] - 8 );
	    			$val['hours']		= 8;

	    		}

	    		$val['value'] = ( $val['overtime'] * $val['rate'] ) + ( $val['hours'] * $val['rate'] ) ;

	    		$data['hours'][ $userid ][ $key ] = [
	    			'date' 		=> $val['date'],
	    			'hours' 	=> $val['hours'],
	    			'overtime' 	=> $val['overtime'],
	    			'rate' 		=> $val['rate'],
	    			'value' 	=> $val['value']
	    		];

	    		$data['users'][ $userid ]['total']['hours'] 	+= $val['hours'];
	    		$data['users'][ $userid ]['total']['overtime'] 	+= $val['overtime'];
	    		$data['users'][ $userid ]['total']['value'] 	+= $val['value'];

	    	}
	    }


        /**
        * Get all users including administrators
        */
        $Recipients = CommonService::getUsersForSendingMails($Recipients);

	    //Send the Email
	    Mail::send([ 'html' => 'emails.payroll'] , [ 'data' => $data ] , function($message) use ( $data , $Recipients , $type , $csv){

            $message->attach( $csv->path );

	        //Setup Recipients
	        foreach( $Recipients as $user ){
	            if( $user->email ){

	                $user->fullname = ( !empty( $user->firstname ) && !empty( $user->lastname ) ? $user->firstname . ' ' . $user->lastname : null );

	                $message->to( $user->email , $user->fullname );

	            }
	        }

	        //Set Subject
	        $message->subject( $type . ' ' . env('COMPANY') . ' Payroll: ' . date( 'l, M d, Y' , strtotime( $data['start'] ) ) . ' to ' . date( 'l, M d, Y' , strtotime( $data['end'] ) ) );

	    });


    }






    
}
