(function(){

	'use strict';

	var app = angular.module('System.Controllers');

	//The App Controller
	app.controller('NewsDashboardController', [  '$scope' , '$http' , '$sce' , 'User' , function( $scope , $http , $sce , User ){
		User.ready(function(){

			if( User.hasPermission([ 'news.view' ]) ){


				//Setup the Base Data
				$scope.list = [];
				$scope.data = {

					limit: 			3,

					start: 			'',

					list: 			[],

					next: 			null,

					showLoadBtn: 	false,

					loading: 		false

				};





				/**
				*
				*	$scope.load
				*		- Loads the News Articles
				* 	
				* 	Params:
				* 		n/a
				*
				**/
				$scope.load 	= function(){

					//Set to Loading
					$scope.loading 		= true;

					//Load Results
					$http.get( ':api/user/news/' + $scope.data.limit + '/' + $scope.data.start ).then(function( response ){

						$scope.loading = false;

						if( response.data.data.length > 0 ){

							$scope.data.list 		= $scope.data.list.concat( response.data.data );
							$scope.data.start 		= $scope.data.list[ $scope.data.list.length - 1 ].id;
							$scope.list				= $scope.data.list;
							$scope.total 			= response.data.total;
							$scope.showLoadBtn		= ( response.data.data.length >= $scope.data.limit );

						}
						
					});
				};



				//Load the News Articles
				$scope.load();

			}

		});
	}]);


})();