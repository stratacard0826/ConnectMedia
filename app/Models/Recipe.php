<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Search as RecipeSearch;
use App\Contracts\Search as RecipeSearchContract;
use App\Traits\Feedback as RecipeFeedback;
use App\Contracts\Feedback as RecipeFeedbackContract;

class Recipe extends Model implements RecipeSearchContract, RecipeFeedbackContract {

	use RecipeSearch;
    use RecipeFeedback;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'recipes';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['link_id', 'link_type', 'user_id', 'media_id', 'name', 'description', 'slug', 'type', 'status', 'status_date', 'calories', 'gluten_free', 'gluten_free_note', 'prep_time', 'cook_time', 'total_time'];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['link_id', 'link_type'];






    
    


    










    /**
    *
    *   author
    *       - Loads the User who created the Recipe
    *
    *   URL Params:
    *       n/a
    *
    *
    *   Returns (Object):
    *       1. The Recipe Directions
    *
    **/
    public function author(){

        return $this->belongsTo('App\Models\User');
   
    }


    














    /**
    *
    *   media
    *       - Loads the Belongs to Many Relationship Attachments
    *
    *   URL Params:
    *       n/a
    *
    *
    *   Returns (Object):
    *       1. The Recipe Attachment
    *
    **/
    public function media(){

        return $this->belongsToMany('App\Models\Attachment','recipe_attachment')->withPivot('name')->withTimestamps();

    }






    
    


    










    /**
    *
    *   directions
    *       - Loads the Belongs to Many Relationship for Directions
    *
    *   URL Params:
    *       n/a
    *
    *
    *   Returns (Object):
    *       1. The Recipe Directions
    *
    **/
    public function directions(){

        return $this->belongsToMany('App\Models\Recipe', 'recipe_directions')->withPivot('direction', 'order')->withTimestamps();
   
    }






    
    


    










    /**
    *
    *   ingredients
    *       - Loads the Belongs to Many Relationship for Ingredients
    *
    *   URL Params:
    *       n/a
    *
    *
    *   Returns (Object):
    *       1. The Recipe Ingredients
    *
    **/
    public function ingredients(){

        return $this->belongsToMany('App\Models\Recipe', 'recipe_ingredients')->withPivot('name' , 'notes' , 'volume' , 'unit')->withTimestamps();
   
    }






    
    


    










    /**
    *
    *   redflag
    *       - Loads the Belongs to Many Relationship for RedFlag
    *
    *   URL Params:
    *       n/a
    *
    *
    *   Returns (Object):
    *       1. The Recipe Red Flag
    *
    **/
    public function redflag(){

        return $this->belongsToMany('App\Models\Recipe', 'recipe_redflag')->withPivot('name', 'notes')->withTimestamps();

    }






    
    


    










    /**
    *
    *   redflag
    *       - Loads the Belongs to Many Relationship for RedFlag
    *
    *   URL Params:
    *       n/a
    *
    *
    *   Returns (Object):
    *       1. The Recipe Red Flag
    *
    **/
    public function serveware(){

        return $this->belongsToMany('App\Models\Recipe', 'recipe_serveware')->withPivot('name', 'notes')->withTimestamps();

    }






    
    


    










    /**
    *
    *   redflag
    *       - Loads the Belongs to Many Relationship for RedFlag
    *
    *   URL Params:
    *       n/a
    *
    *
    *   Returns (Object):
    *       1. The Recipe Red Flag
    *
    **/
    public function sides(){

        return $this->belongsToMany('App\Models\Recipe', 'recipe_sides')->withPivot('name', 'volume', 'unit', 'notes')->withTimestamps();

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

        return $this->morphToMany('App\Models\Recipe', 'link', 'faq' , '' , 'link_id' )->withPivot('category', 'question', 'answer')->withTimestamps();

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
    *   attachMedia
    *       - Attaches the Recipe to a list of Media Files
    *
    *   Params:
    *       - files:        (Object) The Files to Attach to the Recipe
    *       - data:         (Object) The Attachment Data
    *           - name:         (String) The Attachment Name
    *
    *   Returns:
    *       n/a
    *
    **/
    public function attachMedia( $file , $data ){

        $this->media()->attach( $file , $data );

    }




    






    /**
    *
    *   attachDirection
    *       - Attaches the Recipe to a list of steps
    *
    *   Params:
    *       - recipe:        (Object) The Steps to Attach to the Recipe
    * 		- data: 		 (Object) The Step Details
    * 			- direction: 	(String) The Direction Content
    * 			- order: 		(INT) The Direction Order
    *
    *   Returns:
    *       n/a
    *
    **/
    public function attachDirection( $recipe , $data ){

        $this->directions()->attach( $recipe , $data );

    }




    






    /**
    *
    *   attachIngredient
    *       - Attaches Ingredients to the Recipe
    *
    *   Params:
    *       - recipe:        (Object) The Recipe to Attach
    * 		- data: 		 (Object) The Ingredient Details
    * 			- name: 		(String) The Ingredient Name
    * 			- notes: 		(String) The Ingredient Notes
    * 			- amount: 		(INT) The Total Ingredient to Use
    * 			- unit: 		(String) The Unit of Measurement to Use
    *
    *   Returns:
    *       n/a
    *
    **/
    public function attachIngredient( $recipe , $data ){

        $this->ingredients()->attach( $recipe , $data );

    }




    






    /**
    *
    *   attachRedFlag
    *       - Attaches RedFlag to the Recipe
    *
    *   Params:
    *       - recipe:        (Object) The Recipe to Attach
    * 		- data: 		 (Object) The Red Flag Details
    * 			- name: 		(String) The Red Flag Item Name
    * 			- notes: 		(String) The Red Flag Item Notes
    *
    *   Returns:
    *       n/a
    *
    **/
    public function attachRedFlag( $recipe , $data ){

        $this->redflag()->attach( $recipe , $data );

    }




    






    /**
    *
    *   attachServeware
    *       - Attaches Serveware to the Recipe
    *
    *   Params:
    *       - recipe:        (Object) The Recipe to Attach
    * 		- data: 		 (Object) The Serveware Details
    * 			- name: 		(String) The Serveware Item Name
    * 			- notes: 		(String) The Serveware Item Notes
    *
    *   Returns:
    *       n/a
    *
    **/
    public function attachServeware( $recipe , $data ){

        $this->serveware()->attach( $recipe , $data );

    }




    






    /**
    *
    *   attachRedFlag
    *       - Attaches RedFlag to the Recipe
    *
    *   Params:
    *       - recipe:        (Object) The Recipe to Attach
    * 		- data: 		 (Object) The Red Flag Details
    * 			- name: 		(String) The Side Name
    * 			- notes: 		(String) The Side Notes
    * 			- amount: 		(INT) The Total Amount to Use
    * 			- unit: 		(String) The Unit of Measurement to Use
    *
    *   Returns:
    *       n/a
    *
    **/
    public function attachSide( $recipe , $data ){

        $this->sides()->attach( $recipe , $data );

    }




    






    /**
    *
    *   attachFAQ
    *       - Attaches the FAQ to the Recipe
    *
    *   Params:
    *       - files:        (Object) The FAQ to Attach to the Recipe
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
    *   detachAllMedia
    *       - Detaches all of the Recipe Media
    *
    *   Params:
    *       n/a
    *
    *
    *   Returns:
    *       n/a
    *
    **/
    public function detachAllMedia(){

        $this->media()->detach();

    }



    






    /**
    *
    *   detachAllDirections
    *       - Detaches all of the Recipe Directions
    *
    *   Params:
    *       n/a
    *
    *
    *   Returns:
    *       n/a
    *
    **/
    public function detachAllDirections(){

        $this->directions()->detach();

    }



    






    /**
    *
    *   detachAllIngredients
    *       - Detaches all of the Recipe Ingredients
    *
    *   Params:
    *       n/a
    *
    *
    *   Returns:
    *       n/a
    *
    **/
    public function detachAllIngredients(){

        $this->ingredients()->detach();

    }



    






    /**
    *
    *   detachAllRedFlag
    *       - Detaches all of the Recipe Red Flag Items
    *
    *   Params:
    *       n/a
    *
    *
    *   Returns:
    *       n/a
    *
    **/
    public function detachAllRedFlag(){

        $this->redflag()->detach();

    }



    






    /**
    *
    *   detachAllServeware
    *       - Detaches all of the Recipe Serveware
    *
    *   Params:
    *       n/a
    *
    *
    *   Returns:
    *       n/a
    *
    **/
    public function detachAllServeware(){

        $this->serveware()->detach();

    }



    






    /**
    *
    *   detachAllSides
    *       - Detaches all of the Recipe Sides
    *
    *   Params:
    *       n/a
    *
    *
    *   Returns:
    *       n/a
    *
    **/
    public function detachAllSides(){

        $this->sides()->detach();

    }



    






    /**
    *
    *   detachAllFAQ
    *       - Detaches all of the Recipe Directions
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
