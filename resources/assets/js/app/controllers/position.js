(function(){

	'use strict';

	var app = angular.module('System.Controllers');

	//The App Controller
	app.controller('PositionManagementController', [ '$window' , '$stateParams' , '$scope' , '$http' , '$location' , 'Loader' , 'User' , 'Role' , 'Page' , function( $window , $stateParams , $scope , $http , $location , Loader , User , Role , Page ){
		User.ready(function(){

			if( Page.is( /^\/admin\/positions\/edit\/([0-9]+)$/ ) && User.hasPermission([ 'positions' , 'positions.edit' ]) ){




				/**
				*
				*	ROUTE: /admin/positions/edit/([0-9]+)
				*		- Edit the Positions	
				* 	
				* 	Params (URL):
				* 		- positionid: 		(INT) The Position ID to Edit
				*
				**/

				Page.loading.start();

				Loader.get( ':api/position/' + $stateParams.positionid , function( position ){
					if( position.data.id ){

						Page.loading.end();

						$scope.sending 			= false;
						$scope.position 		= position.data;
						$scope.running 			= 'Updating';
						$scope.action 			= 'edit';
						$scope.title 			= 'Edit Position: ' + position.name ;
						$scope.button 			= 'Edit Position';
						$scope.icon 			= 'pencil-square';
						$scope.hasError 		= Page.hasError;
						$scope.errors 			= [];

						//On Form Submit
						$scope.submit 	= function( form ){
							if( form.$valid && !$scope.sending ){

								$scope.sending = true;

								$http.post( ':api/position/' + position.data.id , $scope.position ).then(function( response ){
									if( !response.data.result ){

										$scope.sending 	= false;
										$scope.success 	= '';
										$scope.errors 	= response.data.errors;

										$window.scrollTo(0,0);

									}else{

										form.$setPristine();

										$scope.sending 	= false;
										$scope.errors 	= [];
										$scope.success 	= ( $scope.position.name + ' has been updated.' );

										$window.scrollTo(0,0);

									}
								});

							}
						};

					}else{

						Page.error(404);

					}
				}, $stateParams.positionid );






			}else
			if( Page.is( /^\/admin\/positions\/add$/ ) &&  User.hasPermission([ 'positions' , 'positions.create' ]) ){




				/**
				*
				*	ROUTE: /admin/positions/add
				*		- Add a New Position
				* 	
				* 	Params (URL):
				* 		n/a
				*
				**/

				Page.loading.start();


				Page.loading.end();

				$scope.sending 			= false;
				$scope.running 			= 'Creating';
				$scope.action 			= 'insert';
				$scope.title 			= 'Add Position';
				$scope.button 			= 'Add Position';
				$scope.icon 			= 'plus-circle';
				$scope.hasError 		= Page.hasError;
				$scope.errors 			= [];

				//On Form Submit
				$scope.submit 	= function( form ){
					if( form.$valid && !$scope.sending ){

						$scope.sending = true;

						$http.put( ':api/position' , $scope.position ).then(function( response ){
							if( !response.data.result ){

								$scope.sending 	= false;
								$scope.success 	= '';
								$scope.errors 	= response.data.errors;

								$window.scrollTo(0,0);

							}else{

								form.$setPristine();

								$scope.sending 		= false;
								$scope.errors 		= [];
								$scope.success 		= ( $scope.position.name + ' has been added to the position list.' );
								$scope.position 	= {};

								$window.scrollTo(0,0);

							}
						});

					}
				};










			}else
			if( Page.is( /^\/admin\/positions(\/[0-9]+)?$/ ) && User.hasPermission([ 'positions' ]) ){




				/**
				*
				*	ROUTE: /admin/positions/([0-9]+)?
				*		- The Position List
				* 	
				* 	Params (URL):
				* 		- page: 		(INT) Positions Listing Pagination
				*
				**/
				(function(){


					$scope.page.data 		= [];

					//Load the Page
					$scope.load 			= function( page ){

						Page.loading.start();

						var limit = $scope.page.showing;

						Loader.get( ':api/positions' + ( limit ? '/' + limit : '' ) + ( page ? '/' + page : '' ) , function( positions ){

							if( positions.data.data.length > 0 || page == 1 ){

								if( $scope.page.current != page ){

									$location.path( '/admin/positions' + ( page > 1 ? '/' + page : '' ) );

								}

								$.extend( $scope.page , { current: page }, positions.data );

							}else{

								Page.error(404);

							}

							Page.loading.end();

						}, page, $scope.page.showing);

					};

					//On Click "Delete"
					$scope.delete 		= function( position ){
						if( User.hasPermission([ 'positions.delete' ]) ){

							jQuery.confirm({
							    title: 				'Are you sure?',
							    content: 			'Are you sure you want to delete the position: ' + position.name,
							    confirmButton: 		'Delete Position',
								confirmButtonClass: 'btn-danger',
							    cancelButton: 		'Cancel',
								cancelButtonClass: 	'btn bgaaa coloraaa hoverbg555 hovercolorfff',
							    confirm: 			function(){

							    	$http.delete( ':api/position/' + position.id ).then(function( response ){
							    		if( !response.data.result ){

							    			$scope.success 	= '';
							    			$scope.errors 	= response.data.errors;

							    			$window.scrollTo(0,0);

							    		}else{

							    			$scope.errors 	= [];
							    			$scope.success 	= ( position.name + ' has been deleted.' );

							    			$scope.load( 1 );

							    			$window.scrollTo(0,0);

							    		}
							    	});

							    }
							});
						}
					}


					//Load the First Page
					$scope.load( ( $stateParams.page || 1 ) );


				})();




			}

		});
	}]);


})();