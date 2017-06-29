<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;

interface Feedback {

    /**
     * Add Feedback
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function addFeedback($feedback);


}
