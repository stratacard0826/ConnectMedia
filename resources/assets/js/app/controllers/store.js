(function(){

	'use strict';

	var app = angular.module('System.Controllers');

	//The App Controller
	app.controller('StoreManagementController', [ '$window' , '$stateParams' , '$scope' , '$http' , '$location' , 'User' , 'Page' , 'Store' , function( $window , $stateParams , $scope , $http , $location , User , Page , Store ){
		User.ready(function(){

			if( Page.is( /^\/admin\/stores\/edit\/([0-9]+)$/ ) && User.hasPermission([ 'stores' , 'stores.edit' ]) ){




				/**
				*
				*	ROUTE: /admin/stores/edit/([0-9]+)
				*		- Edit the Store	
				* 	
				* 	Params (URL):
				* 		- userid: 		(INT) The Store ID to Edit
				*
				**/

				Page.loading.start();

				Store.get(function( store ){
					if( store ){

						Page.loading.end();

						$scope.sending 			= false;
						$scope.store 			= store;
						$scope.running 			= 'Updating';
						$scope.action 			= 'edit';
						$scope.title 			= 'Edit Store: ' + store.id ;
						$scope.button 			= 'Edit Store';
						$scope.icon 			= 'pencil-square';
						$scope.hasError 		= Page.hasError;
						$scope.errors 			= [];

						//On Form Submit
						$scope.submit 	= function( form ){
							if( form.$valid && !$scope.sending ){

								$scope.sending = true;

								$http.post( ':api/store/' + store.id , $scope.store ).then(function( response ){
									if( !response.data.result ){

										$scope.sending 	= false;
										$scope.success 	= '';
										$scope.errors 	= response.data.errors;

										$window.scrollTo(0,0);

									}else{

										form.$setPristine();

										$scope.sending 	= false;
										$scope.errors 	= [];
										$scope.success 	= $scope.store.name  + ' has been updated.';

										$window.scrollTo(0,0);

									}
								});


							}
						};

					}else{

						Page.error(404);

					}
				}, $stateParams.storeid );






			}else
			if( Page.is( /^\/admin\/stores\/add$/ ) &&  User.hasPermission([ 'stores' , 'stores.create' ])  ){

				Page.loading.end();

				/**
				*
				*	ROUTE: /admin/stores/add
				*		- Add a New Store	
				* 	
				* 	Params (URL):
				* 		n/a
				*
				**/
				$scope.sending 			= false;
				$scope.running 			= 'Creating';
				$scope.action 			= 'insert';
				$scope.title 			= 'Add Store';
				$scope.button 			= 'Add Store';
				$scope.icon 			= 'plus-circle';
				$scope.hasError 		= Page.hasError;
				$scope.errors 			= [];

				//On Form Submit
				$scope.submit 	= function( form ){
					if( form.$valid && !$scope.sending ){

						$scope.sending = true;

						$http.put( ':api/store' , $scope.store ).then(function( response ){
							if( !response.data.result ){

								$scope.sending 	= false;
								$scope.success 	= '';
								$scope.errors 	= response.data.errors;

								$window.scrollTo(0,0);

							}else{

								form.$setPristine();

								$scope.sending 	= false;
								$scope.errors 	= [];
								$scope.success 	= $scope.store.name + ' has been added to the store list.';
								$scope.store 	= {};

								$window.scrollTo(0,0);

							}
						});

					}
				};

				//Make sure the Page is Built
				$scope.$apply();









			}else
			if( Page.is( /^\/admin\/stores(\/[0-9]+)?$/ ) && User.hasPermission([ 'stores' ])  ){




				/**
				*
				*	ROUTE: /admin/stores/([0-9]+)?
				*		- The Store List
				* 	
				* 	Params (URL):
				* 		- page: 		(INT) Stores Listing Pagination
				*
				**/
				(function(){

					$scope.page.data 		= [];
					
					//On Page Load
					$scope.load 			= function( page ){

						Page.loading.start();

						Store.all(function( stores ){

							if( stores.data.length > 0 || page == 1 ){

								if( $scope.page.current != page ){

									$location.path( '/admin/stores' + ( page > 1 ? '/' + page : '' ) );

								}

								$.extend( $scope.page , { current: page }, stores );

							}else{

								Page.error(404);

							}

							Page.loading.end();

						}, page, $scope.page.showing);

					};

					//On "Click Delete"
					$scope.delete 		= function( store ){
						if( User.hasPermission([ 'stores.delete' ]) ){

							jQuery.confirm({
							    title: 				'Are you sure?',
							    content: 			'Are you sure you want to delete the store: ' + store.name,
							    confirmButton: 		'Delete Store',
								confirmButtonClass: 'btn-danger',
							    cancelButton: 		'Cancel',
								cancelButtonClass: 	'btn bgaaa coloraaa hoverbg555 hovercolorfff',
							    confirm: 			function(){

							    	$http.delete( ':api/store/' + store.id ).then(function( response ){
							    		if( !response.data.result ){

							    			$scope.success 	= '';
							    			$scope.errors 	= response.data.errors;

							    			$window.scrollTo(0,0);

							    		}else{

							    			$scope.errors 	= [];
							    			$scope.success 	= store.name + ' has been deleted.';

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