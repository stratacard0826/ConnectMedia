(function(){

	'use strict';

	var app = angular.module('System.Controllers');

	//The App Controller
	app.controller('NewsController', [ '$window' , '$stateParams' , '$scope' , '$http' , '$location' , '$timeout' , '$sce' , 'User' , 'Role' , 'Page' , 'Store' , 'Loader' , 'Upload' , function( $window , $stateParams , $scope , $http , $location , $timeout , $sce , User , Role , Page , Store , Loader , Upload ){
		User.ready(function(){

			if( Page.is( /^\/news\/edit\/([0-9]+)$/ ) && User.hasPermission([ 'news' , 'news.edit' ]) ){

				/**
				*
				*	ROUTE: /news/edit/([0-9]+)
				*		- Edit a News Article
				* 	
				* 	Params (URL):
				* 		- userid: 		(INT) The News ID to Edit
				*
				**/
				Page.loading.start();


				Loader.get( ':api/news/' + $stateParams.newsid , function( news ){

					if( news.data.data.id ){

						Store.all(function( stores ){

							Role.all(function( roles ){

								Page.loading.end();

								var article 			= news.data.data;

								for( var i=0; i < article.attachments.length; i++ ){

									article.attachments[i].attachment_id 	= article.attachments[i].id;
									article.attachments[i].name 			= article.attachments[i].filename;
									article.attachments[i].progress 		= 100;
									article.attachments[i].status 			= 'success';

								}

								$scope.action 			= 'edit';
								$scope.running 			= 'Updating';
								$scope.title 			= 'Edit Article';
								$scope.button 			= 'Edit Article';
								$scope.icon 			= 'pencil-square';
								$scope.hasError 		= Page.hasError;
								$scope.errors 			= [];
								$scope.success 			= '';
								$scope.sending 			= false;
								$scope.today 			= new Date();
								$scope.news 			= angular.extend({ 'createevent':( article.event_id ? '1' : '0' ) , 'sendemail':0, 'files':article.attachments }, article);

                                $scope.config           = { 'specifications': article.reminders};
                                $scope.news.reminders   = $scope.config.specifications;

                                
								$scope.roles 			= {
									data: 					roles,
									settings: 				{
										displayProp: 				'name',
										smartButtonMaxItems: 		3,
				    					smartButtonTextConverter: 	function(itemText, originalItem){ return itemText; }
									},
									news: 				{
										onItemSelect: 				function(){ $scope.newsForm.roles.$dirty = $scope.news.roles.length; },
										onItemDeselect: 			function(){ $scope.newsForm.roles.$dirty = $scope.news.roles.length; },
										onSelectAll: 				function(){ $scope.newsForm.roles.$dirty = $scope.news.roles.length; },
										onDeselectAll: 				function(){ $scope.newsForm.roles.$dirty = false;  }
									}
								};
								$scope.stores 			= {
									data: 					stores,
									settings: 				{
										displayProp: 				'name',
										smartButtonMaxItems: 		3,
				    					smartButtonTextConverter: 	function(itemText, originalItem){ return itemText; },
									},
									news: 				{
										onItemSelect: 				function(){ $scope.newsForm.stores.$dirty = $scope.news.stores.length; },
										onItemDeselect: 			function(){ $scope.newsForm.stores.$dirty = $scope.news.stores.length; },
										onSelectAll: 				function(){ $scope.newsForm.stores.$dirty = $scope.news.stores.length; },
										onDeselectAll: 				function(){ $scope.newsForm.stores.$dirty = false; }
									}
								};

								$scope.$watch('file',function(){

									$scope.upload( $scope.files );

								});

								$scope.remove = function( index ){
									$scope.news.files.splice( index , 1 );
								};

								$scope.upload = function( files ){
									if( files && files.length ){

										if( files && files.length ){
											for( var i=0; i < files.length; i++ ){
												if( !files[i].status ){
													(function( file ){

														Upload.upload({

															url: 	':api/news/attach',
															
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

								$scope.dateSelect = function( newsForm , type , wrapper ){
									newsForm[ type ].$dirty = true;
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
									if( form.$valid && !$scope.sending ){

										$scope.sending 			= true;
										$scope.news.start 		= moment( $scope.news.start ).format('YYYY-MM-DD HH:mm:ss');
										$scope.news.end 		= moment( $scope.news.end 	).format('YYYY-MM-DD HH:mm:ss');
										$scope.news.today 		= moment( $scope.today 		).format('YYYY-MM-DD HH:mm:ss');
										$scope.news.sendemail 	= +$scope.news.sendemail;

										$http.post( ':api/news/' + $stateParams.newsid , $scope.news ).then(function( response ){
											if( !response.data.result ){


												$scope.sending 	= false;
												$scope.success 	= '';
												$scope.errors 	= response.data.errors;

												$window.scrollTo(0,0);

											}else{

												form.$setPristine();
												$scope.sending 	= false;
												$scope.errors 	= [];
												$scope.success 	= 'Your Article has been updated!';

												$window.scrollTo(0,0);

											}
										});

									}
								};

							});

						});

					}else{

						//Article not found
						Page.error(404);

					}

				});







			}else
			if( Page.is( /^\/news\/add$/ ) && User.hasPermission([ 'news' , 'news.create' ]) ){

				/**
				*
				*	ROUTE: /news/add
				*		- Add a News Article	
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
						$scope.title 			= 'Add Article';
						$scope.button 			= 'Add Article';
						$scope.icon 			= 'plus-circle';
						$scope.hasError 		= Page.hasError;
						$scope.errors 			= [];
						$scope.success 			= '';
						$scope.sending 			= false;
						$scope.today 			= new Date();
						$scope.news 			= { 'roles':[] , 'stores':[], 'sendemail':1, 'files':[], 'createevent':'1' };

                        $scope.config           = { 'specifications': []};
                        $scope.news.reminders = $scope.config.specifications;

                        
						$scope.roles 			= {
							data: 					roles,
							settings: 				{
								displayProp: 				'name',
								smartButtonMaxItems: 		3,
		    					smartButtonTextConverter: 	function(itemText, originalItem){ return itemText; }
							},
							news: 				{
								onItemSelect: 				function(){ $scope.newsForm.roles.$dirty = $scope.news.roles.length; },
								onItemDeselect: 			function(){ $scope.newsForm.roles.$dirty = $scope.news.roles.length; },
								onSelectAll: 				function(){ $scope.newsForm.roles.$dirty = $scope.news.roles.length; },
								onDeselectAll: 				function(){ $scope.newsForm.roles.$dirty = false;  }
							}
						};
						$scope.stores 			= {
							data: 					stores,
							settings: 				{
								displayProp: 				'name',
								smartButtonMaxItems: 		3,
		    					smartButtonTextConverter: 	function(itemText, originalItem){ return itemText; },
							},
							news: 				{
								onItemSelect: 				function(){ $scope.newsForm.stores.$dirty = $scope.news.stores.length; },
								onItemDeselect: 			function(){ $scope.newsForm.stores.$dirty = $scope.news.stores.length; },
								onSelectAll: 				function(){ $scope.newsForm.stores.$dirty = $scope.news.stores.length; },
								onDeselectAll: 				function(){ $scope.newsForm.stores.$dirty = false; }
							}
						};

						$scope.$watch('file',function(){

							$scope.upload( $scope.files );

						});

						$scope.remove = function( index ){
							$scope.news.files.splice( index , 1 );
						};

						$scope.upload = function( files ){
							if( files && files.length ){

								if( files && files.length ){
									for( var i=0; i < files.length; i++ ){
										if( !files[i].status ){
											(function( file ){

												Upload.upload({

													url: 	':api/news/attach',
													
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

						$scope.dateSelect = function( newsForm , type , wrapper ){
							newsForm[ type ].$dirty = true;
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
							if( form.$valid && !$scope.sending ){

								$scope.news.sendemail 	= +$scope.news.sendemail;
								$scope.news.start 		= moment( $scope.news.start ).format('YYYY-MM-DD HH:mm:ss');
								$scope.news.end 		= moment( $scope.news.end 	).format('YYYY-MM-DD HH:mm:ss');
								$scope.news.today 		= moment( $scope.today 		).format('YYYY-MM-DD HH:mm:ss');
								$scope.sending 			= true;

								$http.put( ':api/news' , $scope.news ).then(function( response ){
									if( !response.data.result ){


										$scope.sending 	= false;
										$scope.success 	= '';
										$scope.errors 	= response.data.errors;

										$window.scrollTo(0,0);

									}else{

										form.$setPristine();
										$scope.sending 	= false;
										$scope.errors 	= [];
										$scope.success 	= 'Your Article has been added to the Company News!';
										$scope.news 	= { 'roles':[], 'stores':[], 'sendemail':1, 'files':[], 'article':'' };

										$window.scrollTo(0,0);

									}
								});

							}
						};

					});

				});

				










			}else
			if( Page.is( /^\/news\/view\/([0-9]+)$/ ) && User.hasPermission([ 'news' , 'news.view' ]) ){


				/**
				*
				*	ROUTE: /news/view
				*		- View a News Article	
				* 	
				* 	Params (URL):
				* 		n/a
				*
				**/
				Page.loading.start();


				Loader.get( ':api/news/' + $stateParams.newsid , function( news ){

					Page.loading.end();

					if( news.data.result ){

						$scope.article 			= news.data.data;
						$scope.title 			= 'View Article';
						$scope.icon 			= 'eye';

					}else{

						Page.error(404);

					}

				});

				










			}else
			if( Page.is( /^\/news(\/[0-9]+)?$/ ) && User.hasPermission([ 'news' ]) ){




				/**
				*
				*	ROUTE: /news/([0-9]+)?
				*		- The News List
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

						Loader.get( ':api/news' + ( limit ? '/' + limit : '' ) + ( page ? '/' + page : '' ) , function( news ){

							if( news.data.data.length > 0 || page == 1 ){

								if( $scope.page.current != page ){

									$location.path( '/news' + ( page > 1 ? '/' + page : '' ) );

								}

								angular.extend( $scope.page , { current: page }, news.data );

							}else{

								Page.error(404);

							}

							Page.loading.end();

						});
					
					};

					//On Click "Delete"
					$scope.delete 		= function( article ){
						if( User.hasPermission([ 'news.delete' ]) ){

							jQuery.confirm({
							    title: 				'Are you sure?',
							    content: 			'Are you sure you want to delete article: ' + article.subject,
							    confirmButton: 		'Delete Article',
								confirmButtonClass: 'btn-danger',
							    cancelButton: 		'Cancel',
								cancelButtonClass: 	'btn bgaaa coloraaa hoverbg555 hovercolorfff',
							    confirm: 			function(){

							    	$http.delete( ':api/news/' + article.id ).then(function( response ){
							    		if( !response.data.result ){

							    			$scope.success 	= '';
							    			$scope.errors 	= response.data.errors;

							    			$window.scrollTo(0,0);

							    		}else{

							    			$scope.errors 	= [];
							    			$scope.success 	= 'Article: ' + article.subject + ' has been deleted.';

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