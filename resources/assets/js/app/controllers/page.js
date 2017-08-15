(function(){

	var app = angular.module('System.Controllers');

	//The App Controller
	app.controller('PageController', [ '$state' , '$scope' , '$location' , 'User' , 'Page' , 'Config' , function( $state , $scope , $location , User , Page , Config ){






		/**
	    *
	    *   $scope.hasPermisison
	    *       -  Checks if the current user has the correct permissions
	    *
	    *	SOURCE:
	    * 		factories/user.js
	    *
	    *   Params:
	    * 		n/a
	    *
	    *	Returns (Bool):
	    * 		1. True or False based on Permissions
	    *
	    **/
		$scope.hasPermission = User.hasPermission




		/**
	    *
	    *   $scope.get
	    *       -  Returns the Current Page Data
	    *
	    *   Params:
	    * 		n/a
	    *
	    *	Returns:
	    * 		1. The Current Page Data
	    *
	    **/
		$scope.get = function(){

			return $state.current;

		};










		/**
	    *
	    *   $scope.open
	    *       - On Click, Run the Page Opening function
	    *
	    *	SOURCE:
	    * 		factories/page.js
	    *
	    *   Params:
	    * 		- destination: 			(String) The Route to Open
	    *
	    **/
		$scope.open = Page.open;










		/**
	    *
	    *   $scope.back
	    *       - On Click, Open the Previous Page
	    *
	    *	SOURCE:
	    * 		factories/page.js
	    *
	    *   Params:
	    * 		- compare: 				(REGEX) The Regex to compare the previous destination to
	    * 		- fallback: 			(String) The fallback Destination
	    *
	    **/
		$scope.back = Page.back;










		/**
	    *
	    *   $scope.link
	    *       - Format the Link before Opening the Page
	    *
	    *	SOURCE:
	    * 		factories/page.js
	    *
	    *   Params:
	    * 		- destination: 			(String) The Route to Open
	    *
	    **/
		$scope.link = function( url ){
			
			window.location.href = url.replace( ':api' , Config.api );

		}






	}]);


	//Handles the Document Title
	app.run([ '$rootScope' , '$location' , function($rootScope , $location) {

		$rootScope.history = [];

	    $rootScope.$on('$stateChangeSuccess', function (event, current, previous) {

	    	$rootScope.history.push( $location.path() );

	    });

	}]);


})();