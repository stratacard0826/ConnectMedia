(function(){

	'use strict';

	var app = angular.module('System.Controllers');

	//The App Controller
	app.controller('AuthorizationController', [ '$scope' , 'Page' , function( $scope , Page ){

		$scope.hasError = Page.hasError;
		$scope.submit 	= function( validated ){
			if( validated ){


			}
		};

	}]);


})();