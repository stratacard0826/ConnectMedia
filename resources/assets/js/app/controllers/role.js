(function(){

	'use strict';

	var app = angular.module('System.Controllers');

	//The App Controller
	app.controller('RoleManagementController', [ '$window' , '$stateParams' , '$scope' , '$http' , '$location' , 'User' , 'Role' , 'Page' , function( $window , $stateParams , $scope , $http , $location , User , Role , Page ){
		User.ready(function(){

			if( Page.is( /^\/admin\/roles\/edit\/([0-9]+)$/ ) && User.hasPermission([ 'roles' , 'roles.edit' ]) ){




				/**
				*
				*	ROUTE: /admin/roles/edit/([0-9]+)
				*		- Edit the Roles	
				* 	
				* 	Params (URL):
				* 		- roleid: 		(INT) The Role ID to Edit
				*
				**/

				Page.loading.start();

				Role.get(function( role ){
					if( role ){

						Role.permissions.get(function( permissions ){

							role.permissions = permissions;

							Role.permissions.all(function( permissions ){

								Page.loading.end();

								$scope.role 			= role;
								$scope.sending 			= false;
								$scope.running 			= 'Updating';
								$scope.action 			= 'edit';
								$scope.title 			= 'Edit Role: ' + role.name ;
								$scope.button 			= 'Edit Role';
								$scope.icon 			= 'pencil-square';
								$scope.hasError 		= Page.hasError;
								$scope.errors 			= [];
								$scope.permissions 		= {
									data: 					permissions.data,
									settings: 				{
										displayProp: 				'name',
										smartButtonMaxItems: 		3,
				    					smartButtonTextConverter: 	function(itemText, originalItem){ return itemText; }
									},
									events: 				{
										onItemSelect: 				function(){ $scope.roleForm.permissions.$dirty = $scope.role.permissions.length; },
										onItemDeselect: 			function(){ $scope.roleForm.permissions.$dirty = $scope.role.permissions.length; },
										onSelectAll: 				function(){ $scope.roleForm.permissions.$dirty = $scope.role.permissions.length; },
										onDeselectAll: 				function(){ $scope.roleForm.permissions.$dirty = false; }
									}
								};

								//On Form Submit
								$scope.submit 	= function( form ){
									if( form.$valid && !$scope.sending ){

										var data = angular.extend({}, $scope.role, {
											'permissions': 	jQuery.map( $scope.role.permissions, function(value,index){
												return value.id;
											})
										});

										$scope.sending = true;

										$http.post( ':api/role/' + role.id , data ).then(function( response ){
											if( !response.data.result ){

												$scope.sending 	= false;
												$scope.success 	= '';
												$scope.errors 	= response.data.errors;

												$window.scrollTo(0,0);

											}else{

												form.$setPristine();

												$scope.sending 	= false;
												$scope.errors 	= [];
												$scope.success 	= ( $scope.role.name + ' has been updated.' );

												$window.scrollTo(0,0);

											}
										});

									}
								};

							});

						}, $stateParams.roleid );

					}else{

						Page.error(404);

					}
				}, $stateParams.roleid );






			}else
			if( Page.is( /^\/admin\/roles\/add$/ ) &&  User.hasPermission([ 'roles' , 'roles.create' ]) ){




				/**
				*
				*	ROUTE: /admin/roles/add
				*		- Add a New Role
				* 	
				* 	Params (URL):
				* 		n/a
				*
				**/

				Page.loading.start();

				Role.permissions.all(function( permissions ){

					Page.loading.end();

					$scope.action 			= 'insert';
					$scope.sending 			= false;
					$scope.running 			= 'Adding';
					$scope.title 			= 'Add Role';
					$scope.button 			= 'Add Role';
					$scope.icon 			= 'plus-circle';
					$scope.hasError 		= Page.hasError;
					$scope.errors 			= [];
					$scope.role 			= { 'permissions':[] };
					$scope.permissions 		= {
						data: 					permissions.data,
						settings: 				{
							displayProp: 				'name',
							smartButtonMaxItems: 		3,
	    					smartButtonTextConverter: 	function(itemText, originalItem){ return itemText; },
						},
						events: 				{
							onItemSelect: 				function(){ $scope.roleForm.permissions.$dirty = $scope.role.permissions.length; },
							onItemDeselect: 			function(){ $scope.roleForm.permissions.$dirty = $scope.role.permissions.length; },
							onSelectAll: 				function(){ $scope.roleForm.permissions.$dirty = $scope.role.permissions.length; },
							onDeselectAll: 				function(){ $scope.roleForm.permissions.$dirty = false; }
						}
					};

					//On Form Submit
					$scope.submit 	= function( form ){
						if( form.$valid && !$scope.sending ){

							var data = angular.extend({}, $scope.role, {
								'permissions': 	jQuery.map( $scope.role.permissions, function(value,index){
									return value.id;
								})
							});

							$scope.sending = true;

							$http.put( ':api/role' , data ).then(function( response ){
								if( !response.data.result ){


									$scope.sending 	= false;
									$scope.success 	= '';
									$scope.errors 	= response.data.errors;

									$window.scrollTo(0,0);

								}else{

									form.$setPristine();

									$scope.sending 	= false;
									$scope.errors 	= [];
									$scope.success 	= ( $scope.role.name + ' has been added to the role list.' );
									$scope.role 	= { permissions:[] };

									$window.scrollTo(0,0);

								}
							});

						}
					};

				});










			}else
			if( Page.is( /^\/admin\/roles(\/[0-9]+)?$/ ) && User.hasPermission([ 'roles' ]) ){




				/**
				*
				*	ROUTE: /admin/roles/([0-9]+)?
				*		- The Role List
				* 	
				* 	Params (URL):
				* 		- page: 		(INT) Roles Listing Pagination
				*
				**/
				(function(){


					$scope.page.data 		= [];

					//Load the Page
					$scope.load 			= function( page ){

						Page.loading.start();

						Role.all(function( roles ){

							if( roles.data.length > 0 || page == 1 ){

								if( $scope.page.current != page ){

									$location.path( '/admin/roles' + ( page > 1 ? '/' + page : '' ) );

								}

								$.extend( $scope.page , { current: page }, roles );

							}else{

								Page.error(404);

							}

							Page.loading.end();

						}, page, $scope.page.showing);

					};

					//On Click "Delete"
					$scope.delete 		= function( role ){
						if( User.hasPermission([ 'roles.delete' ]) ){

							jQuery.confirm({
							    title: 				'Are you sure?',
							    content: 			'Are you sure you want to delete the role: ' + role.name,
							    confirmButton: 		'Delete Role',
								confirmButtonClass: 'btn-danger',
							    cancelButton: 		'Cancel',
								cancelButtonClass: 	'btn bgaaa coloraaa hoverbg555 hovercolorfff',
							    confirm: 			function(){

							    	$http.delete( ':api/role/' + role.id ).then(function( response ){
							    		if( !response.data.result ){

							    			$scope.success 	= '';
							    			$scope.errors 	= response.data.errors;

							    			$window.scrollTo(0,0);

							    		}else{

							    			$scope.errors 	= [];
							    			$scope.success 	= ( role.name + ' has been deleted.' );

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