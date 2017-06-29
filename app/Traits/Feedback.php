<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use App\Models\Feedback as FeedbackModel;

trait Feedback {
   











    
    


    










    /**
    *
    *   addFeedback
    *       - Loads the Belongs to Many Relationship and Sends the Feedback
    *
    *   URL Params:
    *       $feedback:          (Object) The Feedback to Send
    *           - subject:          (String) The Subject to Send
    *           - name:             (String) The Name of the Item receiving Feedback
    *           - relation:         (String) Any additional Associations to add
    *           - type:             (String) The Type of Feedback Sent
    *           - message:          (String) The Feedback Message
    *
    *   Returns (Object):
    *       1. The Recipe Feedback
    *
    **/
    public function addFeedback($feedback){

        //Store the Feedback
        $this->morphMany('App\Models\Feedback', 'link')->create( $feedback );

        //Send the Email
        FeedbackModel::send( $feedback );

    }







}
