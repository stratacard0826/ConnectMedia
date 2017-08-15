(function(){

	'use strict';

	var app = angular.module('System.Controllers');

	//The App Controller
	app.controller('ProfileManagementController', [ '$window' , '$scope' , '$http' , 'User' , 'Page' , function( $window , $scope , $http , User , Page ){
		User.ready(function(){

			if( Page.is( /^\/profile$/ ) ){

				/**
				*
				*	ROUTE: /profile
				*		- Edit the User Profile	
				* 	
				* 	Params (URL):
				* 		- userid: 		(INT) The User ID to Edit
				*
				**/

				Page.loading.start();

				User.get(function( user ){
					if( user ){

						Page.loading.end();

						$scope.sending 			= false;
						$scope.user 			= user;
						$scope.running 			= 'Updating';
						$scope.action 			= 'edit';
						$scope.title 			= 'Edit Profile';
						$scope.button 			= 'Edit Profile';
						$scope.icon 			= 'pencil-square';
						$scope.hasError 		= Page.hasError;
						$scope.errors 			= [];

						//On Form Submit
						$scope.submit 	= function( form ){
							if( form.$valid && !$scope.sending ){

								$scope.sending = true;

								$http.post( ':api/profile' , $scope.user ).then(function( response ){
									if( !response.data.result ){

										$scope.sending 	= false;
										$scope.success 	= '';
										$scope.errors 	= response.data.errors;

										$window.scrollTo(0,0);

									}else{

										form.$setPristine();

										$scope.sending 	= false;
										$scope.errors 	= [];
										$scope.success 	= 'Your Profile has been updated.';

										$window.scrollTo(0,0);

									}
								});


							}
						};

					}else{

						Page.error(404);

					}
				});






			}

		});
	}]);


})();