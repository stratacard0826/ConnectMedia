(function(){

	'use strict';

	var app = angular.module('System.Controllers');

	//The App Controller
	app.controller('EventController', [ '$window' , '$stateParams' , '$scope' , '$http' , '$location' , 'Upload' , 'User' , 'Page' , 'Loader' , 'Store' , 'Role' , function( $window , $stateParams , $scope , $http , $location , Upload , User , Page , Loader , Store , Role ){
		User.ready(function(){

			if( Page.is( /^\/admin\/events\/edit\/([0-9]+)$/ ) && User.hasPermission([ 'events' , 'events.edit' ]) ){

				/**
				*
				*	ROUTE: /events/edit/([0-9]+)
				*		- Edit a Events Event
				* 	
				* 	Params (URL):
				* 		- userid: 		(INT) The Events ID to Edit
				*
				**/
				Page.loading.start();


				Loader.get( ':api/events/' + $stateParams.eventid , function( event ){

					if( event.data.data.id ){

						var event = event.data.data;

						Store.all(function( stores ){

							Role.all(function( roles ){

								Page.loading.end();

								for( var i=0; i < event.attachments.length; i++ ){

									event.attachments[i].attachment_id 	= event.attachments[i].id;
									event.attachments[i].name 			= event.attachments[i].filename;
									event.attachments[i].progress 		= 100;
									event.attachments[i].status 		= 'success';

								}

								$scope.action 				= 'edit';
								$scope.running 				= 'Editting';
								$scope.title 				= 'Edit Event';
								$scope.button 				= 'Edit Event';
								$scope.icon 				= 'pencil-square';
								$scope.hasError 			= Page.hasError;
								$scope.errors 				= [];
								$scope.success 				= '';
								$scope.sending 				= false;
								$scope.today 				= new Date();
								$scope.events 				= angular.extend({ 'sendemail':0, 'files':event.attachments }, event);
								$scope.events.start			= moment( $scope.events.start ).format('YYYY-MM-DD HH:mm:ss');
								$scope.events.end 			= moment( $scope.events.end ).format('YYYY-MM-DD HH:mm:ss');
                                $scope.config               = { 'specifications': $scope.events.reminders};

								$scope.roles 				= {
									data: 					roles,
									settings: 				{
										displayProp: 				'name',
										smartButtonMaxItems: 		3,
				    					smartButtonTextConverter: 	function(itemText, originalItem){ return itemText; }
									},
									events: 				{
										onItemSelect: 				function(){ $scope.eventForm.roles.$dirty = $scope.events.roles.length; },
										onItemDeselect: 			function(){ $scope.eventForm.roles.$dirty = $scope.events.roles.length; },
										onSelectAll: 				function(){ $scope.eventForm.roles.$dirty = $scope.events.roles.length; },
										onDeselectAll: 				function(){ $scope.eventForm.roles.$dirty = false;  }
									}
								};
								$scope.stores 			= {
									data: 					stores,
									settings: 				{
										displayProp: 				'name',
										smartButtonMaxItems: 		3,
				    					smartButtonTextConverter: 	function(itemText, originalItem){ return itemText; },
									},
									events: 				{
										onItemSelect: 				function(){ $scope.eventForm.stores.$dirty = $scope.events.stores.length; },
										onItemDeselect: 			function(){ $scope.eventForm.stores.$dirty = $scope.events.stores.length; },
										onSelectAll: 				function(){ $scope.eventForm.stores.$dirty = $scope.events.stores.length; },
										onDeselectAll: 				function(){ $scope.eventForm.stores.$dirty = false; }
									}
								};					

								$scope.$watch('file',function(){

									$scope.upload( $scope.files );

								});

								$scope.remove = function( index ){
									$scope.events.files.splice( index , 1 );
								};

								$scope.upload = function( files ){
									if( files && files.length ){

										if( files && files.length ){
											for( var i=0; i < files.length; i++ ){
												if( !files[i].status ){
													(function( file ){

														Upload.upload({

															url: 	':api/events/attach',
															
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

								$scope.dateSelect = function( eventForm , type , wrapper ){
									eventForm[ type ].$dirty = true;
									jQuery('[uib-dropdown-toggle]:nth(' + ( wrapper - 1 ) + ')').toggleClass('open');
								}

								$scope.setAllowedDates = function( view , $dates , $leftDate, $upDate, $rightDate ){
									
									for( var i=0; i < $dates.length; i++ ){
										$dates[i].selectable = ( $dates[i].localDateValue() >= $scope.today  );
									}

									$leftDate.selectable  = ( $leftDate.localDateValue() >= $scope.today );
									$rightDate.selectable = ( $rightDate.localDateValue() >= $scope.today );

								};

								//On Form Submit
								$scope.submit 	= function( form ){
									if( form.$valid ){

										$scope.sending = true;

										//Prepare the Data
										$scope.events.start 	= moment( $scope.events.start 	).format('YYYY-MM-DD HH:mm:ss');
										$scope.events.end 		= moment( $scope.events.end 	).format('YYYY-MM-DD HH:mm:ss');
										$scope.events.today 	= moment( $scope.today 			).format('YYYY-MM-DD HH:mm:ss');
										$scope.events.sendemail = +$scope.events.sendemail;
										
										$http.post( ':api/events/' + $stateParams.eventid , $scope.events ).then(function( response ){
											if( !response.data.result ){


												$scope.sending 	= false;
												$scope.success 	= '';
												$scope.errors 	= response.data.errors;

												$window.scrollTo(0,0);

											}else{

												form.$setPristine();

												$scope.sending 	= false;
												$scope.errors 	= [];
												$scope.success 	= 'Your Event has been updated!';

												$window.scrollTo(0,0);

											}
										});

									}
								};

							});

						});

					}else{

						//Page not Found
						Page.error(404);

					}

				});







			}else
			if( Page.is( /^\/admin\/events\/add$/ ) && User.hasPermission([ 'events' , 'events.create' ]) ){
                

				/**
				*
				*	ROUTE: /events/add
				*		- Add a Events Event	
				* 	
				* 	Params (URL):
				* 		n/a
				*
				**/
				Page.loading.start();
				Store.all(function( stores ){

					Role.all(function( roles ){

						Page.loading.end();

						$scope.action 			= 'insert';
						$scope.running 			= 'Creating';
						$scope.title 			= 'Add Event';
						$scope.button 			= 'Add Event';
						$scope.icon 			= 'plus-circle';
						$scope.hasError 		= Page.hasError;
						$scope.errors 			= [];
						$scope.success 			= '';
						$scope.sending 			= false;
						$scope.today 			= new Date();
						$scope.events 			= { 'roles':[] , 'stores':[], 'sendemail':1, 'files':[] };
                        $scope.config           = { 'specifications': []};
                        $scope.events.reminders = $scope.config.specifications;
						$scope.roles 			= {
							data: 					roles,
							settings: 				{
								displayProp: 				'name',
								smartButtonMaxItems: 		3,
		    					smartButtonTextConverter: 	function(itemText, originalItem){ return itemText; }
							},
							events: 				{
								onItemSelect: 				function(){ $scope.eventForm.roles.$dirty = $scope.events.roles.length; },
								onItemDeselect: 			function(){ $scope.eventForm.roles.$dirty = $scope.events.roles.length; },
								onSelectAll: 				function(){ $scope.eventForm.roles.$dirty = $scope.events.roles.length; },
								onDeselectAll: 				function(){ $scope.eventForm.roles.$dirty = false;  }
							}
						};
						$scope.stores 			= {
							data: 					stores,
							settings: 				{
								displayProp: 				'name',
								smartButtonMaxItems: 		3,
		    					smartButtonTextConverter: 	function(itemText, originalItem){ return itemText; },
							},
							events: 				{
								onItemSelect: 				function(){ $scope.eventForm.stores.$dirty = $scope.events.stores.length; },
								onItemDeselect: 			function(){ $scope.eventForm.stores.$dirty = $scope.events.stores.length; },
								onSelectAll: 				function(){ $scope.eventForm.stores.$dirty = $scope.events.stores.length; },
								onDeselectAll: 				function(){ $scope.eventForm.stores.$dirty = false; }
							}
						};

						$scope.$watch('file',function(){

							$scope.upload( $scope.files );

						});

						$scope.remove = function( index ){
							$scope.events.files.splice( index , 1 );
						};

						$scope.upload = function( files ){
							if( files && files.length ){

								if( files && files.length ){
									for( var i=0; i < files.length; i++ ){
										if( !files[i].status ){
											(function( file ){

												Upload.upload({

													url: 	':api/events/attach',
													
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

						$scope.dateSelect = function( eventForm , type , wrapper ){
							eventForm[ type ].$dirty = true;
							jQuery('[uib-dropdown-toggle]:nth(' + ( wrapper - 1 ) + ')').toggleClass('open');
						}

						$scope.setAllowedDates = function( view , $dates , $leftDate, $upDate, $rightDate ){
							
							for( var i=0; i < $dates.length; i++ ){
								$dates[i].selectable = ( $dates[i].localDateValue() >= $scope.today  );
							}

							$leftDate.selectable  = ( $leftDate.localDateValue() >= $scope.today );
							$rightDate.selectable = ( $rightDate.localDateValue() >= $scope.today );

						};

						//On Form Submit
						$scope.submit 	= function( form ){
							if( form.$valid ){

								$scope.sending = true;

								//Prepare the Data
								$scope.events.start 	= moment( $scope.events.start 	).format('YYYY-MM-DD HH:mm:ss');
								$scope.events.end 		= moment( $scope.events.end 	).format('YYYY-MM-DD HH:mm:ss');
								$scope.events.today 	= moment( $scope.today 			).format('YYYY-MM-DD HH:mm:ss');
								$scope.events.sendemail = +$scope.events.sendemail;
                                

								$http.put( ':api/events' , $scope.events ).then(function( response ){
									if( !response.data.result ){


										$scope.sending 	= false;
										$scope.success 	= '';
										$scope.errors 	= response.data.errors;

										$window.scrollTo(0,0);

									}else{

										form.$setPristine();

										$scope.sending 		= false;
										$scope.errors 		= [];
										$scope.success 		= 'Your Event has been added to the Company Events!';
										$scope.events 		= { 'roles':[], 'stores':[], 'files':[], 'sendemail':1, 'details':'' };

										$window.scrollTo(0,0);

									}
								});

							}
						};

					});

				});

			}else
			if( Page.is( /^\/admin\/events\/view\/([0-9]+)$/ ) && User.hasPermission([ 'events' , 'events.view' ]) ){


				/**
				*
				*	ROUTE: /events/view
				*		- View a Events Event	
				* 	
				* 	Params (URL):
				* 		n/a
				*
				**/
				Page.loading.start();


				Loader.get( ':api/events/' + $stateParams.eventid , function( events ){

					Page.loading.end();

					if( events.data.result ){

						$scope.event 			= events.data.data;
						$scope.title 			= 'View Event';
						$scope.icon 			= 'eye';

					}else{

						Page.error(404);

					}

				});

				










			}else
			if( Page.is( /^\/admin\/events(\/[0-9]+)?$/ ) && User.hasPermission([ 'events' ]) ){




				/**
				*
				*	ROUTE: /events/([0-9]+)?
				*		- The Events List
				* 	
				* 	Params (URL):
				* 		- page: 		(INT) Users Listing Pagination
				*
				**/
				(function(){

					$scope.page.data 		= [];

					//On Page Load
					$scope.load 			= function( page ){

						Page.loading.start();

						var limit = $scope.page.showing;

						Loader.get( ':api/events' + ( limit ? '/' + limit : '' ) + ( page ? '/' + page : '' ) , function( events ){

							if( events.data.data.length > 0 || page == 1 ){

								if( $scope.page.current != page ){

									$location.path( '/admin/events' + ( page > 1 ? '/' + page : '' ) );

								}

								for( var i=0; i < events.data.data.length; i++ ){

									events.data.data[i].start = events.data.data[i].start;

									events.data.data[i].end = events.data.data[i].end;

								}

								angular.extend( $scope.page , { current: page }, events.data );

							}else{

								Page.error(404);

							}

							Page.loading.end();

						});
					
					};

					//On Click "Delete"
					$scope.delete 		= function( event ){
						if( User.hasPermission([ 'events.delete' ]) ){

							jQuery.confirm({
							    title: 				'Are you sure?',
							    content: 			'Are you sure you want to delete the event: ' + event.name,
							    confirmButton: 		'Delete Event',
								confirmButtonClass: 'btn-danger',
							    cancelButton: 		'Cancel',
								cancelButtonClass: 	'btn bgaaa coloraaa hoverbg555 hovercolorfff',
							    confirm: 			function(){

							    	$http.delete( ':api/events/' + event.id ).then(function( response ){
							    		if( !response.data.result ){

							    			$scope.success 	= '';
							    			$scope.errors 	= response.data.errors;

							    			$window.scrollTo(0,0);

							    		}else{

							    			$scope.errors 	= [];
							    			$scope.success 	= 'Event: ' + event.name + ' has been deleted.';

							    			$scope.load( 1 );

							    			$window.scrollTo(0,0);

							    		}
							    	});

							    }
							});
						}
					};

					//Load the First Page
					$scope.load( ( $stateParams.page || 1 ) );

				})();




			}

		});
	}]);


})();