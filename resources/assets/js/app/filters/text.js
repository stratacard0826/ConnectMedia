(function(){
	
	var app 		= angular.module('System.Filters');
	



	/**
	*
	*	FILTER: capitalize
	*		- Capitalizes words in a sentence
	*
	* 	USAGE:
	* 		{{ string.variable | capitalize }}
	*
	**/
	app.filter('capitalize', [function($http){

    	return function(input, all) {
      	
      		var reg = (all) ? /([^\W_]+[^\s-]*) */g : /([^\W_]+[^\s-]*)/;
      	
      		return (!!input) ? input.replace(reg, function(txt){ return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase(); }) : '';
    	
    	};

	}]);
	







})();