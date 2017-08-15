(function(){
	
	var app 		= angular.module('System.Filters');
	



	/**
	*
	*	FILTER: range
	*		- Creates an Array from a range of numbers
	*
	* 	USAGE:
	* 		{{ range:(min):(max) }}
	*
	**/
	app.filter('range', [function(){

    	return function(input, min, max) {
      	
    		min = parseInt( min );
    		max = parseInt( max );

    		for( var i = min; i < max; i++ ) input.push( i );

    		return input;
    	
    	};

	}]);
	





})();