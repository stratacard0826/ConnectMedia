<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'faq';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['link_id', 'link_type', 'user_id', 'category', 'question', 'answer'];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['link_id', 'link_type'];
}
