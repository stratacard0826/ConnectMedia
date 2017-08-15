(function(){

	'use strict';

	var app = angular.module('System.Controllers');

	//The App Controller
	app.controller('ReportController', [ '$window' , '$stateParams' , '$scope' , '$http' , '$location' , 'Config' , 'User' , 'Store' , 'Page' , 'Loader' , 'Upload' , function( $window , $stateParams , $scope , $http , $location , Config , User , Store , Page , Loader , Upload){
		User.ready(function(){


			if( Page.is( /^\/reports\/view\/([0-9]+)$/ ) && User.hasPermission([ 'reports' , 'reports.view' ]) ){




				/**
				*
				*	ROUTE: /reports/edit/([0-9]+)
				*		- Edit the Menu	
				* 	
				* 	Params (URL):
				* 		- userid: 		(INT) The Menu ID to Edit
				*
				**/

				Page.loading.start();

				Loader.get( ':api/reports/' + $stateParams.reportid , function( report ){
					if( report.data.data.id ){		

						Store.all(function(data){
							
							Page.loading.end();

							$scope.report 			= report.data.data;
							$scope.action 			= 'view';
							$scope.title 			= 'View Report';
							$scope.button 			= 'View Report';
							$scope.icon 			= 'eye';

						});



					}else{

						Page.error(404);

					}
				});






			}else
			if( Page.is( /^\/reports\/edit\/([0-9]+)$/ ) && User.hasPermission([ 'reports' , 'reports.edit' ]) ){




				/**
				*
				*	ROUTE: /reports/edit/([0-9]+)
				*		- Edit the Menu	
				* 	
				* 	Params (URL):
				* 		- userid: 		(INT) The Menu ID to Edit
				*
				**/

				Page.loading.start();

				Loader.get( ':api/reports/' + $stateParams.reportid , function( report ){
					if( report.data.data.id ){		

						Store.all(function(data){
							
							Page.loading.end();

							$scope.sending 			= false;
							$scope.running 			= 'Updating';
							$scope.report 			= report.data.data;
							$scope.action 			= 'edit';
							$scope.title 			= 'Edit Report';
							$scope.button 			= 'Edit Report';
							$scope.icon 			= 'pencil-square';
							$scope.hasError 		= Page.hasError;
							$scope.errors 			= [];
							$scope.today 			= new Date();

							$scope.$watch('file',function(){
								$scope.upload( $scope.files );
							});

							$scope.upload = function( files ){
								if( files && files.length ){

									if( files && files.length ){
										for( var i=0; i < files.length; i++ ){
											if( !files[i].status ){
												(function( file ){

													Upload.upload({

														url: 	':api/reports/attach',
														
														data: 	{ 'file': file },
														
														method: 'POST',
												
													}).then(function(resp){
														if( resp.data.result ){

															file.status 		= 'success';
															file.attachment_id 	= resp.data.attachment_id;

														}else{

															file.status = 'error';

														}
													},function(resp){

														file.status = 'error';

													},function(evt){

														file.status 	= 'running';
														file.progress 	= Math.min(100 , parseInt( 100 * evt.loaded / evt.total ) );

													});

												})( files[i] );
											}
										}
									}

								}
							};	

							$scope.remove = function( id ){
								delete $scope.report.files[ id ].file;
							}
							
							$scope.dateSelect = function( reportsForm , type , wrapper ){
								reportsForm[ type ].$dirty = true;
								jQuery('[uib-dropdown-toggle]:nth(' + ( wrapper - 1 ) + ')').toggleClass('open');
							}

							$scope.move = function( from , to ){
								
								$scope.report.files.splice( to , 0 , $scope.report.files.splice( from , 1 )[0] );

								for( var i=0; i < $scope.report.files.length; i++ ){
									$scope.report.files[i].order = i;
								}

							}


							//On Form Submit
							$scope.submit 	= function( form ){

								$scope.errors = [];

								if( form.$valid && !$scope.sending && $scope.errors.length == 0 ){

									$scope.sending 			= true;
									$scope.report.start 	= moment( $scope.report.start ).format('YYYY-MM-DD');
									$scope.report.end 		= moment( $scope.report.end ).format('YYYY-MM-DD');

									$http.post( ':api/reports/' + $stateParams.reportid , $scope.report ).then(function( response ){
										if( !response.data.result ){

											$scope.sending 	= false;
											$scope.success 	= '';
											$scope.errors 	= response.data.errors;

											$window.scrollTo(0,0);

										}else{

											form.$setPristine();

											$scope.sending 	= false;
											$scope.errors 	= [];
											$scope.success 	= 'Your report has been updated in the weekly reports.';

											$window.scrollTo(0,0);

										}
									});

								}
							};



						});



					}else{

						Page.error(404);

					}
				});






			}else
			if( Page.is( /^\/reports\/add$/ ) &&  User.hasPermission([ 'reports' , 'reports.create' ])  ){


				/**
				*
				*	ROUTE: /reports/add
				*		- Add a New Menu	
				* 	
				* 	Params (URL):
				* 		n/a
				*
				**/

				Store.all(function(stores){
					
					Page.loading.end();

					$scope.sending 			= false;
					$scope.running 			= 'Creating';
					$scope.action 			= 'insert';
					$scope.title 			= 'Add Report';
					$scope.button 			= 'Add Report';
					$scope.icon 			= 'plus-circle';
					$scope.hasError 		= Page.hasError;
					$scope.errors 			= [];
					$scope.today 			= new Date();
					$scope.report 			= { files:[], 'stores':stores };
					$scope.initialize 		= function(){

						for( var i=0; i < stores.length; i++ ){
							$scope.report.files[i] = {
								file: 		null,
								store: 		stores[i],
								order: 		i
							};
						}

					}

					$scope.$watch('file',function(){
						$scope.upload( $scope.files );
					});

					$scope.upload = function( files ){
						if( files && files.length ){

							if( files && files.length ){
								for( var i=0; i < files.length; i++ ){
									if( !files[i].status ){
										(function( file ){

											Upload.upload({

												url: 	':api/reports/attach',
												
												data: 	{ 'file': file },
												
												method: 'POST',
										
											}).then(function(resp){
												if( resp.data.result ){

													file.status 		= 'success';
													file.attachment_id 	= resp.data.attachment_id;

												}else{

													file.status = 'error';

												}
											},function(resp){

												file.status = 'error';

											},function(evt){

												file.status 	= 'running';
												file.progress 	= Math.min(100 , parseInt( 100 * evt.loaded / evt.total ) );

											});

										})( files[i] );
									}
								}
							}

						}
					};	

					$scope.remove = function( id ){
						delete $scope.report.files[ id ].file;
					}
					
					$scope.dateSelect = function( reportsForm , type , wrapper ){
						reportsForm[ type ].$dirty = true;
						jQuery('[uib-dropdown-toggle]:nth(' + ( wrapper - 1 ) + ')').toggleClass('open');
					}

					$scope.move = function( from , to ){
						
						$scope.report.files.splice( to , 0 , $scope.report.files.splice( from , 1 )[0] );

						for( var i=0; i < $scope.report.files.length; i++ ){
							$scope.report.files[i].order = i;
						}

					}


					//On Form Submit
					$scope.submit 	= function( form ){

						$scope.errors = [];

						if( form.$valid && !$scope.sending && $scope.errors.length == 0 ){

							$scope.sending 			= true;
							$scope.report.start 	= moment( $scope.report.start ).format('YYYY-MM-DD');
							$scope.report.end 		= moment( $scope.report.end ).format('YYYY-MM-DD');

							$http.put( ':api/reports' , $scope.report ).then(function( response ){
								if( !response.data.result ){

									$scope.sending 	= false;
									$scope.success 	= '';
									$scope.errors 	= response.data.errors;

									$window.scrollTo(0,0);

								}else{

									form.$setPristine();

									$scope.sending 	= false;
									$scope.errors 	= [];
									$scope.success 	= 'Your report has been added to the weekly reports.';
									$scope.report 	= { files:[] };

									$scope.initialize();

									$window.scrollTo(0,0);

								}
							});

						}
					};


					$scope.initialize();

				});








			}else
			if( Page.is( /^\/reports(\/[0-9]+)?$/ ) && User.hasPermission([ 'reports' ])  ){

				/**
				*
				*	ROUTE: /reports/([0-9]+)?
				*		- The Menu List
				* 	
				* 	Params (URL):
				* 		- page: 		(INT) Menus Listing Pagination
				*
				**/
				(function(){

					$scope.page.data 		= [];
					
					//On Page Load
					$scope.load 			= function( page , searching ){

						var limit = $scope.page.showing;

						Page.loading.start();

						Loader.get( ':api/reports' + ( limit ? '/' + limit : '' ) + ( page ? '/' + page : '' ), function( reports ){
							if( reports ){

								if( reports.data.data.length > 0 || page == 1 ){

									if( $scope.page.current != page ){

										$location.path( '/reports' + ( page > 1 ? '/' + page : '' ) );

									}

									$.extend( $scope.page , { current: page }, reports.data );

								}else{

									Page.error(404);

								}

								Page.loading.end();

							}
						});

					};

					//On "Click Delete"
					$scope.delete 		= function( reports ){
						if( User.hasPermission([ 'reports.delete' ]) ){

							jQuery.confirm({
							    title: 				'Are you sure?',
							    content: 			'Are you sure you want to delete the report?',
							    confirmButton: 		'Delete Menu',
								confirmButtonClass: 'btn-danger',
							    cancelButton: 		'Cancel',
								cancelButtonClass: 	'btn bgaaa coloraaa hoverbg555 hovercolorfff',
							    confirm: 			function(){

							    	$http.delete( ':api/reports/' + reports.id ).then(function( response ){
							    		if( !response.data.result ){

							    			$scope.success 	= '';
							    			$scope.errors 	= response.data.errors;

							    			$window.scrollTo(0,0);

							    		}else{

							    			$scope.errors 	= [];
							    			$scope.success 	= 'The report has been deleted.';

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