(function(){

	'use strict';

	var app = angular.module('System.Controllers');

	//The App Controller
	app.controller('MedicalController', [ '$window' , '$stateParams' , '$scope' , '$http' , '$location' , 'Config' , 'User' , 'Page' , 'Store' , 'Loader' , 'Upload' , function( $window , $stateParams , $scope , $http , $location , Config , User , Page , Store , Loader , Upload){
		User.ready(function(){
			
			if( Page.is( /^\/medical\/view\/([0-9]+)$/ ) && User.hasPermission([ 'medical' , 'medical.view' ]) ){




				/**
				*
				*	ROUTE: /medical/view/([0-9]+)
				*		- View the Medical Referral	
				* 	
				* 	Params (URL):
				* 		- userid: 		(INT) The Medical Referral ID to View
				*
				**/

				Page.loading.start();

				Loader.get( ':api/medical/' + $stateParams.medicalid , function( medical ){
					if( medical.data.data.id ){

						Page.loading.end();
						$scope.medical 				= medical.data.data;
						$scope.title 				= 'View Medical Referral' ;
						$scope.icon 				= 'eye';
						
					}else{

						Page.error(404);

					}
				});





			}else
			if( Page.is( /^\/medical\/edit\/([0-9]+)$/ ) && User.hasPermission([ 'medical' , 'medical.edit' ]) ){




				/**
				*
				*	ROUTE: /medical/edit/([0-9]+)
				*		- Edit the Medical Referral	
				* 	
				* 	Params (URL):
				* 		- userid: 		(INT) The Medical Referral ID to Edit
				*
				**/

				Page.loading.start();

				Loader.get( ':api/medical/' + $stateParams.medicalid , function( medical ){

					User.stores(function(stores){

						Loader.get( ':api/doctors' , function( doctors ){
				
							Page.loading.end();

							$scope.sending 			= false;
							$scope.timer 			= null;
							$scope.running 			= 'Updating';
							$scope.action 			= 'edit';
							$scope.title 			= 'Edit Medical Referral';
							$scope.button 			= 'Edit Medical Referral';
							$scope.icon 			= 'pencil-square';
							$scope.hasError 		= Page.hasError;
							$scope.errors 			= [];
							$scope.medical 			= medical.data.data;
							$scope.stores 			= stores;
							$scope.doctors 			= doctors.data;



							//On Form Submit
							$scope.submit 	= function( form ){
								if( form.$valid && !$scope.sending ){

									$scope.sending = true;

									$http.post( ':api/medical/' + $stateParams.medicalid , $scope.medical ).then(function( response ){
										if( !response.data.result ){

											$scope.sending 	= false;
											$scope.success 	= '';
											$scope.errors 	= response.data.errors;

											$window.scrollTo(0,0);

										}else{

											form.$setPristine();

											$scope.sending 	= false;
											$scope.errors 	= [];
											$scope.success 	= 'The Medical Referral has been updated';							

											$window.scrollTo(0,0);

										}
									});

								}
							};

						});

					});

				});

				






			}else
			if( Page.is( /^\/medical\/add$/ ) &&  User.hasPermission([ 'medical' , 'medical.create' ])  ){


				/**
				*
				*	ROUTE: /medical/add
				*		- Add a New Medical Referral	
				* 	
				* 	Params (URL):
				* 		n/a
				*
				**/

				User.stores(function(stores){

					Loader.get( ':api/doctors' , function( doctors ){

						Page.loading.end();

						$scope.sending 			= false;
						$scope.timer 			= null;
						$scope.saving 			= false;
						$scope.running 			= 'Creating';
						$scope.action 			= 'insert';
						$scope.title 			= 'Add Medical Referral';
						$scope.button 			= 'Add Medical Referral';
						$scope.icon 			= 'plus-circle';
						$scope.hasError 		= Page.hasError;
						$scope.errors 			= [];
						$scope.stores 			= stores;
						$scope.doctors 			= doctors.data;
						$scope.medical 			= { products:[] };

						//On Form Submit
						$scope.submit 	= function( form ){
							if( form.$valid && !$scope.sending ){

								$scope.sending = true;

								$http.put( ':api/medical' , $scope.medical ).then(function( response ){
									if( !response.data.result ){


										$scope.sending 	= false;
										$scope.success 	= '';
										$scope.errors 	= response.data.errors;

										$window.scrollTo(0,0);

									}else{

										form.$setPristine();

										$scope.sending 	= false;
										$scope.errors 	= [];
										$scope.medical 	= { products:[] };
										$scope.success 	= 'The Medical Referral has been Created';						



										$window.scrollTo(0,0);

									}
								});

							}
						};

					});

				});











			}else
			if( Page.is( /^\/medical(\/[0-9]+)?$/ ) && User.hasPermission([ 'medical' ])  ){

				/**
				*
				*	ROUTE: /medical/([0-9]+)?
				*		- The Medical Referral List
				* 	
				* 	Params (URL):
				* 		- page: 		(INT) Medical Referrals Listing Pagination
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

						Loader.get( ':api/medicals' + ( limit ? '/' + limit : '' ) + ( page ? '/' + page : '' ) , function( medical ){
							if( medical ){

								if( medical.data.data.length > 0 || page == 1 ){

									if( $scope.page.current != page ){

										$location.path( '/medical' + ( page > 1 ? '/' + page : '' ) );

									}

									$.extend( $scope.page , { current: page }, medical.data );

								}

								Page.loading.end();

								$scope.list.loading = false;

							}
						});

					};

					//On "Click Delete"
					$scope.delete 		= function( medical ){
						if( User.hasPermission([ 'medical.delete' ]) ){

							jQuery.confirm({
							    title: 				'Are you sure?',
							    content: 			'Are you sure you want to delete Medical Referral #' + medical.id,
							    confirmButton: 		'Delete Medical Referral',
								confirmButtonClass: 'btn-danger',
							    cancelButton: 		'Cancel',
								cancelButtonClass: 	'btn bgaaa coloraaa hoverbg555 hovercolorfff',
							    confirm: 			function(){

							    	$http.delete( ':api/medical/' + medical.id ).then(function( response ){
							    		if( !response.data.result ){

							    			$scope.success 	= '';
							    			$scope.errors 	= response.data.errors;

							    			$window.scrollTo(0,0);

							    		}else{

							    			$scope.errors 	= [];
							    			$scope.success 	= 'Medical Referral ' + medical.id + ' has been deleted.';

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