(function(){

	'use strict';

	var app = angular.module('System.Controllers');

	//The App Controller
	app.controller('SearchController', [ '$stateParams' , '$scope' , '$http' , '$location' , 'User' , 'Page' , 'Loader' , function( $stateParams , $scope , $http , $location , User , Page , Loader ){
		User.ready(function(){

			if( Page.is( /^\/search(\/.+)?$/ ) && User.hasPermission([ 'search' ]) ){




				/**
				*
				*	ROUTE: /admin/users/([0-9]+)?
				*		- The User List
				* 	
				* 	Params (URL):
				* 		- page: 		(INT) Users Listing Pagination
				*
				**/
				(function(){


					$scope.page.data 		= [];

					//On Page Load
					$scope.load 			= function( query , page ){

						Page.loading.start();

						var limit = $scope.page.showing;

						Loader.get( ':api/search' + ( limit ? '/' + limit : '' ) + ( page ? '/' + page : '' ) + ( query ? '/' + encodeURIComponent( query ) : '' ) , function( search ){

							if( search.data.length > 0 ){

								if( $scope.page.current != page ){

									$location.path( '/search' + ( query ? '/' + query : '' ) + ( page > 1 ? '/' + page : '' ) );

								}

								angular.extend( $scope.page , { current: page }, search );

							}

							Page.loading.end();

						});
					
					};

					//Load the First Page
					$scope.load( $stateParams.query , ( $stateParams.page || 1 ) );

				})();




			}

		});
	}]);


})();