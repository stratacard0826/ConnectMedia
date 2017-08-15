(function(){

	'use strict';

	var app = angular.module('System.Controllers');

	//The App Controller
	app.controller('PayrollController', [ '$window' , '$stateParams' , '$scope' , '$http' , '$location' , 'Config' , 'User' , 'Store' , 'Page' , 'Loader' , 'Upload' , function( $window , $stateParams , $scope , $http , $location , Config , User , Store , Page , Loader , Upload){
		User.ready(function(){

			if( Page.is( /^\/payrolls\/view\/([0-9]+)$/ ) && User.hasPermission([ 'payrolls' , 'payrolls.view' ]) ){




				/**
				*
				*	ROUTE: /payrolls/view/([0-9]+)
				*		- View the Payroll	
				* 	
				* 	Params (URL):
				* 		- userid: 		(INT) The Payroll ID to View
				*
				**/

				Page.loading.start();

				Loader.get( ':api/payrolls/' + $stateParams.payrollid , function( payroll ){
					if( payroll.data.data.id ){

						Loader.get( ':api/store/' + payroll.data.data.store_id + '/users' , function( users ){	

							Page.loading.end();

							$scope.payroll 		= payroll.data.data;
							$scope.users 		= ( users ? users.data : null );
							$scope.title 		= 'View Payroll: ' + $scope.payroll.id ;
							$scope.icon 		= 'eye';
							$scope.calculate 	= function( data , total ){

								data.overtime 		= 0;
								data.hours 			= parseFloat( data.hours );
								data.rate 			= parseFloat( data.rate );
								data.value 			= 0;

								if( data.hours > 8 ){

									data.overtime 	= ( parseFloat( data.hours ) - 8 );
									data.hours 	  	= 8;

								}

								data.value 			= ( ( data.rate * data.hours ) + ( data.rate * data.overtime ) );
								total.hours 		+= data.hours;
								total.overtime 		+= data.overtime;
								total.value 		+= data.value;

							}

							$scope.download 	= function(){

								window.location.href = Config.api + '/payrolls/download/' + $stateParams.payrollid ;

							}

						});

					}else{

						Page.error(404);

					}
				});





			}else
			if( Page.is( /^\/payrolls\/edit\/([0-9]+)$/ ) && User.hasPermission([ 'payrolls' , 'payrolls.edit' ]) ){




				/**
				*
				*	ROUTE: /payrolls/edit/([0-9]+)
				*		- Edit the Payroll	
				* 	
				* 	Params (URL):
				* 		- userid: 		(INT) The Payroll ID to Edit
				*
				**/

				Page.loading.start();

				Loader.get( ':api/payrolls/' + $stateParams.payrollid , function( payroll ){
					if( payroll.data.data.id ){

						User.stores(function(stores){

							Loader.get( ':api/store/' + payroll.data.data.store_id + '/users' , function( users ){	

								Loader.get( ':api/positions' , function( positions ){
									
									Page.loading.end();

									$scope.sending 				= false;
									$scope.saving 				= false;
									$scope.payroll 				= payroll.data.data;
									$scope.running 				= 'Updating';
									$scope.action 				= 'edit';
									$scope.title 				= 'Edit Payroll: ' + $scope.payroll.id ;
									$scope.button 				= 'Edit Payroll';
									$scope.icon 				= 'pencil-square';
									$scope.hasError 			= Page.hasError;
									$scope.errors 				= [];
									$scope.stores 				= stores;
									$scope.users 				= ( users ? users.data : null );
									$scope.positions 			= positions.data.data;

									$scope.hasArrayError = function( cat , index , key ){
										var name = 'payroll[hours][' + cat + '][' + index + ']' + ( key ? '[' + key + ']' : '' ) ;
										if( $scope.payrollForm[ name ] ){
											return $scope.payrollForm[ name ].$invalid && $scope.payrollForm[ name ].$dirty
										}
									}

									$scope.hasArraySuccess = function( cat , index , key ){
										var name = 'payroll[hours][' + cat + '][' + index + ']' + ( key ? '[' + key + ']' : '' );
										if( $scope.payrollForm[ name ] ){
											return !$scope.payrollForm[ name ].$invalid && $scope.payrollForm[ name ].$dirty
										}
									}			
									
									$scope.dateSelect = function( payrollForm , type , wrapper ){

										payrollForm[ type ].$dirty = true;

										jQuery('[name="' + type + '"]').parents('[uib-dropdown-toggle]:first').toggleClass('open');

									}

									//Add an Hours
									$scope.add 		= function( user , form ){

										if(typeof $scope.payroll.hours[ user.id ] === 'undefined' ){
											$scope.payroll.hours[ user.id ] = [];
										}

										$scope.payroll.hours[ user.id ].push({}); 

									};

									//On Form Submit
									$scope.submit 	= function( form ){
										if( form.$valid && !$scope.sending ){

											$scope.sending 			= true;
											$scope.payroll.start	= moment( $scope.payroll.start ).format('YYYY-MM-DD');
											$scope.payroll.end		= moment( $scope.payroll.end ).format('YYYY-MM-DD');

											$http.post( ':api/payrolls/' + $scope.payroll.id , $scope.payroll ).then(function( response ){
												if( !response.data.result ){


													$scope.sending 	= false;
													$scope.success 	= '';
													$scope.errors 	= response.data.errors;

													$window.scrollTo(0,0);

												}else{

													form.$setPristine();

													$scope.sending 	= false;
													$scope.errors 	= [];
													$scope.success 	= 'Payroll has been updated.';

													$window.scrollTo(0,0);

												}
											});


										}
									};

								});

							});

						});

					}else{

						Page.error(404);

					}
				});
				






			}else
			if( Page.is( /^\/payrolls\/add$/ ) &&  User.hasPermission([ 'payrolls' , 'payrolls.create' ])  ){


				/**
				*
				*	ROUTE: /payrolls/add
				*		- Add a New Payroll	
				* 	
				* 	Params (URL):
				* 		n/a
				*
				**/
				
				Page.loading.start();

				User.stores(function(stores){

					Loader.get( ':api/store/' + ( stores.length > 0 ? stores[0].id : 0 ) + '/users' , function( users ){	

						Loader.get( ':api/positions' , function( positions ){
				
							Page.loading.end();

							$scope.sending 			= false;
							$scope.running 			= 'Creating';
							$scope.action 			= 'insert';
							$scope.loading 			= true;
							$scope.saving 			= false;
							$scope.title 			= 'Add Payroll';
							$scope.button 			= 'Add Payroll';
							$scope.icon 			= 'plus-circle';
							$scope.timer 			= null;
							$scope.hasError 		= Page.hasError;
							$scope.errors 			= [];
							$scope.stores 			= stores;
							$scope.users 			= ( users ? users.data : null );
							$scope.payroll 			= { store_id: ( stores.length > 0 ? String( stores[0].id ) : null ) , hours:{} };
							$scope.positions 		= positions.data.data;

							$scope.hasArrayError = function( cat , index , key ){
								var name = 'payroll.hours[' + cat + '][' + index + ']' + ( key ? '[' + key + ']' : '' ) ;
								if( $scope.payrollForm[ name ] ){
									return $scope.payrollForm[ name ].$invalid && $scope.payrollForm[ name ].$dirty
								}
							}

							$scope.hasArraySuccess = function( cat , index , key ){
								var name = 'payroll.hours[' + cat + '][' + index + ']' + ( key ? '[' + key + ']' : '' );
								if( $scope.payrollForm[ name ] ){
									return !$scope.payrollForm[ name ].$invalid && $scope.payrollForm[ name ].$dirty
								}
							}				
								
							$scope.dateSelect = function( payrollForm , type , wrapper ){

								payrollForm[ type ].$dirty = true;

								jQuery('[name="' + type + '"]').parents('[uib-dropdown-toggle]:first').toggleClass('open');

								$scope.save( $scope.payrollForm );

							}



							//Add an Hours
							$scope.add 		= function( user , form ){

								if(typeof $scope.payroll.hours[ user.id ] === 'undefined' ){
									$scope.payroll.hours[ user.id ] = [];
								}

								$scope.payroll.hours[ user.id ].push({}); 

								$scope.save( form );

							};


							//Save the Form
							$scope.save 	= function( form ){

								if( $scope.timer ) window.clearTimeout( $scope.timer );

								$scope.saving 	= true;
								$scope.timer 	= window.setTimeout(function(){

									$http.put( ':api/payrolls/save' , $scope.payroll ).then(function( response ){

										//Do Nothing
										$scope.saving = false;

									});

								}, 1000);

							};

							//Load the Store Data
							$scope.update 	= function( store_id ){
								Loader.get( ':api/payrolls/active/' + store_id , function( payroll ){

									$scope.payrollForm.$setPristine();

									$scope.loading = false;

									$scope.payroll 	= angular.extend( { hours: {} } , payroll.data.data || {} , {

										store_id: 	$scope.payroll.store_id

									} );

								});
							}

							//On Form Submit
							$scope.submit 	= function( form ){
								if( form.$valid && !$scope.saving && !$scope.sending ){

									$scope.sending 			= true;
									$scope.payroll.start	= moment( $scope.payroll.start ).format('YYYY-MM-DD');
									$scope.payroll.end		= moment( $scope.payroll.end ).format('YYYY-MM-DD');

									$http.put( ':api/payrolls' , $scope.payroll ).then(function( response ){
										if( !response.data.result ){

											$scope.sending 	= false;
											$scope.success 	= '';
											$scope.errors 	= response.data.errors;

											$window.scrollTo(0,0);

										}else{

											form.$setPristine();


											$scope.sending 	= false;
											$scope.errors 	= [];
											$scope.success 	= 'The Payroll has been added.';
											$scope.payroll 	= {};

											$window.scrollTo(0,0);

										}
									});

								}
							};

							//
							$scope.update( $scope.payroll.store_id );

						});

					});

				});









			}else
			if( Page.is( /^\/payrolls(\/[0-9]+)?$/ ) && User.hasPermission([ 'payrolls' ])  ){

				/**
				*
				*	ROUTE: /payrolls/([0-9]+)?
				*		- The Payroll List
				* 	
				* 	Params (URL):
				* 		- page: 		(INT) Payrolls Listing Pagination
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

						Loader.get( ':api/payrolls' + ( limit ? '/' + limit : '' ) + ( page ? '/' + page : '' ) , function( payrolls ){
							if( payrolls ){

								if( payrolls.data.data.length > 0 || page == 1 ){

									if( $scope.page.current != page ){

										$location.path( '/payrolls' + ( page > 1 ? '/' + page : '' ) );

									}

									$.extend( $scope.page , { current: page }, payrolls.data );

								}else{

									Page.error(404);

								}

								Page.loading.end();

							}
						});

					};

					//On "Click Delete"
					$scope.delete 		= function( payroll ){
						if( User.hasPermission([ 'payrolls.delete' ]) ){

							jQuery.confirm({
							    title: 				'Are you sure?',
							    content: 			'Are you sure you want to delete the Payroll ' + payroll.id,
							    confirmButton: 		'Delete Payroll',
								confirmButtonClass: 'btn-danger',
							    cancelButton: 		'Cancel',
								cancelButtonClass: 	'btn bgaaa coloraaa hoverbg555 hovercolorfff',
							    confirm: 			function(){

							    	$http.delete( ':api/payrolls/' + payroll.id ).then(function( response ){
							    		if( !response.data.result ){

							    			$scope.success 	= '';
							    			$scope.errors 	= response.data.errors;

							    			$window.scrollTo(0,0);

							    		}else{

							    			$scope.errors 	= [];
							    			$scope.success 	= 'Payroll #' + payroll.id + ' has been deleted.';

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