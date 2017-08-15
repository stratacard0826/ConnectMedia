(function(){

	'use strict';

	var app = angular.module('System.Controllers');

	//The App Controller
	app.controller('NotificationsController', [ '$rootScope' , '$scope' , '$element' , '$http' , function( $rootScope , $scope , $element , $http ){



		/**
		*
		*	$scope.setup
		*		- Initialize Variables in the Controller	
		* 	
		* 	Params (Object):
		* 		- limit: 		(INT) 			The Date to Load
		* 		- date: 		(Timestamp) 	The Last Timestamp to Load
		*
		**/
		$scope.setup 	= function( data ){

			$scope.data = angular.extend({
				limit: 			5,
				start: 			null,
				list: 			[],
				total: 			0,
				intval: 		null,
				showLoadBtn: 	false,
				loading: 		false
			},data);

			$scope.list = [];

			$scope.load();
	
		};






		/**
		*
		*	$scope.load
		*		- Loads the Notifications	
		* 	
		* 	Params:
		* 		n/a
		*
		**/
		$scope.load 	= function(){

			$scope.loading = true;

			$http.get( ':api/user/notifications/' + $scope.data.limit + '/' + ( $scope.data.start ? $scope.data.start : '' ) ).then(function( response ){

				$scope.loading 		= false;

				if( response.data.data.length > 0 ){

					$scope.data.list 	= $scope.data.list.concat( response.data.data );
					$scope.data.start 	= response.data.data[ response.data.data.length - 1 ].id ;
					$scope.list 		= $scope.data.list;
					$scope.total 		= response.data.total;
					$scope.showLoadBtn	= ( response.data.data.length >= $scope.data.limit );

				}

			});
		};










		/**
		*
		*	$scope.read
		*		- Set a Post as Read	
		* 	
		* 	Params:
		* 		n/a
		*
		**/
		$scope.read 	= function( item ){
			$http.post( ':api/user/notifications/' + item.id ).then(function(){

				//Set as Read
				item.users = [true];

				//Clear any existing intervals
				if( $scope.data.intval ) window.clearInterval( $scope.data.intval );

				//Set the Unread
				$scope.data.intval = window.setTimeout(function(){

					//Load the Updated Total
					$http.get( ':api/user/notifications/unread' ).then(function( response ){

						//Clear the Interval
						$scope.data.intval = null;

						//Update
						$scope.total = response.data.total;

					});

				},500);

			});
		};



		










		/**
		*
		*	$rootScope.poll
		*		- Poll for Notifications	
		* 	
		* 	Params:
		* 		n/a
		*
		**/
		if( typeof $rootScope.poll === 'undefined' ){

			$rootScope.poll = window.setInterval(function(){
				$http.get( ':api/user/notifications/poll' + ( $scope.data.list.length > 0 ? '/' + $scope.data.list[0].id : '' ) ).then(function(response){

					$scope.data.list 	= response.data.data.concat( $scope.data.list );
					$scope.total 	 	= response.data.total;
					$scope.list 	 	= $scope.data.list;

				});
			},10000);

		}






	}]);







	

	



	

})();