<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Search as PromoSearch;
use App\Contracts\Search as PromoSearchContract;
use App\Traits\Feedback as PromoFeedback;
use App\Contracts\Feedback as PromoFeedbackContract;

class Promo extends Model  implements PromoSearchContract, PromoFeedbackContract {

	use PromoSearch;
    use PromoFeedback;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'promos';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'attachment_id', 'slug', 'name', 'description', 'start', 'end'];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
    //


    














    /**
    *
    *   files
    *       - Loads the Belongs to Many Relationship Attachments
    *
    *   URL Params:
    *       n/a
    *
    *
    *   Returns (Object):
    *       1. The Promotion Attachment
    *
    **/
    public function files(){

        return $this->belongsToMany('App\Models\Attachment','promo_attachment')->withPivot('name', 'category')->withTimestamps();

    }






    
    


    










    /**
    *
    *   faq
    *       - Loads the Belongs to Many Relationship for FAQ
    *
    *   URL Params:
    *       n/a
    *
    *
    *   Returns (Object):
    *       1. The Recipe FAQs
    *
    **/
    public function faq(){

        return $this->morphToMany('App\Models\Promo', 'link', 'faq' , '' , 'link_id' )->withPivot('category', 'question', 'answer')->withTimestamps();

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







    






    /**
    *
    *   attachFile
    *       - Attaches the Marketing Material to a list of Files
    *
    *   Params:
    *       - files:        (Object) The Files to Attach to the Promotion
    *       - data:         (Object) The Attachment Data
    *           - name:         (String) The Attachment Name
    *
    *   Returns:
    *       n/a
    *
    **/
    public function attachFile( $file , $data ){

        $this->files()->attach( $file , $data );

    }




    






    /**
    *
    *   attachFAQ
    *       - Attaches the FAQ to the Promotion
    *
    *   Params:
    *       - files:        (Object) The FAQ to Attach to the Promotion
    *
    *
    *   Returns:
    *       n/a
    *
    **/
    public function attachFAQ( $recipe , $question ){

        $this->faq()->attach( $recipe , $question );

    }



    






    /**
    *
    *   detachAllFiles
    *       - Detaches all of the Recipe Files
    *
    *   Params:
    *       n/a
    *
    *
    *   Returns:
    *       n/a
    *
    **/
    public function detachAllFiles(){

        $this->files()->detach();

    }



    






    /**
    *
    *   detachAllFAQ
    *       - Detaches all of the File Directions
    *
    *   Params:
    *       n/a
    *
    *
    *   Returns:
    *       n/a
    *
    **/
    public function detachAllFAQ(){

        $this->faq()->detach();

    }


}
