(function(){

	'use strict';

	var app = angular.module('System.Controllers');

	//The App Controller
	app.controller('SearchFormController', [ '$rootScope' , '$stateParams' , '$scope' , '$location' , 'Page' , function( $rootScope , $stateParams , $scope , $location , Page ){




		/**
		*
		*	$scope.getQuery
		*		- On Submit, Open the Search Page with the Search Criteria
		* 	
		* 	Params (URL):
		* 		- page: 		(INT) Users Listing Pagination
		*
		**/
		$scope.getQuery = function(){

			return ( $location.path().match(/^\/search\/(.*)(\/[0-9]+)?/) || ['',''] )[1];

		};






		/**
		*
		*	$scope.submit
		*		- On Submit, Open the Search Page with the Search Criteria
		* 	
		* 	Params (URL):
		* 		- page: 		(INT) Users Listing Pagination
		*
		**/
		$scope.submit = function(){
			if( $scope.search.query.length > 0 ){

				//Open the Page
				Page.open( '/search/' + encodeURIComponent( $scope.search.query ) );

			}
		};







		/**
		*
		*	ON: $stateChangeStart
		*		- Update the Search on a Search Page, Remove the Search if not.
		*
		*
		**/
		$rootScope.$on('$stateChangeSuccess', function(event, toState, toParams, fromState, fromParams){

			$scope.search.query = $scope.getQuery();

		});








		//Get the Search
		$scope.search = {
		
			query: $scope.getQuery()

		};




	}]);


})();