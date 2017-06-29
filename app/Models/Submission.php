<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Promotion;

class Submission extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'submissions';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'id' , 'logout_id' , 'zone' , 'total_sales' , 'food_sales' , 'foh' , 'boh' , 'flow' , 'people' , 'line_check' , 'deletes' , 'notes' , 'incidents' , 'fresh_outs' ];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];



    






    /**
    *
    *   store
    *       - Create and Store the Submission
    *
    *   URL Params:
    *       - id:               (INT) The Logout ID
    *       - zone:             (String) The Logout Zone (am / pm)
    *
    *
    *   Returns (Object):
    *       1. The Zone Submission
    *
    **/
    public static function logout( $id , $zone ){       

        //Get the Submission
        $submission = self::where( 'logout_id' , $id )->where( 'zone' , $zone )->get()->first();

        if( $submission ){

            //Promos
            $data           = $submission->toArray();
            $data['qsa']    = [];
            $data['promos'] = [
                'management'    => [], 
                'customer'      => [] 
            ];

            //Management
            $list = $submission->management()->get()->toArray();

            //Setup the Management Promotions
            foreach( $list as $management ){

                if(!empty( $management['pivot']['user_id'] )){

                    $management['pivot']['user'] = User::find( $management['pivot']['user_id'] )->first()->toArray();

                }

                $data['promos']['management'][] = $management['pivot'];

            }

            //Customer
            $list   = $submission->customer()->get()->toArray();

            //Setup the Management Promotions
            foreach( $list as $customer ){

                if(!empty( $customer['pivot']['promotion_id'] )){

                    $customer['pivot']['promotion'] = Promotion::find( $customer['pivot']['promotion_id'] )->first()->toArray();

                }

                $data['promos']['customer'][] = $customer['pivot'];

            }

            //QSA
            $list = $submission->qsa()->get()->toArray();

            //Setup the Management Promotions
            foreach( $list as $key => $qsa ){

                $data['qsa'][] = $qsa['pivot'];

            }

            return $data;

        }

        return null;

    }





    /**
    *
    *   store
    *       - Create and Store the Submission
    *
    *   URL Params:
    *       - id:               (INT) The Logout ID
    *       - zone:             (String) The Logout Zone (am / pm)
    *       - data:             (Object) The Logout Data
    *           - total_sales:      (INT) The Total Sales for the Day
    *           - food_sales:       (INT) The Total Food Sales for the Day
    *           - foh:              (INT) The Front of House Percentage
    *           - boh:              (INT) The Back of House Percentage
    *           - flow:             (String) The Flow of the day
    *           - people:           (String) The Staff Info
    *           - promos:               (Object) The Promotion Array
    *               - management:            (Object) The Management Promotions
    *                   - index:                 (INT) The Management Key Index
    *                       - staff:                   (INT) The Staff ID of the Recipient of the Promo
    *                       - promo:                   (String) The Promotion Name
    *                       - value:                   (INT) The Dollar Value of the Promotion
    *               - customer:              (Object) The Customer Array
    *                   - index:                 (INT) The Management Key Index
    *                       - promo:                   (String) The Promotion Name
    *                       - value:                   (INT) The Dollar Value of the Promotion
    *           - qsa:                  (Object) The Quality Service Assurance Array
    *               - index:                 (INT) The Management Key Index
    *                       - level:             (String) The QSA Level
    *                       - value:             (INT) The QSA Dollar Value
    *                       - description:       (String) Description of the QSA Return
    *           - line_check:       (String) The Line Check Data
    *           - deletes:          (String) The Deletes Data
    *           - notes:            (String) The Additional Notes
    *           - incidents:        (String) The Incident Report
    *           - fresh_outs:       (String) The Fresh Outs Data     
    *
    *
    *   Returns (Object):
    *       1. The Submission QSA
    *
    **/
    public static function store( $id , $zone , $data ){
        

        //Get the Submission
        $submission = self::where( 'logout_id' , $id )->where( 'zone' , $zone )->get()->first();

        if( $submission ){

            //Clear Existing Management Promos
            $submission->management()->detach();

            //Clear Existing Customer Promos
            $submission->customer()->detach();

            //Clear Existing QSA's
            $submission->qsa()->detach();

            //Delete the Submission
            $submission->delete();

        }

        //Clear non-numerical characters
        foreach( [ 'total_sales' , 'food_sales' , 'boh' , 'foh' ] as $reset ){
            if(!empty( $data[ $reset ] )){
                $data[ $reset ] = preg_replace( '/[^0-9.]+/', '' , $data[ $reset ] );
            }
        }

        //Create the Submission
        $submission = self::create(array_merge([
            'zone'      => $zone,
            'logout_id' => $id
        ],$data));

        //Create the Management Promo
        foreach( $data['promos']['management'] as $management ){

            $management['zone'] = $zone;

            if(isset( $management['value'] )){

                $management['value'] = preg_replace( '/[^0-9.]+/' , '' , $management['value'] );
            
            }

            $submission->management()->attach( $submission->id , [
                'user_id'   => @$management['user_id'],
                'zone'      => @$management['zone'],
                'promo'     => @$management['promo'],
                'value'     => @$management['value']
            ]);
        
        }

        //Create the Customer Promo
        foreach( $data['promos']['customer'] as $customer ){

            $customer['zone'] = $zone;

            if(isset( $customer['value'] )){

                $customer['value'] = preg_replace( '/[^0-9.]+/' , '' , $customer['value'] );
            
            }

            $submission->customer()->attach( $submission->id , [
                'promotion_id'  => @$customer['promotion_id'],
                'value'         => @$customer['value'] 
            ] );

        }

        //Create the QSA
        foreach( $data['qsa'] as $qsa ){

            $qsa['zone'] = $zone;

            if(isset( $qsa['value'] )){

                $qsa['value'] = preg_replace( '/[^0-9.]+/' , '' , $qsa['value'] );
            
            }

            $submission->qsa()->attach( $submission->id , [ 
                'level'         => @$qsa['level'],
                'value'         => @$qsa['value'],
                'description'   => @$qsa['description']
            ]);
        
        }

    }



    






    /**
    *
    *   customer
    *       - Loads the Belongs to Many Relationship Customer Promo
    *
    *   URL Params:
    *       n/a
    *
    *
    *   Returns (Object):
    *       1. The Submission Customer Promo
    *
    **/
    public function customer(){

        return $this->belongsToMany('App\Models\Submission' , 'submission_customer_promo' )->withPivot([ 'zone' , 'promotion_id' , 'value' ])->withTimestamps();
   
    }



    






    /**
    *
    *   logouts
    *       - Loads the Logout Relationship
    *
    *   URL Params:
    *       n/a
    *
    *
    *   Returns (Object):
    *       1. The Submission Logout
    *
    **/
    public function logouts(){

        return $this->hasOne('App\Models\Logout' , 'id' , 'logout_id');
   
    }



    






    /**
    *
    *   management
    *       - Loads the Belongs to Many Relationship Management Promo
    *
    *   URL Params:
    *       n/a
    *
    *
    *   Returns (Object):
    *       1. The Submission Management Promo
    *
    **/
    public function management(){

        return $this->belongsToMany('App\Models\Submission' , 'submission_management_promo' )->withPivot([ 'zone' , 'user_id' , 'promo' , 'value' ])->withTimestamps();
   
    }



    






    /**
    *
    *   qsa
    *       - Loads the Belongs to Many Relationship QSA
    *
    *   URL Params:
    *       n/a
    *
    *
    *   Returns (Object):
    *       1. The Submission QSA
    *
    **/
    public function qsa(){

        return $this->belongsToMany('App\Models\Submission' , 'submission_qsa' )->withPivot([ 'zone' , 'level' , 'value' , 'description' ])->withTimestamps();
   
    }

}
