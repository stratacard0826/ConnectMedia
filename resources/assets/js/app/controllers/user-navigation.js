(function(){

	var app = angular.module('System.Controllers');

	//The App Controller
	app.controller('UserNavigationController', [ '$scope' , '$location' , 'User' , function( $scope , $location , User ){
		User.ready(function(){

			User.get(function( user ){

				/**
			    *
			    *   $scope.getName
			    *       -  Returns the User Name
			    *
			    *   Params:
			    * 		n/a
			    *
			    *	Returns:
			    * 		1. The Full User Name
			    *
			    **/
				$scope.getName = function(){

					if( user.firstname != '' ){

						return user.firstname + ( user.lastname != '' ? ' ' + user.lastname : '' );

					}
					
					return user.username;

				}


			});

		});
	}]);

})();