(function(){

	'use strict';

	var app = angular.module('System.Controllers');

	//The App Controller
	app.controller('DoctorController', [ '$window' , '$stateParams' , '$scope' , '$http' , '$location' , 'Config' , 'User' , 'Page' , 'Store' , 'Loader' , 'Upload' , function( $window , $stateParams , $scope , $http , $location , Config , User , Page , Store , Loader , Upload){
		User.ready(function(){
			
			if( Page.is( /^\/medical\/doctors\/view\/([0-9]+)$/ ) && User.hasPermission([ 'doctors' , 'doctors.view' ]) ){




				/**
				*
				*	ROUTE: /doctor/view/([0-9]+)
				*		- View the Doctor	
				* 	
				* 	Params (URL):
				* 		- userid: 		(INT) The Doctor ID to View
				*
				**/

				Page.loading.start();

				Loader.get( ':api/doctor/' + $stateParams.doctorid , function( doctor ){
					if( doctor.data.data.id ){

						Page.loading.end();
						$scope.doctor 				= doctor.data.data;
						$scope.title 				= 'View Doctor' ;
						$scope.icon 				= 'eye';
						
					}else{

						Page.error(404);

					}
				});





			}else
			if( Page.is( /^\/medical\/doctors\/edit\/([0-9]+)$/ ) && User.hasPermission([ 'doctors' , 'doctors.edit' ]) ){




				/**
				*
				*	ROUTE: /doctor/edit/([0-9]+)
				*		- Edit the Doctor	
				* 	
				* 	Params (URL):
				* 		- userid: 		(INT) The Doctor ID to Edit
				*
				**/

				Page.loading.start();

				Loader.get( ':api/doctor/' + $stateParams.doctorid , function( doctor ){
					
					Page.loading.end();

					$scope.sending 			= false;
					$scope.timer 			= null;
					$scope.running 			= 'Updating';
					$scope.action 			= 'edit';
					$scope.title 			= 'Edit Doctor';
					$scope.button 			= 'Edit Doctor';
					$scope.icon 			= 'pencil-square';
					$scope.hasError 		= Page.hasError;
					$scope.doctor 			= doctor.data;
					$scope.errors 			= [];


					//On Form Submit
					$scope.submit 	= function( form ){
						if( form.$valid && !$scope.sending ){

							$scope.sending = true;

							$http.post( ':api/doctor/' + $stateParams.doctorid , $scope.doctor ).then(function( response ){
								if( !response.data.result ){

									$scope.sending 	= false;
									$scope.success 	= '';
									$scope.errors 	= response.data.errors;

									$window.scrollTo(0,0);

								}else{

									form.$setPristine();

									$scope.sending 	= false;
									$scope.errors 	= [];
									$scope.success 	= 'The Doctor has been updated';							

									$window.scrollTo(0,0);

								}
							});

						}
					};

				});

				






			}else
			if( Page.is( /^\/medical\/doctors\/add$/ ) &&  User.hasPermission([ 'doctors' , 'doctors.create' ])  ){


				/**
				*
				*	ROUTE: /doctor/add
				*		- Add a New Doctor	
				* 	
				* 	Params (URL):
				* 		n/a
				*
				**/


				
				Page.loading.end();

				$scope.sending 			= false;
				$scope.timer 			= null;
				$scope.saving 			= false;
				$scope.running 			= 'Creating';
				$scope.action 			= 'insert';
				$scope.title 			= 'Add Doctor';
				$scope.button 			= 'Add Doctor';
				$scope.icon 			= 'plus-circle';
				$scope.hasError 		= Page.hasError;
				$scope.errors 			= [];

				//On Form Submit
				$scope.submit 	= function( form ){
					if( form.$valid && !$scope.sending ){

						$scope.sending = true;

						$http.put( ':api/doctor' , $scope.doctor ).then(function( response ){
							if( !response.data.result ){


								$scope.sending 	= false;
								$scope.success 	= '';
								$scope.errors 	= response.data.errors;

								$window.scrollTo(0,0);

							}else{

								form.$setPristine();

								$scope.sending 	= false;
								$scope.errors 	= [];
								$scope.doctor 	= {};
								$scope.success 	= 'The Doctor has been Created';						



								$window.scrollTo(0,0);

							}
						});

					}
				};











			}else
			if( Page.is( /^\/medical\/doctors(\/[0-9]+)?$/ ) && User.hasPermission([ 'doctors' ])  ){

				/**
				*
				*	ROUTE: /doctor/([0-9]+)?
				*		- The Doctor List
				* 	
				* 	Params (URL):
				* 		- page: 		(INT) Doctors Listing Pagination
				*
				**/
				(function(){

					$scope.page.data 		= [];
					$scope.query 			= '';
					$scope.list 			= { query: '', loading: false };
					
					//On Page Load
					$scope.load 			= function( page ){

						Page.loading.start();

						var limit 		= $scope.page.showing;

						Loader.get( ':api/doctors' + ( limit ? '/' + limit : '' ) + ( page ? '/' + page : '' ) , function( doctor ){
							if( doctor ){

								if( doctor.data.data.length > 0 || page == 1 ){

									if( $scope.page.current != page ){

										$location.path( '/medical/doctors' + ( page > 1 ? '/' + page : '' ) );

									}

									$.extend( $scope.page , { current: page }, doctor.data );

								}

								Page.loading.end();

								$scope.list.loading = false;

							}
						});

					};

					//On "Click Delete"
					$scope.delete 		= function( doctor ){
						if( User.hasPermission([ 'doctors.delete' ]) ){

							jQuery.confirm({
							    title: 				'Are you sure?',
							    content: 			'Are you sure you want to delete doctor ' + doctor.id,
							    confirmButton: 		'Delete Doctor',
								confirmButtonClass: 'btn-danger',
							    cancelButton: 		'Cancel',
								cancelButtonClass: 	'btn bgaaa coloraaa hoverbg555 hovercolorfff',
							    confirm: 			function(){

							    	$http.delete( ':api/doctor/' + doctor.id ).then(function( response ){
							    		if( !response.data.result ){

							    			$scope.success 	= '';
							    			$scope.errors 	= response.data.errors;

							    			$window.scrollTo(0,0);

							    		}else{

							    			$scope.errors 	= [];
							    			$scope.success 	= 'Doctor ' + doctor.id + ' has been deleted.';

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




			}else{


				Page.loading.end();
				Page.error(404);


			}

		});
	}]);


})();