(function(){

	'use strict';

	var app = angular.module('System.Controllers');

	//The App Controller
	app.controller('TechController', [ '$window' , '$stateParams' , '$scope' , '$http' , '$location' , 'Config' , 'User' , 'Page' , 'Store' , 'Role' , 'Loader' , 'Upload' , function( $window , $stateParams , $scope , $http , $location , Config , User , Page , Store , Role , Loader , Upload){
		User.ready(function(){

			if( Page.is( /^\/tech\/edit\/([0-9]+)$/ ) && User.hasPermission([ 'tech' , 'tech.edit' ]) ){




				/**
				*
				*	ROUTE: /tech/edit/([0-9]+)
				*		- Edit the Tech Talk	
				* 	
				* 	Params (URL):
				* 		- userid: 		(INT) The Tech Talk ID to Edit
				*
				**/

				Page.loading.start();

				Loader.get( ':api/tech/' + $stateParams.techid , function( tech ){

					if( tech.data.data.id ){

						Store.all(function( stores ){

							Role.all(function( roles ){
				
								Page.loading.end();

								$scope.sending 			= false;
								$scope.timer 			= null;
								$scope.running 			= 'Updating';
								$scope.action 			= 'edit';
								$scope.title 			= 'Edit Product';
								$scope.button 			= 'Edit Product';
								$scope.icon 			= 'pencil-square';
								$scope.hasError 		= Page.hasError;
								$scope.errors 			= [];
								$scope.tech 			= tech.data.data;
								$scope.roles 			= {
									data: 					roles,
									settings: 				{
										displayProp: 				'name',
										smartButtonMaxItems: 		3,
				    					smartButtonTextConverter: 	function(itemText, originalItem){ return itemText; }
									},
									events: 				{
										onItemSelect: 				function(){ $scope.techForm.roles.$dirty = $scope.tech.roles.length; },
										onItemDeselect: 			function(){ $scope.techForm.roles.$dirty = $scope.tech.roles.length; },
										onSelectAll: 				function(){ $scope.techForm.roles.$dirty = $scope.tech.roles.length; },
										onDeselectAll: 				function(){ $scope.techForm.roles.$dirty = false;  }
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
										onItemSelect: 				function(){ $scope.techForm.stores.$dirty = $scope.tech.stores.length; },
										onItemDeselect: 			function(){ $scope.techForm.stores.$dirty = $scope.tech.stores.length; },
										onSelectAll: 				function(){ $scope.techForm.stores.$dirty = $scope.tech.stores.length; },
										onDeselectAll: 				function(){ $scope.techForm.stores.$dirty = false; }
									}
								};

								$scope.$watch('file',function(){

									$scope.upload( $scope.files );

								});

								$scope.remove = function(){
									delete $scope.tech.attachment;
								};

								$scope.upload = function( files ){
									if( files && files.length ){

										(function( file ){

											$scope.tech.attachment = file;

											Upload.upload({

												url: 	':api/tech/attach',
												
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

										})( files[ files.length - 1 ] );

									}
								};



								//On Form Submit
								$scope.submit 	= function( form ){
									if( form.$valid && !$scope.sending ){

										$scope.sending = true;

										$http.post( ':api/tech/' + $stateParams.techid , $scope.tech ).then(function( response ){
											if( !response.data.result ){

												$scope.sending 	= false;
												$scope.success 	= '';
												$scope.errors 	= response.data.errors;

												$window.scrollTo(0,0);

											}else{

												form.$setPristine();

												$scope.sending 	= false;
												$scope.errors 	= [];
												$scope.success 	= 'The Tech Talk has been updated';							

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
			if( Page.is( /^\/tech\/add$/ ) &&  User.hasPermission([ 'tech' , 'tech.create' ])  ){


				/**
				*
				*	ROUTE: /tech/add
				*		- Add a New Tech Talk	
				* 	
				* 	Params (URL):
				* 		n/a
				*
				**/

				Page.loading.start();

				Store.all(function( stores ){

					Role.all(function( roles ){

						Page.loading.end();

						$scope.sending 			= false;
						$scope.timer 			= null;
						$scope.saving 			= false;
						$scope.running 			= 'Creating';
						$scope.action 			= 'insert';
						$scope.title 			= 'Add Product';
						$scope.button 			= 'Add Product';
						$scope.icon 			= 'plus-circle';
						$scope.hasError 		= Page.hasError;
						$scope.errors 			= [];
						$scope.tech 			= { 'specifications':[], 'roles':[] , 'stores':[], 'sendemail':1 };
						$scope.roles 			= {
							data: 					roles,
							settings: 				{
								displayProp: 				'name',
								smartButtonMaxItems: 		3,
		    					smartButtonTextConverter: 	function(itemText, originalItem){ return itemText; }
							},
							events: 				{
								onItemSelect: 				function(){ $scope.techForm.roles.$dirty = $scope.tech.roles.length; },
								onItemDeselect: 			function(){ $scope.techForm.roles.$dirty = $scope.tech.roles.length; },
								onSelectAll: 				function(){ $scope.techForm.roles.$dirty = $scope.tech.roles.length; },
								onDeselectAll: 				function(){ $scope.techForm.roles.$dirty = false;  }
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
								onItemSelect: 				function(){ $scope.techForm.stores.$dirty = $scope.tech.stores.length; },
								onItemDeselect: 			function(){ $scope.techForm.stores.$dirty = $scope.tech.stores.length; },
								onSelectAll: 				function(){ $scope.techForm.stores.$dirty = $scope.tech.stores.length; },
								onDeselectAll: 				function(){ $scope.techForm.stores.$dirty = false; }
							}
						};

						$scope.$watch('file',function(){
							$scope.upload( $scope.files );
						});

						$scope.remove = function(){
							delete $scope.tech.attachment;
						};

						$scope.upload = function( files ){
							if( files && files.length ){

								(function( file ){

									$scope.tech.attachment = file;

									Upload.upload({

										url: 	':api/tech/attach',
										
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

								})( files[ files.length - 1 ] );

							}
						};

						//On Form Submit
						$scope.submit 	= function( form ){
							if( form.$valid && !$scope.sending ){

								$scope.sending = true;

								$http.put( ':api/tech' , $scope.tech ).then(function( response ){
									if( !response.data.result ){


										$scope.sending 	= false;
										$scope.success 	= '';
										$scope.errors 	= response.data.errors;

										$window.scrollTo(0,0);

									}else{

										form.$setPristine();

										$scope.sending 	= false;
										$scope.errors 	= [];
										$scope.tech 	= { 'specifications':[], 'roles':[] , 'stores':[], 'sendemail':1 };
										$scope.success 	= 'The Product has been Created';			

										$window.scrollTo(0,0);

									}
								});

							}
						};

					});

				});











			}else
			if( Page.is( /^\/tech(\/[0-9]+)?$/ ) && User.hasPermission([ 'tech' ])  ){

				/**
				*
				*	ROUTE: /tech/([0-9]+)?
				*		- The Tech Talk List
				* 	
				* 	Params (URL):
				* 		- page: 		(INT) Tech Talks Listing Pagination
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

						Loader.get( ':api/tech' + ( limit ? '/' + limit : '' ) + ( page ? '/' + page : '' ) , function( tech ){
							if( tech ){

								if( tech.data.data.length > 0 || page == 1 ){

									if( $scope.page.current != page ){

										$location.path( '/tech' + ( page > 1 ? '/' + page : '' ) );

									}

									$.extend( $scope.page , { current: page }, tech.data );

									window.setTimeout(function(){

										new Masonry( document.getElementById('masonry') , {
										  	itemSelector: '.grid-item',
										  	columnWidth: '.grid-item',
										  	gutter: 20
										});

									} , 50 );

								}


								Page.loading.end();

								$scope.list.loading = false;

							}
						});

					};

					//On "Click Delete"
					$scope.delete 		= function( tech ){
						if( User.hasPermission([ 'tech.delete' ]) ){

							jQuery.confirm({
							    title: 				'Are you sure?',
							    content: 			'Are you sure you want to delete Tech Talk Product #' + tech.id,
							    confirmButton: 		'Delete Tech Talk',
								confirmButtonClass: 'btn-danger',
							    cancelButton: 		'Cancel',
								cancelButtonClass: 	'btn bgaaa coloraaa hoverbg555 hovercolorfff',
							    confirm: 			function(){

							    	$http.delete( ':api/tech/' + tech.id ).then(function( response ){
							    		if( !response.data.result ){

							    			$scope.success 	= '';
							    			$scope.errors 	= response.data.errors;

							    			$window.scrollTo(0,0);

							    		}else{

							    			$scope.errors 	= [];
							    			$scope.success 	= 'Tech Talk Product ' + tech.id + ' has been deleted.';

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