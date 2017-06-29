<?php

/**
*
*	@source 	vendor/laravel/framework/src/Illuminate/Foundation/helpers
*
*
**/


/**
* 	Get the path to a versioned Elixir file.
*
* 	@source 	https://github.com/isaacperaza/elixir-busting
* 	@requires 	http://stackoverflow.com/a/28475973/1467922 (See: bootstrap/autoload.php)
* 	@param  	string  $file
* 	@return 	string
*
* 	@throws 	\InvalidArgumentException
**/
if( !function_exists( 'elixir' ) ){

	function elixir($file)
	{
	    static $manifest = null;

	    if (is_null($manifest)) {
	        $manifest = json_decode(file_get_contents(public_path('rev-manifest.json')), true);
	    }

	    if (isset($manifest[$file])) {
	        return '/public/'.$manifest[$file];
	    }

	    throw new InvalidArgumentException("File {$file} not defined in asset manifest.");
	}

}