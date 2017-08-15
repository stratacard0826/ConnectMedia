(function(){

	'use strict';

	var app = angular.module('System.Controllers');

	//The App Controller
	app.controller('MenuController', [ '$window' , '$stateParams' , '$scope' , '$http' , '$location' , 'Config' , 'User' , 'Page' , 'Loader' , 'Upload' , function( $window , $stateParams , $scope , $http , $location , Config , User , Page , Loader , Upload){
		User.ready(function(){

			if( Page.is( /^\/menu\/view\/([0-9]+)$/ ) && User.hasPermission([ 'menu' , 'menu.view' ]) ){




				/**
				*
				*	ROUTE: /menu/view/([0-9]+)
				*		- View the Recipe	
				* 	
				* 	Params (URL):
				* 		- userid: 		(INT) The Menu ID to View
				*
				**/

				Page.loading.start();

				Loader.get( ':api/menu/' + $stateParams.recipeid , function( recipe ){
					if( recipe.data.data.id ){

						Page.loading.end();
						$scope.menu 				= recipe.data.data;
						$scope.title 				= 'View Recipe: ' + $scope.menu.name ;
						$scope.icon 				= 'eye';
						$scope.sending 				= false;
						$scope.faqs 				= {};
						$scope.categories 			= [
							{ icon: 'briefcase' 	, text: 'General Questions' },
							{ icon: 'users' 		, text: 'Tips for Cooking' },
							{ icon: 'info-circle'  	, text: 'Knowledge for Customers' },
							{ icon: 'map-marker' 	, text: 'Promotional Information' },
							{ icon: 'ambulance' 	, text: 'Health Risks' },
							{ icon: 'plus' 			, text: 'Other Questions' }
						];

						//Prepare the FAQs
						for( var i=0; i < $scope.menu.faq.length; i++ ){

							var item 						= $scope.menu.faq[i] ;

							if( !$scope.faqs[ item.category ] ){

								switch( item.category ){

									case 'General Questions':
										$scope.faqs[ item.category ] = {
											icon: 	'briefcase',
											list: 	[]
										};
										break;

									case 'Tips for Cooking':
										$scope.faqs[ item.category ] = {
											icon: 	'users',
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

							$scope.faqs[ item.category ].list.push( $scope.menu.faq[i] );

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

								$http.post( ':api/menu/feedback/' + $scope.menu.id , $scope.menu.feedback ).then(function( response ){
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

								    	$scope.menu.feedback.message = '';
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
			if( Page.is( /^\/menu\/edit\/([0-9]+)$/ ) && User.hasPermission([ 'menu' , 'menu.edit' ]) ){




				/**
				*
				*	ROUTE: /menu/edit/([0-9]+)
				*		- Edit the Menu	
				* 	
				* 	Params (URL):
				* 		- userid: 		(INT) The Menu ID to Edit
				*
				**/

				Page.loading.start();

				Loader.get( ':api/menu/' + $stateParams.recipeid , function( recipe ){
					if( recipe.data.data.id ){
						
						Page.loading.end();

						$scope.menu 				= recipe.data.data;
						$scope.sending 				= false;
						$scope.running 				= 'Updating';
						$scope.action 				= 'edit';
						$scope.title 				= 'Edit Recipe: ' + $scope.menu.id ;
						$scope.button 				= 'Edit Recipe';
						$scope.icon 				= 'pencil-square';
						$scope.hasError 			= Page.hasError;
						$scope.errors 				= [];
						$scope.today 				= new Date();
						$scope.menu.files 			= $scope.menu.media;
						$scope.menu.gluten_free 	= String( $scope.menu.gluten_free );
						$scope.menu.status_date 	= moment( $scope.menu.status_date * 1000 ).format('YYYY-MM-DD');

						$scope.$watch('file',function(){
							$scope.upload( $scope.files );
						});

						$scope.remove = function( file , index ){
							$scope.menu.files.splice( index , 1 );						
							if( file.checked && $scope.menu.files.length > 0 ){
								$scope.menu.files[0].checked = true;
								$scope.menu.primary_media 	= $scope.menu.files[0].attachment_id;
							}else{
								$scope.menu.primary_media 	= false;
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
												if( !$scope.menu.primary_media && i == 0 ){

													file.checked = true;

												}

												Upload.upload({

													url: 	':api/menu/attach',
													
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
							if( $scope.menuForm[ name ] ){
								return $scope.menuForm[ name ].$invalid && $scope.menuForm[ name ].$dirty
							}
						}

						$scope.hasArraySuccess = function( cat , index , key ){
							var name = cat + '[' + index + ']' + ( key ? '[' + key + ']' : '' );
							if( $scope.menuForm[ name ] ){
								return !$scope.menuForm[ name ].$invalid && $scope.menuForm[ name ].$dirty
							}
						}			
						
						$scope.dateSelect = function( menuForm , type , wrapper ){
							menuForm[ type ].$dirty = true;
							jQuery('[uib-dropdown-toggle]:nth(' + ( wrapper - 1 ) + ')').toggleClass('open');
						}

						//On Form Submit
						$scope.submit 	= function( form ){
							if( form.$valid ){

								$scope.sending = true;

								$http.post( ':api/menu/' + $scope.menu.id , $scope.menu ).then(function( response ){
									if( !response.data.result ){

										$scope.sending 	= false;
										$scope.success 	= '';
										$scope.errors 	= response.data.errors;

										$window.scrollTo(0,0);

									}else{

										form.$setPristine();

										$scope.sending 	= false;
										$scope.errors 	= [];
										$scope.success 	= $scope.menu.name  + ' has been updated.';

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
			if( Page.is( /^\/menu\/add$/ ) &&  User.hasPermission([ 'menu' , 'menu.create' ])  ){


				/**
				*
				*	ROUTE: /menu/add
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
				$scope.open 			= false;
				$scope.today 			= new Date();
				$scope.menu 			= { serveware:[], sides:[], redflag:[], ingredients:[], directions:[], files:[], faq:[] };
	

				$scope.$watch('file',function(){
					$scope.upload( $scope.files );
				});

				$scope.remove = function( file , index ){
					$scope.menu.files.splice( index , 1 );						
					if( file.checked && $scope.menu.files.length > 0 ){
						$scope.menu.files[0].checked = true;
						$scope.menu.primary_media 	= $scope.menu.files[0].attachment_id;
					}else{
						$scope.menu.primary_media 	= false;
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
										if( !$scope.menu.primary_media && i == 0 ){

											file.checked = true;

										}

										Upload.upload({

											url: 	':api/menu/attach',
											
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
					if( $scope.menuForm[ name ] ){
						return $scope.menuForm[ name ].$invalid && $scope.menuForm[ name ].$dirty
					}
				}

				$scope.hasArraySuccess = function( cat , index , key ){
					var name = cat + '[' + index + ']' + ( key ? '[' + key + ']' : '' );
					if( $scope.menuForm[ name ] ){
						return !$scope.menuForm[ name ].$invalid && $scope.menuForm[ name ].$dirty
					}
				}			
				
				$scope.dateSelect = function( menuForm , type , wrapper ){
					menuForm[ type ].$dirty = true;
					jQuery('[uib-dropdown-toggle]:nth(' + ( wrapper - 1 ) + ')').toggleClass('open');
				}

				//On Form Submit
				$scope.submit 	= function( form ){

					$scope.errors = [];

					if( $scope.menu.ingredients.length == 0 ){

						$scope.errors.push('Ingredients are Required');

					}

					if( form.$valid && $scope.errors.length == 0 ){

						$scope.menu.status_date	= moment( $scope.menu.status_date ).format('YYYY-MM-DD HH:mm:ss');

						$http.put( ':api/menu' , $scope.menu ).then(function( response ){
							if( !response.data.result ){

								$scope.success 	= '';
								$scope.errors 	= response.data.errors;

								$window.scrollTo(0,0);

							}else{

								form.$setPristine();

								$scope.errors 	= [];
								$scope.success 	= $scope.menu.name + ' has been added to the menu list.';
								$scope.menu 	= { type:{}, gluten:{}, sides:[], redflag:[], ingredients:[], directions:[], files:[], faq:[] };

								$window.scrollTo(0,0);

							}
						});

					}
				};

				//Make sure the Page is Built
				$scope.$apply();









			}else
			if( Page.is( /^\/menu(\/[0-9]+)?$/ ) && User.hasPermission([ 'menu' ])  ){

				/**
				*
				*	ROUTE: /menu/([0-9]+)?
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

						Loader.get( ':api/menu' + ( limit ? '/' + limit : '' ) + ( page ? '/' + page : '' ) + ( query ? '?q=' + encodeURIComponent( query ) : '' ) , function( recipes ){
							if( recipes ){

								if( recipes.data.data.length > 0 || page == 1 ){

									if( $scope.page.current != page ){

										$location.path( '/menu' + ( page > 1 ? '/' + page : '' ) );

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
					$scope.delete 		= function( menu ){
						if( User.hasPermission([ 'menu.delete' ]) ){

							jQuery.confirm({
							    title: 				'Are you sure?',
							    content: 			'Are you sure you want to delete the menu item: ' + menu.name,
							    confirmButton: 		'Delete Menu',
								confirmButtonClass: 'btn-danger',
							    cancelButton: 		'Cancel',
								cancelButtonClass: 	'btn bgaaa coloraaa hoverbg555 hovercolorfff',
							    confirm: 			function(){

							    	$http.delete( ':api/menu/' + menu.id ).then(function( response ){
							    		if( !response.data.result ){

							    			$scope.success 	= '';
							    			$scope.errors 	= response.data.errors;

							    			$window.scrollTo(0,0);

							    		}else{

							    			$scope.errors 	= [];
							    			$scope.success 	= menu.name + ' has been deleted.';

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

						Loader.remove( /:api\/menu*/ );
						
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