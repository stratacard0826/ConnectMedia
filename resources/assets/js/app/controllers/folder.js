(function(){

	'use strict';

	var app = angular.module('System.Controllers');

	//The App Controller
	app.controller('FolderManagementController', [ '$window' , '$stateParams' , '$scope' , '$http' , '$location' , 'Loader' , 'User' , 'Role' , 'Page' , 'Hierarchy' , function( $window , $stateParams , $scope , $http , $location , Loader , User , Role , Page , Hierarchy ){
		User.ready(function(){

			if( Page.is( /^\/documents\/folders\/edit\/([0-9]+)$/ ) && User.hasPermission([ 'folders' , 'folders.edit' ]) ){




				/**
				*
				*	ROUTE: /documents/folders/edit/([0-9]+)
				*		- Edit the Folders	
				* 	
				* 	Params (URL):
				* 		- folderid: 		(INT) The Folder ID to Edit
				*
				**/

				Page.loading.start();


				Loader.get( ':api/folder/' + $stateParams.folderid , function( folder ){
					if( folder.data.id ){				
						Loader.get( ':api/folders' , function( folders ){

							Page.loading.end();

							$scope.sending 			= false;
							$scope.folder 			= folder.data;
							$scope.running 			= 'Updating';
							$scope.action 			= 'edit';
							$scope.title 			= 'Edit Folder: ' + folder.name ;
							$scope.button 			= 'Edit Folder';
							$scope.icon 			= 'pencil-square';
							$scope.hasError 		= Page.hasError;
							$scope.errors 			= [];
							$scope.folders 			= Hierarchy.create({ 
								data: folders.data.data, 
								each: function( item , parents ){

									return item.id != folder.data.id && parents.indexOf( folder.data.id ) == -1;

								}
							});

							//On Form Submit
							$scope.submit 	= function( form ){
								if( form.$valid && !$scope.sending ){

									$scope.sending = true;

									$http.post( ':api/folder/' + folder.data.id , $scope.folder ).then(function( response ){
										if( !response.data.result ){

											$scope.sending 	= false;
											$scope.success 	= '';
											$scope.errors 	= response.data.errors;

											$window.scrollTo(0,0);

										}else{
											Loader.get( ':api/folders' , function( folders ){

												form.$setPristine();

												$scope.sending 	= false;
												$scope.errors 	= [];
												$scope.success 	= ( $scope.folder.name + ' has been updated.' );
												$scope.folders 	= Hierarchy.create({ 
													data: folders.data.data, 
													each: function( item , parents ){

														return item.id != folder.data.id && parents.indexOf( folder.data.id ) == -1;

													}
												});

												$window.scrollTo(0,0);

											});
										}
									});

								}
							};

						});
					}else{

						Page.error(404);

					}
				}, $stateParams.folderid );






			}else
			if( Page.is( /^\/documents\/folders\/add$/ ) &&  User.hasPermission([ 'folders' , 'folders.create' ]) ){




				/**
				*
				*	ROUTE: /documents/folders/add
				*		- Add a New Folder
				* 	
				* 	Params (URL):
				* 		n/a
				*
				**/

				Page.loading.start();

				Loader.get( ':api/folders' , function( folders ){

					Page.loading.end();

					$scope.sending 			= false;
					$scope.running 			= 'Creating';
					$scope.action 			= 'insert';
					$scope.title 			= 'Add Folder';
					$scope.button 			= 'Add Folder';
					$scope.icon 			= 'plus-circle';
					$scope.hasError 		= Page.hasError;
					$scope.errors 			= [];
					$scope.folders 			= Hierarchy.create({ data: folders.data.data });

					//On Form Submit
					$scope.submit 	= function( form ){
						if( form.$valid && !$scope.sending ){

							$scope.sending = true;

							$http.put( ':api/folder' , $scope.folder ).then(function( response ){
								if( !response.data.result ){

									$scope.sending 	= false;
									$scope.success 	= '';
									$scope.errors 	= response.data.errors;

									$window.scrollTo(0,0);

								}else{
									Loader.get( ':api/folders' , function( folders ){

										form.$setPristine();

										$scope.sending 		= false;
										$scope.errors 		= [];
										$scope.success 		= ( $scope.folder.name + ' has been added to the folder list.' );
										$scope.folder 		= {};
										$scope.folders 		= Hierarchy.create({ data: folders.data.data });

										$window.scrollTo(0,0);

									});
								}
							});

						}
					};

				});









			}else
			if( Page.is( /^\/documents\/folders(\/[0-9]+)?$/ ) && User.hasPermission([ 'folders' ]) ){




				/**
				*
				*	ROUTE: /documents/folders/([0-9]+)?
				*		- The Folder List
				* 	
				* 	Params (URL):
				* 		- page: 		(INT) Folders Listing Pagination
				*
				**/
				(function(){


					$scope.page.data 		= [];

					//Load the Page
					$scope.load 			= function( page ){

						Page.loading.start();

						var limit = $scope.page.showing;

						Loader.get( ':api/folders' + ( limit ? '/' + limit : '' ) + ( page ? '/' + page : '' ) , function( folders ){
							console.log(folders)
							folders.data.data 		= Hierarchy.create({ data: folders.data.data });

							if( folders.data.data.length > 0 || page == 1 ){

								if( $scope.page.current != page ){

									$location.path( '/documents/folders' + ( page > 1 ? '/' + page : '' ) );

								}

								$.extend( $scope.page , { current: page }, folders.data );

							}else{

								Page.error(404);

							}

							Page.loading.end();

						}, page, $scope.page.showing);

					};

					//On Click "Delete"
					$scope.delete 		= function( folder ){
						if( User.hasPermission([ 'folders.delete' ]) ){

							jQuery.confirm({
							    title: 				'Are you sure?',
							    content: 			'Are you sure you want to delete the folder: ' + folder.name,
							    confirmButton: 		'Delete Folder',
								confirmButtonClass: 'btn-danger',
							    cancelButton: 		'Cancel',
								cancelButtonClass: 	'btn bgaaa coloraaa hoverbg555 hovercolorfff',
							    confirm: 			function(){

							    	$http.delete( ':api/folder/' + folder.id ).then(function( response ){
							    		if( !response.data.result ){

							    			$scope.success 	= '';
							    			$scope.errors 	= response.data.errors;

							    			$window.scrollTo(0,0);

							    		}else{

							    			$scope.errors 	= [];
							    			$scope.success 	= ( folder.name + ' has been deleted.' );

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




			}

		});
	}]);


})();