<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mime extends Model {
   


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mimes';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'mime', 'type', 'subtype', 'extension'];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];



    /**
     * The attribute to include or exclude timestamps
     *
     * @var bool
     */
    public $timestamps = false;

}
