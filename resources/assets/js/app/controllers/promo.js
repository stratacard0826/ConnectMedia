(function(){

	'use strict';

	var app = angular.module('System.Controllers');

	//The App Controller
	app.controller('PromoController', [ '$window' , '$stateParams' , '$scope' , '$http' , '$location' , 'Config' , 'User' , 'Page' , 'Loader' , 'Upload' , function( $window , $stateParams , $scope , $http , $location , Config , User , Page , Loader , Upload){
		User.ready(function(){

			if( Page.is( /^\/promos\/view\/([0-9]+)$/ ) && User.hasPermission([ 'promos' , 'promos.view' ]) ){




				/**
				*
				*	ROUTE: /promos/view/([0-9]+)
				*		- View the Promotion	
				* 	
				* 	Params (URL):
				* 		- userid: 		(INT) The Promos ID to View
				*
				**/

				Page.loading.start();

				Loader.get( ':api/promos/' + $stateParams.promotionid , function( promo ){
					if( promo.data.data.id ){

						Page.loading.end();

						$scope.promo 				= promo.data.data;
						$scope.title 				= 'View Promotion: ' + $scope.promo.name ;
						$scope.icon 				= 'eye';
						$scope.sending 				= false;
						$scope.faqs 				= {};
						$scope.categories 			= [
							{ icon: 'briefcase' 	, text: 'General Questions' },
							{ icon: 'users' 		, text: 'Tips for Marketing' },
							{ icon: 'info-circle'  	, text: 'Customer Promotions' },
							{ icon: 'paint-brush' 	, text: 'Branding' },
							{ icon: 'plus' 			, text: 'Other Questions' }
						];

						//Prepare the FAQs
						for( var i=0; i < $scope.promo.faq.length; i++ ){

							var item 						= $scope.promo.faq[i] ;

							if( !$scope.faqs[ item.category ] ){

								switch( item.category ){

									case 'General Questions':
										$scope.faqs[ item.category ] = {
											icon: 	'briefcase',
											list: 	[]
										};
										break;

									case 'Tips for Marketing':
										$scope.faqs[ item.category ] = {
											icon: 	'users',
											list: 	[]
										};
										break;

									case 'Customer Promotions':
										$scope.faqs[ item.category ] = {
											icon: 	'info-circle',
											list: 	[]
										};
										break;

									case 'Branding':
										$scope.faqs[ item.category ] = {
											icon: 	'map-marker',
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

							$scope.faqs[ item.category ].list.push( $scope.promo.faq[i] );

						}


						$scope.download = function( file ){

							window.location.href = Config.api + '/download/' + file.slug ;

						}

						$scope.view = function( file ){

							if( file.mime.type == 'video' ){							

								jQuery.dialog({
								    title: 				file.name,
								    content: 			'<video controls preload="auto" width="100%" height="100%"> <source src="' + media.file + '" type="' + media.mime.type + '/' + media.mime.subtype + '" /> </video>'
								});

							}else{

								jQuery.dialog({
									title: 				file.name,
									content: 			'<div class="clearfix">' + '<img src="' + file.image + '" />' + '<a href="' + file.image + '" target="_blank" class="btn btn-primary fright">Open in New Window <small>(Full Size)</small></a>' + '</div>'  
								});

							}

						}

						$scope.sendFeedback 		= function( form ){
							if( form.$valid ){

								$scope.sending = true;

								$http.post( ':api/promos/feedback/' + $scope.promo.id , $scope.promo.feedback ).then(function( response ){
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

								    	$scope.promo.feedback.message = '';
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
			if( Page.is( /^\/promos\/edit\/([0-9]+)$/ ) && User.hasPermission([ 'promos' , 'promos.edit' ]) ){




				/**
				*
				*	ROUTE: /promos/edit/([0-9]+)
				*		- Edit the Promos	
				* 	
				* 	Params (URL):
				* 		- userid: 		(INT) The Promos ID to Edit
				*
				**/

				Page.loading.start();

				Loader.get( ':api/promos/' + $stateParams.promotionid , function( promo ){
					if( promo.data.data.id ){
						
						Page.loading.end();

						$scope.sending 				= false;
						$scope.promo 				= promo.data.data;
						$scope.running 				= 'Updating';
						$scope.action 				= 'edit';
						$scope.title 				= 'Edit Promotion: ' + $scope.promo.id ;
						$scope.button 				= 'Edit Promotion';
						$scope.icon 				= 'pencil-square';
						$scope.hasError 			= Page.hasError;
						$scope.errors 				= [];
						$scope.today 				= new Date();
						$scope.promo.start 			= moment( $scope.promo.start_time * 1000 ).format('YYYY-MM-DD');
						$scope.promo.end 			= moment( $scope.promo.end_time * 1000 ).format('YYYY-MM-DD');

						$scope.$watch('file',function(){
							$scope.upload( $scope.files );
						});

						$scope.remove = function( file , index ){
							$scope.promo.files.splice( index , 1 );						
							if( file.checked && $scope.promo.files.length > 0 ){
								$scope.promo.files[0].checked = true;
								$scope.promo.attachment_id 	= $scope.promo.files[0].attachment_id;
							}else{
								$scope.promo.attachment_id 	= null;
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
												if( !$scope.promo.primary_media && i == 0 ){

													file.checked = true;

												}

												Upload.upload({

													url: 	':api/promos/attach',
													
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
							if( $scope.promoForm[ name ] ){
								return $scope.promoForm[ name ].$invalid && $scope.promoForm[ name ].$dirty
							}
						}

						$scope.hasArraySuccess = function( cat , index , key ){
							var name = cat + '[' + index + ']' + ( key ? '[' + key + ']' : '' );
							if( $scope.promoForm[ name ] ){
								return !$scope.promoForm[ name ].$invalid && $scope.promoForm[ name ].$dirty
							}
						}			
						
						$scope.dateSelect = function( promoForm , type , wrapper ){
							promoForm[ type ].$dirty = true;
							jQuery('[uib-dropdown-toggle]:nth(' + ( wrapper - 1 ) + ')').toggleClass('open');
						}

						//On Form Submit
						$scope.submit 	= function( form ){
							if( form.$valid && !$scope.sending ){

								$scope.sending 		= true;
								$scope.promo.start	= moment( $scope.promo.start ).format('YYYY-MM-DD');
								$scope.promo.end	= moment( $scope.promo.end ).format('YYYY-MM-DD');

								$http.post( ':api/promos/' + $scope.promo.id , $scope.promo ).then(function( response ){
									if( !response.data.result ){

										$scope.sending 	= false;
										$scope.success 	= '';
										$scope.errors 	= response.data.errors;

										$window.scrollTo(0,0);

									}else{

										form.$setPristine();

										$scope.sending 	= false;
										$scope.errors 	= [];
										$scope.success 	= $scope.promo.name  + ' has been updated.';

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
			if( Page.is( /^\/promos\/add$/ ) &&  User.hasPermission([ 'promos' , 'promos.create' ])  ){


				/**
				*
				*	ROUTE: /promos/add
				*		- Add a New Promos	
				* 	
				* 	Params (URL):
				* 		n/a
				*
				**/
				
				Page.loading.end();

				$scope.sending 			= false;
				$scope.running 			= 'Creating';
				$scope.action 			= 'insert';
				$scope.title 			= 'Add Promotion';
				$scope.button 			= 'Add Promotion';
				$scope.icon 			= 'plus-circle';
				$scope.files 			= [];
				$scope.hasError 		= Page.hasError;
				$scope.errors 			= [];
				$scope.today 			= new Date();
				$scope.promo 			= { faq:[] };
	

				$scope.$watch('file',function(){
					$scope.upload( $scope.files );
				});

				$scope.remove = function( file , index ){
					$scope.promo.files.splice( index , 1 );						
					if( file.checked && $scope.promo.files.length > 0 ){
						$scope.promo.files[0].checked = true;
						$scope.promo.attachment_id 	= $scope.promo.files[0].attachment_id;
					}else{
						$scope.promo.attachment_id 	= null;
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
										if( !$scope.promo.primary_media && i == 0 ){

											file.checked = true;

										}

										Upload.upload({

											url: 	':api/promos/attach',
											
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
					if( $scope.promoForm[ name ] ){
						return $scope.promoForm[ name ].$invalid && $scope.promoForm[ name ].$dirty
					}
				}

				$scope.hasArraySuccess = function( cat , index , key ){
					var name = cat + '[' + index + ']' + ( key ? '[' + key + ']' : '' );
					if( $scope.promoForm[ name ] ){
						return !$scope.promoForm[ name ].$invalid && $scope.promoForm[ name ].$dirty
					}
				}			
				
				$scope.dateSelect = function( promoForm , type , wrapper ){
					promoForm[ type ].$dirty = true;
					jQuery('[uib-dropdown-toggle]:nth(' + ( wrapper - 1 ) + ')').toggleClass('open');
				}

				//On Form Submit
				$scope.submit 	= function( form ){
					if( form.$valid && !$scope.sending ){

						$scope.sending 		= true;
						$scope.promo.start	= moment( $scope.promo.start ).format('YYYY-MM-DD HH:mm:ss');
						$scope.promo.end	= moment( $scope.promo.end ).format('YYYY-MM-DD HH:mm:ss');

						$http.put( ':api/promos' , $scope.promo ).then(function( response ){
							if( !response.data.result ){

								$scope.sending 	= false;
								$scope.success 	= '';
								$scope.errors 	= response.data.errors;

								$window.scrollTo(0,0);

							}else{

								form.$setPristine();

								$scope.sending 	= false;
								$scope.errors 	= [];
								$scope.success 	= $scope.promo.name + ' has been added to the promo list.';
								$scope.promo 	= { files:[] , faq:[] };

								$window.scrollTo(0,0);

							}
						});

					}
				};

				//Make sure the Page is Built
				$scope.$apply();









			}else
			if( Page.is( /^\/promos(\/[0-9]+)?$/ ) && User.hasPermission([ 'promos' ])  ){

				/**
				*
				*	ROUTE: /promos/([0-9]+)?
				*		- The Promos List
				* 	
				* 	Params (URL):
				* 		- page: 		(INT) Promoss Listing Pagination
				*
				**/
				(function(){

					$scope.page.data 		= [];
					$scope.query 			= '';
					$scope.list 			= { query: '', loading: false };
					
					//On Page Load
					$scope.load 			= function( page , searching ){

						if( !searching ){

							Page.loading.start();

						}else{

							$scope.list.loading = true;

						}

						var limit 		= $scope.page.showing;

						var queries 	= [];

						if( $scope.list.query ) 	queries.push( 'q=' + encodeURIComponent( $scope.list.query ) );
						if( $scope.list.sort ) 		queries.push( 'sort=' + encodeURIComponent( $scope.list.sort ) );
						if( $scope.list.document) 	queries.push( 'document=' + encodeURIComponent( $scope.list.document ) );

						Loader.get( ':api/promos' + ( limit ? '/' + limit : '' ) + ( page ? '/' + page : '' ) + ( queries.length > 0 ? '?' + queries.join('&') : '' ) , function( promotions ){
							if( promotions ){

								if( promotions.data.data.length > 0 || page == 1 ){

									if( $scope.page.current != page ){

										$location.path( '/promos' + ( page > 1 ? '/' + page : '' ) );

									}

									$.extend( $scope.page , { current: page }, promotions.data );

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
					$scope.delete 		= function( promos ){
						if( User.hasPermission([ 'promos.delete' ]) ){

							jQuery.confirm({
							    title: 				'Are you sure?',
							    content: 			'Are you sure you want to delete the marketing promotion: ' + promos.name,
							    confirmButton: 		'Delete Promotion',
								confirmButtonClass: 'btn-danger',
							    cancelButton: 		'Cancel',
								cancelButtonClass: 	'btn bgaaa coloraaa hoverbg555 hovercolorfff',
							    confirm: 			function(){

							    	$http.delete( ':api/promos/' + promos.id ).then(function( response ){
							    		if( !response.data.result ){

							    			$scope.success 	= '';
							    			$scope.errors 	= response.data.errors;

							    			$window.scrollTo(0,0);

							    		}else{

							    			$scope.errors 	= [];
							    			$scope.success 	= promos.name + ' has been deleted.';

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

						Loader.remove( /:api\/promos*/ );

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