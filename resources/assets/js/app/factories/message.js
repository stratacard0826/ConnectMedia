(function(){
	
	var app = angular.module('System.Services');
	
	app.factory('Message' , [ '$rootScope' , '$location' , function( $rootScope , $location ){


		var queue 	= [];
			current = '';

		/**
	    *
	    *   this.set
	    *       -  Sets a Message to be shown
	    *
	    *   Params:
	    * 		message: 		(String) The Message to Store
	    *
	    *	Returns:
	    * 		n/a
	    *
	    **/
		this.set = function( message ){

			messages.push( message );

		};






		/**
	    *
	    *   this.get
	    *       -  Returns the Current Message
	    *
	    *   Params:
	    * 		n/a
	    *
	    *	Returns (Array):
	    * 		1. The Current Message
	    *
	    **/
		this.get = function(){

			return current;

		};





		$rootScope.$on('$routeChangeSuccess', function(){

			messages = queue.shift() || '' ;

		});








		//Return the Object
		return this;

	}]);

})();