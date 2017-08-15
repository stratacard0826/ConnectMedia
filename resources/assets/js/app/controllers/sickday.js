(function(){

	'use strict';

	var app = angular.module('System.Controllers');

	//The App Controller
	app.controller('SickController', [ '$window' , '$stateParams' , '$scope' , '$http' , '$location' , 'Config' , 'User' , 'Page' , 'Store' , 'Loader' , 'Upload' , function( $window , $stateParams , $scope , $http , $location , Config , User , Page , Store , Loader , Upload){
		User.ready(function(){
			
			if( Page.is( /^\/admin\/sickday\/view\/([0-9]+)$/ ) && User.hasPermission([ 'sickdays' , 'sickdays.view' ]) ){




				/**
				*
				*	ROUTE: /sick/view/([0-9]+)
				*		- View the Sick Day	
				* 	
				* 	Params (URL):
				* 		- userid: 		(INT) The Sick Day ID to View
				*
				**/

				Page.loading.start();

				Loader.get( ':api/sickday/' + $stateParams.sickid , function( sick ){
					if( sick.data.data.id ){

						Page.loading.end();
						$scope.sickday 				= sick.data.data;
						$scope.title 				= 'View Sick Day' ;
						$scope.icon 				= 'eye';
						
					}else{

						Page.error(404);

					}
				});





			}else
			if( Page.is( /^\/admin\/sickday\/edit\/([0-9]+)$/ ) && User.hasPermission([ 'sickdays' , 'sickdays.edit' ]) ){




				/**
				*
				*	ROUTE: /sick/edit/([0-9]+)
				*		- Edit the Sick Day	
				* 	
				* 	Params (URL):
				* 		- userid: 		(INT) The Sick Day ID to Edit
				*
				**/

				Page.loading.start();

				User.stores(function(stores){

					Loader.get( ':api/sickday/' + $stateParams.sickid , function( sick ){

						Store.users(function( users ){
					
							Page.loading.end();

							$scope.sending 			= false;
							$scope.timer 			= null;
							$scope.running 			= 'Updating';
							$scope.action 			= 'edit';
							$scope.title 			= 'Edit Sick Day';
							$scope.button 			= 'Edit Sick Day';
							$scope.icon 			= 'pencil-square';
							$scope.hasError 		= Page.hasError;
							$scope.errors 			= [];
							$scope.stores 			= stores;	
							$scope.users 			= users;
							$scope.sickday 			= angular.extend( sick.data.data , {
								store_id:	 sick.data.data.store.id,
								user_id:	 sick.data.data.user.id
							});

							//On Date Select
							$scope.dateSelect = function( sickDayForm , type , wrapper ){
								sickDayForm[ type ].$dirty = true;
								jQuery('[uib-dropdown-toggle]:nth(' + ( wrapper - 1 ) + ')').toggleClass('open');
								$scope.save( $scope.sickday );
							}

							//On Store Select
							$scope.update = function( storeid ){
								Store.users(function( users ){

									$scope.users = users;

								}, ( storeid || 0 ) );
							};


							//On Form Submit
							$scope.submit 	= function( form ){
								if( form.$valid && !$scope.sending ){

									$scope.sending = true;

									$http.post( ':api/sickday/' + $stateParams.sickid , $scope.sickday ).then(function( response ){
										if( !response.data.result ){

											$scope.sending 	= false;
											$scope.success 	= '';
											$scope.errors 	= response.data.errors;

											$window.scrollTo(0,0);

										}else{

											form.$setPristine();

											$scope.sending 	= false;
											$scope.errors 	= [];
											$scope.success 	= 'The Sick Day has been updated';							

											$window.scrollTo(0,0);

										}
									});

								}
							};

						}, sick.data.data.store.id );
					})

				});

				






			}else
			if( Page.is( /^\/admin\/sickday\/add$/ ) &&  User.hasPermission([ 'sickdays' , 'sickdays.create' ])  ){


				/**
				*
				*	ROUTE: /sick/add
				*		- Add a New Sick Day	
				* 	
				* 	Params (URL):
				* 		n/a
				*
				**/


				User.stores(function(stores){
				
					Page.loading.end();

					$scope.sending 			= false;
					$scope.timer 			= null;
					$scope.saving 			= false;
					$scope.running 			= 'Creating';
					$scope.action 			= 'insert';
					$scope.title 			= 'Add Sick Day';
					$scope.button 			= 'Add Sick Day';
					$scope.icon 			= 'plus-circle';
					$scope.hasError 		= Page.hasError;
					$scope.errors 			= [];
					$scope.stores 			= stores;

					//On Date Select
					$scope.dateSelect = function( sickDayForm , type , wrapper ){
						sickDayForm[ type ].$dirty = true;
						jQuery('[uib-dropdown-toggle]:nth(' + ( wrapper - 1 ) + ')').toggleClass('open');
					}

					//On Store Select
					$scope.update = function( storeid ){
						Store.users(function( users ){

							$scope.users = users;

						}, ( storeid || 0 ) );
					};

					//On Form Submit
					$scope.submit 	= function( form ){
						if( form.$valid && !$scope.sending ){

							$scope.sending = true;

							$http.put( ':api/sickday' , $scope.sickday ).then(function( response ){
								if( !response.data.result ){


									$scope.sending 	= false;
									$scope.success 	= '';
									$scope.errors 	= response.data.errors;

									$window.scrollTo(0,0);

								}else{

									form.$setPristine();

									$scope.sending 	= false;
									$scope.errors 	= [];
									$scope.sickday 	= {};
									$scope.success 	= 'The Sick Day has been Added';						



									$window.scrollTo(0,0);

								}
							});

						}
					};


				
				});










			}else
			if( Page.is( /^\/admin\/sickday(\/[0-9]+)?$/ ) && User.hasPermission([ 'sickdays' ])  ){

				/**
				*
				*	ROUTE: /sick/([0-9]+)?
				*		- The Sick Day List
				* 	
				* 	Params (URL):
				* 		- page: 		(INT) Sick Days Listing Pagination
				*
				**/
				(function(){

					$scope.page.data 		= [];
					$scope.query 			= '';
					$scope.list 			= { query: '', loading: false };
					
					//On Page Load
					$scope.load 			= function( page , searching ){

						Page.loading.start();

						var limit 		= $scope.page.showing;

						Loader.get( ':api/sickdays' + ( limit ? '/' + limit : '' ) + ( page ? '/' + page : '' ) , function( sick ){
							if( sick ){

								if( sick.data.data.length > 0 || page == 1 ){

									if( $scope.page.current != page ){

										$location.path( '/admin/sickday' + ( page > 1 ? '/' + page : '' ) );

									}

									$.extend( $scope.page , { current: page }, sick.data );

								}

								Page.loading.end();

								$scope.list.loading = false;

							}
						});

					};

					//On "Click Delete"
					$scope.delete 		= function( sick ){
						if( User.hasPermission([ 'sickdays.delete' ]) ){

							jQuery.confirm({
							    title: 				'Are you sure?',
							    content: 			'Are you sure you want to delete sick ' + sick.id,
							    confirmButton: 		'Delete Sick Day',
								confirmButtonClass: 'btn-danger',
							    cancelButton: 		'Cancel',
								cancelButtonClass: 	'btn bgaaa coloraaa hoverbg555 hovercolorfff',
							    confirm: 			function(){

							    	$http.delete( ':api/sickday/' + sick.id ).then(function( response ){
							    		if( !response.data.result ){

							    			$scope.success 	= '';
							    			$scope.errors 	= response.data.errors;

							    			$window.scrollTo(0,0);

							    		}else{

							    			$scope.errors 	= [];
							    			$scope.success 	= 'Sick Day ' + sick.id + ' has been deleted.';

							    			$scope.load( 1 );

							    			$window.scrollTo(0,0);

							    		}
							    	});

							    }
							});
						}

					}

					//Load the First Page
					$scope.load( ( $stateParams.page || 1 ) , false );

				})();




			}else{


				Page.loading.end();
				Page.error(404);


			}

		});
	}]);


})();