(function(){

	'use strict';

	var app = angular.module('System.Controllers');

	//The App Controller
	app.controller('BarController', [ '$window' , '$stateParams' , '$scope' , '$http' , '$location' , 'Config' , 'User' , 'Page' , 'Loader' , 'Upload' , function( $window , $stateParams , $scope , $http , $location , Config , User , Page , Loader , Upload){
		User.ready(function(){

			if( Page.is( /^\/bar\/view\/([0-9]+)$/ ) && User.hasPermission([ 'bar' , 'bar.view' ]) ){



				/**
				*
				*	ROUTE: /bar/view/([0-9]+)
				*		- View the Recipe	
				* 	
				* 	Params (URL):
				* 		- userid: 		(INT) The Menu ID to View
				*
				**/

				Page.loading.start();

				Loader.get( ':api/bar/' + $stateParams.recipeid , function( recipe ){
					if( recipe.data.data.id ){



						Page.loading.end();
						$scope.bar 					= recipe.data.data;
						$scope.title 				= 'View Recipe: ' + $scope.bar.name ;
						$scope.icon 				= 'eye';
						$scope.sending 				= false;
						$scope.faqs 				= {};
						$scope.categories 			= [
							{ icon: 'briefcase' 	, text: 'General Questions' },
							{ icon: 'martini' 		, text: 'Tips for Mixing' },
							{ icon: 'info-circle'  	, text: 'Knowledge for Customers' },
							{ icon: 'map-marker' 	, text: 'Promotional Information' },
							{ icon: 'ambulance' 	, text: 'Health Risks' },
							{ icon: 'plus' 			, text: 'Other Questions' }
						];

						//Prepare the FAQs
						for( var i=0; i < $scope.bar.faq.length; i++ ){

							var item 						= $scope.bar.faq[i] ;

							if( !$scope.faqs[ item.category ] ){

								switch( item.category ){

									case 'General Questions':
										$scope.faqs[ item.category ] = {
											icon: 	'briefcase',
											list: 	[]
										};
										break;

									case 'Tips for Mixing':
										$scope.faqs[ item.category ] = {
											icon: 	'martini',
											list: 	[]
										};
										break;

									case 'Knowledge for Customers':
										$scope.faqs[ item.category ] = {
											icon: 	'info-circle',
											list: 	[]
										};
										break;

									case 'Promotional Information':
										$scope.faqs[ item.category ] = {
											icon: 	'map-marker',
											list: 	[]
										};
										break;

									case 'Health Risks':
										$scope.faqs[ item.category ] = {
											icon: 	'ambulance',
											list: 	[]
										};
										break;

									case 'Other Questions':
										$scope.faqs[ item.category ] = {
											icon: 	'plus',
											list: 	[]
										};

								}

							}

							$scope.faqs[ item.category ].list.push( $scope.bar.faq[i] );

						}


						$scope.download = function( media ){

							window.location.href = Config.api + '/download/' + media.slug ;

						}

						$scope.view = function( media ){

							jQuery.dialog({
							    title: 				media.name,
							    content: 			( media.mime.type == 'image' ? '<img src="' + media.image + '" />' : '<video controls preload="auto" width="100%" height="100%"> <source src="' + media.file + '" type="' + media.mime.type + '/' + media.mime.subtype + '" /> </video>' )
							});

						}

						$scope.sendFeedback 		= function( form ){
							if( form.$valid ){

								$scope.sending = true;

								$http.post( ':api/bar/feedback/' + $scope.bar.id , $scope.bar.feedback ).then(function( response ){
									if( !response.data.result ){

								    	$scope.sending 				 = false;
										$window.scrollTo(0,0);

										jQuery.alert({
										    title: 				'An Error Occured',
										    content: 			'We were unable to send your Feedback, please try again later',
										    confirmButton: 		'Close',
											confirmButtonClass: 'btn-danger'
										});

									}else{

								    	$scope.bar.feedback.message = '';
								    	$scope.sending 				 = false;
								    	form.$setPristine();
										$window.scrollTo(0,0);

										jQuery.alert({
										    title: 				'Feedback Sent!',
										    content: 			'Your Feedback has been sent!',
										    confirmButton: 		'Close',
											confirmButtonClass: 'btn-success'
										});

									}
								});


							}
						}

					}else{

						Page.error(404);

					}
				});







			}else
			if( Page.is( /^\/bar\/edit\/([0-9]+)$/ ) && User.hasPermission([ 'bar' , 'bar.edit' ]) ){




				/**
				*
				*	ROUTE: /bar/edit/([0-9]+)
				*		- Edit the Menu	
				* 	
				* 	Params (URL):
				* 		- userid: 		(INT) The Menu ID to Edit
				*
				**/

				Page.loading.start();

				Loader.get( ':api/bar/' + $stateParams.recipeid , function( recipe ){
					if( recipe.data.data.id ){
						
						Page.loading.end();

						$scope.bar 					= recipe.data.data;
						$scope.action 				= 'edit';
						$scope.title 				= 'Edit Recipe: ' + $scope.bar.id ;
						$scope.button 				= 'Edit Recipe';
						$scope.icon 				= 'pencil-square';
						$scope.hasError 			= Page.hasError;
						$scope.errors 				= [];
						$scope.today 				= new Date();
						$scope.bar.files 			= $scope.bar.media;
						$scope.bar.gluten_free 		= String( $scope.bar.gluten_free );
						$scope.bar.status_date 		= moment( $scope.bar.status_date * 1000 ).format('YYYY-MM-DD');

						$scope.$watch('file',function(){
							$scope.upload( $scope.files );
						});

						$scope.remove = function( file , index ){
							$scope.bar.files.splice( index , 1 );				
							if( file.checked && $scope.bar.files.length > 0 ){
								$scope.bar.files[0].checked = true;
								$scope.bar.primary_media 	= $scope.bar.files[0].attachment_id;
							}else{
								$scope.bar.primary_media 	= false;
							}
						};

						$scope.select = function( files , index ){
							angular.forEach( files , function( file , i ){
								file.checked = ( i == index );
							});
						}

						$scope.upload = function( files ){
							if( files && files.length ){

								if( files && files.length ){
									for( var i=0; i < files.length; i++ ){
										if( !files[i].status ){
											(function( file ){

												//Set the First Element to Checked
												if( !$scope.bar.primary_media && i == 0 ){

													file.checked = true;

												}

												Upload.upload({

													url: 	':api/bar/attach',
													
													data: 	{ 'file': file },
													
													method: 'POST',
											
												}).then(function(resp){
													if( resp.data.result ){

														file.status 		= 'success';
														file.attachment_id 	= resp.data.attachment_id;
														file.custom_name 	= file.name;

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

						$scope.hasArrayError = function( cat , index , key ){
							var name = cat + '[' + index + ']' + ( key ? '[' + key + ']' : '' ) ;
							if( $scope.barForm[ name ] ){
								return $scope.barForm[ name ].$invalid && $scope.barForm[ name ].$dirty
							}
						}

						$scope.hasArraySuccess = function( cat , index , key ){
							var name = cat + '[' + index + ']' + ( key ? '[' + key + ']' : '' );
							if( $scope.barForm[ name ] ){
								return !$scope.barForm[ name ].$invalid && $scope.barForm[ name ].$dirty
							}
						}			
						
						$scope.dateSelect = function( barForm , type , wrapper ){
							barForm[ type ].$dirty = true;
							jQuery('[uib-dropdown-toggle]:nth(' + ( wrapper - 1 ) + ')').toggleClass('open');
						}

						//On Form Submit
						$scope.submit 	= function( form ){
							if( form.$valid ){

								$http.post( ':api/bar/' + $scope.bar.id , $scope.bar ).then(function( response ){
									if( !response.data.result ){

										$scope.success 	= '';
										$scope.errors 	= response.data.errors;

										$window.scrollTo(0,0);

									}else{

										form.$setPristine();
										$scope.errors 	= [];
										$scope.success 	= $scope.bar.name  + ' has been updated.';

										$window.scrollTo(0,0);

									}
								});


							}
						};



					}else{

						Page.error(404);

					}
				});






			}else
			if( Page.is( /^\/bar\/add$/ ) &&  User.hasPermission([ 'bar' , 'bar.create' ])  ){


				/**
				*
				*	ROUTE: /bar/add
				*		- Add a New Menu	
				* 	
				* 	Params (URL):
				* 		n/a
				*
				**/
				
				Page.loading.end();

				$scope.action 			= 'insert';
				$scope.title 			= 'Add Recipe';
				$scope.button 			= 'Add Recipe';
				$scope.icon 			= 'plus-circle';
				$scope.hasError 		= Page.hasError;
				$scope.errors 			= [];
				$scope.today 			= new Date();
				$scope.bar 				= { serveware:[], sides:[], redflag:[], ingredients:[], directions:[], files:[], faq:[] };
	

				$scope.$watch('file',function(){
					$scope.upload( $scope.files );
				});

				$scope.remove = function( file , index ){
					$scope.bar.files.splice( index , 1 );				
					if( file.checked && $scope.bar.files.length > 0 ){
						$scope.bar.files[0].checked = true;
						$scope.bar.primary_media 	= $scope.bar.files[0].attachment_id;
					}else{
						$scope.bar.primary_media 	= false;
					}
				};

				$scope.select = function( files , index ){
					angular.forEach( files , function( file , i ){
						file.checked = ( i == index );
					});
				}

				$scope.upload = function( files ){
					if( files && files.length ){

						if( files && files.length ){
							for( var i=0; i < files.length; i++ ){
								if( !files[i].status ){
									(function( file ){

										//Set the First Element to Checked
										if( !$scope.bar.primary_media && i == 0 ){

											file.checked = true;

										}

										Upload.upload({

											url: 	':api/bar/attach',
											
											data: 	{ 'file': file },
											
											method: 'POST',
									
										}).then(function(resp){
											if( resp.data.result ){

												file.status 		= 'success';
												file.attachment_id 	= resp.data.attachment_id;
												file.custom_name 	= file.name;

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

				$scope.hasArrayError = function( cat , index , key ){
					var name = cat + '[' + index + ']' + ( key ? '[' + key + ']' : '' ) ;
					if( $scope.barForm[ name ] ){
						return $scope.barForm[ name ].$invalid && $scope.barForm[ name ].$dirty
					}
				}

				$scope.hasArraySuccess = function( cat , index , key ){
					var name = cat + '[' + index + ']' + ( key ? '[' + key + ']' : '' );
					if( $scope.barForm[ name ] ){
						return !$scope.barForm[ name ].$invalid && $scope.barForm[ name ].$dirty
					}
				}			
				
				$scope.dateSelect = function( barForm , type , wrapper ){
					barForm[ type ].$dirty = true;
					jQuery('[uib-dropdown-toggle]:nth(' + ( wrapper - 1 ) + ')').toggleClass('open');
				}

				//On Form Submit
				$scope.submit 	= function( form ){

					$scope.errors = [];

					if( $scope.bar.ingredients.length == 0 ){

						$scope.errors.push('Ingredients are Required');

					}

					if( form.$valid && $scope.errors.length == 0 ){

						$scope.bar.status_date	= moment( $scope.bar.status_date ).format('YYYY-MM-DD HH:mm:ss');

						$http.put( ':api/bar' , $scope.bar ).then(function( response ){
							if( !response.data.result ){

								$scope.success 	= '';
								$scope.errors 	= response.data.errors;

								$window.scrollTo(0,0);

							}else{

								form.$setPristine();

								$scope.errors 	= [];
								$scope.success 	= $scope.bar.name + ' has been added to the bar list.';
								$scope.bar 		= { type:{}, gluten:{}, sides:[], redflag:[], ingredients:[], directions:[], files:[], faq:[] };

								$window.scrollTo(0,0);

							}
						});

					}
				};

				//Make sure the Page is Built
				$scope.$apply();









			}else
			if( Page.is( /^\/bar(\/[0-9]+)?$/ ) && User.hasPermission([ 'bar' ])  ){

				/**
				*
				*	ROUTE: /bar/([0-9]+)?
				*		- The Menu List
				* 	
				* 	Params (URL):
				* 		- page: 		(INT) Menus Listing Pagination
				*
				**/
				(function(){

					$scope.page.data 		= [];
					$scope.query 			= '';
					$scope.list 			= { query: '', loading: false };
					
					//On Page Load
					$scope.load 			= function( page , searching ){

						var limit = $scope.page.showing;

						var query = $scope.list.query;

						if( !searching ){

							Page.loading.start();

						}else{

							$scope.list.loading = true;

						}

						Loader.get( ':api/bar' + ( limit ? '/' + limit : '' ) + ( page ? '/' + page : '' ) + ( query ? '?q=' + encodeURIComponent( query ) : '' ) , function( recipes ){
							if( recipes ){

								if( recipes.data.data.length > 0 || page == 1 ){

									if( $scope.page.current != page ){

										$location.path( '/bar' + ( page > 1 ? '/' + page : '' ) );

									}

									$.extend( $scope.page , { current: page }, recipes.data );

								}else
								if( !searching ){

									Page.error(404);

								}

								Page.loading.end();

								$scope.list.loading = false;

							}
						});

					};

					//On "Click Delete"
					$scope.delete 		= function( bar ){
						if( User.hasPermission([ 'bar.delete' ]) ){

							jQuery.confirm({
							    title: 				'Are you sure?',
							    content: 			'Are you sure you want to delete the bar item: ' + bar.name,
							    confirmButton: 		'Delete Menu',
								confirmButtonClass: 'btn-danger',
							    cancelButton: 		'Cancel',
								cancelButtonClass: 	'btn bgaaa coloraaa hoverbg555 hovercolorfff',
							    confirm: 			function(){

							    	$http.delete( ':api/bar/' + bar.id ).then(function( response ){
							    		if( !response.data.result ){

							    			$scope.success 	= '';
							    			$scope.errors 	= response.data.errors;

							    			$window.scrollTo(0,0);

							    		}else{

							    			$scope.errors 	= [];
							    			$scope.success 	= bar.name + ' has been deleted.';

							    			$scope.load( 1 );

							    			$window.scrollTo(0,0);

							    		}
							    	});

							    }
							});
						}

					}

					$scope.search = function( form ){

						$location.search('');

						Loader.remove( /:api\/bar*/ );
						
						$scope.load( 1 , true );

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