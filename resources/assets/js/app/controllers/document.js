(function(){

	'use strict';

	var app = angular.module('System.Controllers');

	//The App Controller
	app.controller('DocumentController', [ '$window' , '$stateParams' , '$scope' , '$http' , '$location' , 'Config' , 'User' , 'Page' , 'Loader' , 'Upload' , function( $window , $stateParams , $scope , $http , $location , Config , User , Page , Loader , Upload){
		User.ready(function(){

			if( Page.is( /^\/documents\/manage$/ ) &&  User.hasPermission([ 'documents' , 'documents.manage' ])  ){


				/**
				*
				*	ROUTE: /documents/manage
				*		- Manage the Document Files
				* 	
				* 	Params (URL):
				* 		n/a
				*
				**/

				Page.loading.start();


				Loader.get( ':api/documents'  , function( files ){
				
					Page.loading.end();


					$scope.sending 			= false;
					$scope.running 			= 'Updating';
					$scope.action 			= 'insert';
					$scope.title 			= 'Manage Documents';
					$scope.button 			= 'Update Documents';
					$scope.icon 			= 'pencil-square';
					$scope.files 			= [];
					$scope.errors 			= [];
					$scope.document 		= { files: files.data.data };

					$scope.$watch('file',function(){
						$scope.upload( $scope.files );
					});

					$scope.remove = function( file , index ){
						$scope.document.files.splice( index , 1 );						
						if( file.checked && $scope.document.files.length > 0 ){
							$scope.document.files[0].checked = true;
							$scope.document.attachment_id 	= $scope.document.files[0].attachment_id;
						}else{
							$scope.document.attachment_id 	= null;
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

											Upload.upload({

												url: 	':api/documents/attach',
												
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


					//On Form Submit
					$scope.submit 	= function( form ){
						if( form.$valid && !$scope.sending ){

							$scope.sending = true;

							$http.post( ':api/documents' , $scope.document ).then(function( response ){
								if( !response.data.result ){

									$scope.sending 	= false;
									$scope.success 	= '';
									$scope.errors 	= response.data.errors;

									$window.scrollTo(0,0);

								}else{

									form.$setPristine();

									$scope.sending 	= false;
									$scope.errors 	= [];
									$scope.success 	= 'The documents have been saved';

									$window.scrollTo(0,0);

								}
							});

						}
					};


				});








			}else
			if( Page.is( /^\/documents(\/[0-9]+)?$/ ) && User.hasPermission([ 'documents' ])  ){

				/**
				*
				*	ROUTE: /documents/([0-9]+)?
				*		- The Documents List
				* 	
				* 	Params (URL):
				* 		- page: 		(INT) Documentss Listing Pagination
				*
				**/
				(function(){

					$scope.page.data 		= [];
					$scope.list 			= { 
						query: 		( $location.search().q || null ), 
						sort: 		( $location.search().sort || null ),
						document: 	( $location.search().document || null ),
						loading: 	false,
					};

					//On Page Load
					$scope.load 			= function( page , searching ){

						var queries 		= [];
						var limit 			= $scope.page.showing;

						if( $scope.list.query ) 	queries.push( 'q=' + encodeURIComponent( $scope.list.query ) );
						if( $scope.list.sort ) 		queries.push( 'sort=' + encodeURIComponent( $scope.list.sort ) );
						if( $scope.list.document) 	queries.push( 'document=' + encodeURIComponent( $scope.list.document ) );

						if( !searching ){

							Page.loading.start();

						}else{

							$scope.list.loading = true;

						}

						Loader.get( ':api/documents' + ( limit ? '/' + limit : '' ) + ( page ? '/' + page : '' ) + ( queries.length > 0 ? '?' + queries.join('&') : '' ) , function( files ){
							if( files ){

								if( files.data.data.length > 0 || page == 1 ){

									if( $scope.page.current != page ){

										$location.path( '/documents' + ( page > 1 ? '/' + page : '' ) );

									}

									$.extend( $scope.page , { current: page }, files.data );

								}else
								if( !searching ){

									Page.error(404);

								}

								Page.loading.end();
								
								$scope.list.loading = false;

							}
						});

					};

					$scope.download = function( file ){

						window.location.href = Config.api + '/download/' + file.slug ;

					}


					$scope.view = function( media ){

						if( media.mime.type == 'video' ){							

							jQuery.dialog({
							    title: 				media.name,
							    content: 			'<video controls preload="auto" width="100%" height="100%"> <source src="' + media.url + '" type="' + media.mime.type + '/' + media.mime.subtype + '" /> </video>'
							});

						}else
						if( media.mime.type == 'image' ){

							jQuery.dialog({
								title: 				media.name,
								content: 			'<div class="clearfix">' + '<img src="' + media.image + '" />' + '<a href="' + media.image + '" target="_blank" class="btn btn-primary fright">Open in New Window <small>(Full Size)</small></a>' + '</div>'  
							});

						}else{

							//Open the File
							window.open( media.url , '_blank' );

						}

					}

					$scope.search = function( form ){

						$location.search('');

						Loader.remove( /:api\/documents*/ );

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