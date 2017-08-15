(function(){

	var app = angular.module('System.Controllers');

	//The App Controller
	app.controller('NavigationController', [ '$scope' , '$location' , 'User' , 'Page' , function( $scope , $location , User , Page ){
		User.ready(function(){



			/**
		    *
		    *   $scope.selected
		    *       - Check if the current navigation item is selected
		    *
		    *   Params:
		    * 		- link: 			(String) The Link to Validate
		    * 		- active: 			(String) The Classes to set on Success
		    * 		- inactive: 		(String) The Classes to set on Failure
		    *
		    *	Returns (String):
		    *		1. The Provided active or inactive string
		    *
		    **/
			$scope.selected = function( link , active , inactive ){

				var location = ( $location.path() || '/' );

				if( location == link.replace(/\*/,'') || ( link.length > 1 && link.indexOf('*') > -1 && location.indexOf( link.replace(/\*/,'') ) > -1 ) ){

					//Return the Active Class
					return active;

				}

				//Return the Inactive Class
				return inactive;
			};




		});
	}]);

})();