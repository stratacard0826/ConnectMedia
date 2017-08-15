(function(){

	'use strict';

	var app = angular.module('System.Controllers');

	//The App Controller
	app.controller('UserManagementController', [ '$window' , '$stateParams' , '$scope' , '$http' , '$location' , 'User' , 'Role' , 'Page' , 'Store' , function( $window , $stateParams , $scope , $http , $location , User , Role , Page , Store ){
		User.ready(function(){

			if( Page.is( /^\/admin\/users\/edit\/([0-9]+)$/ ) && User.hasPermission([ 'users' , 'users.edit' ]) ){

				/**
				*
				*	ROUTE: /admin/users/edit/([0-9]+)
				*		- Edit the Users	
				* 	
				* 	Params (URL):
				* 		- userid: 		(INT) The User ID to Edit
				*
				**/

				Page.loading.start();

				User.get(function( user ){
					if( user ){

						User.roles(function( roles ){

							user.roles 	 	= ( roles[0] || [] );

							User.stores(function( stores ){
			
								user.stores 	= stores;

								User.customPermissions(function( permissions ){

									user.permissions = permissions;

									Store.all(function( stores ){

										Role.all(function( roles ){

											Role.permissions.all(function( permissions ){

												Page.loading.end();

												$scope.sending 			= false;
												$scope.user 			= user;
												$scope.running 			= 'Updating';
												$scope.action 			= 'edit';
												$scope.title 			= 'Edit User: ' + user.id ;
												$scope.button 			= 'Edit User';
												$scope.icon 			= 'pencil-square';
												$scope.hasError 		= Page.hasError;
												$scope.errors 			= [];
												$scope.roles 			= {
													data: 					roles,
													settings: 				{
														displayProp: 				'name',
														smartButtonMaxItems: 		3,
								    					smartButtonTextConverter: 	function(itemText, originalItem){ return itemText; },
								    					closeOnSelect: 				true,
														selectionLimit: 			1
													},
													events: 				{
														onItemSelect: 				function(){ $scope.userForm.roles.$dirty = true; },
														onSelectAll: 				function(){ $scope.userForm.roles.$dirty = true; },
														onDeselectAll: 				function(){ $scope.userForm.roles.$dirty = false;  }
													}
												};
												$scope.permissions 		= {
													data: 					permissions.data,
													settings: 				{
														displayProp: 				'name',
														smartButtonMaxItems: 		3,
								    					smartButtonTextConverter: 	function(itemText, originalItem){ return itemText; }
													},
													events: 				{
														onItemSelect: 				function(){ $scope.userForm.permissions.$dirty = $scope.user.permissions.length; },
														onItemDeselect: 			function(){ $scope.userForm.permissions.$dirty = $scope.user.permissions.length; },
														onSelectAll: 				function(){ $scope.userForm.permissions.$dirty = $scope.user.permissions.length; },
														onDeselectAll: 				function(){ $scope.userForm.permissions.$dirty = false; }
													}
												};
												$scope.stores 			= {
													data: 					stores,
													settings: 				{
														displayProp: 				'name',
														showCheckAll: 				false,
														showUncheckAll: 			false,
														smartButtonMaxItems: 		3,
								    					smartButtonTextConverter: 	function(itemText, originalItem){ return itemText; },
													},
													events: 				{
														onItemSelect: 				function(){ $scope.userForm.stores.$dirty = $scope.user.stores.length; },
														onItemDeselect: 			function(){ $scope.userForm.stores.$dirty = $scope.user.stores.length; },
														onSelectAll: 				function(){ $scope.userForm.stores.$dirty = $scope.user.stores.length; },
														onDeselectAll: 				function(){ $scope.userForm.stores.$dirty = false; }
													}
												};

												//On Form Submit
												$scope.submit 	= function( form ){
													if( form.$valid && !$scope.sending ){

														$scope.sending = true;

														var data = angular.extend({}, $scope.user, {
															'roles': 		( $scope.user.roles.id || '' ), 
															'permissions': 	jQuery.map( $scope.user.permissions, function(value,index){
																return value.id;
															}), 
															'stores': 		jQuery.map( $scope.user.stores, function(value,index){
																return value.id;
															})
														});

														$http.post( ':api/user/' + user.id , data ).then(function( response ){
															if( !response.data.result ){

																$scope.sending 	= false;
																$scope.success 	= '';
																$scope.errors 	= response.data.errors;

																$window.scrollTo(0,0);

															}else{

																form.$setPristine();

																$scope.sending 	= false;
																$scope.errors 	= [];
																$scope.success 	= ( $scope.user.username || $scope.user.email ) + ' has been updated.';

																$window.scrollTo(0,0);

															}
														});


													}
												};

											});

										});

									});

								}, $stateParams.userid );

							}, $stateParams.userid );

						}, $stateParams.userid );

					}else{

						Page.error(404);

					}
				}, $stateParams.userid );






			}else
			if( Page.is( /^\/admin\/users\/add$/ ) && User.hasPermission([ 'users' , 'users.create' ]) ){


				/**
				*
				*	ROUTE: /admin/users/add
				*		- Add a New User	
				* 	
				* 	Params (URL):
				* 		n/a
				*
				**/

				Page.loading.start();

				Role.all(function( roles ){

					Role.permissions.all(function( permissions ){

						Store.all(function( stores ){

							Page.loading.end();

							$scope.sending 			= false;
							$scope.running 			= 'Creating';
							$scope.action 			= 'insert';
							$scope.title 			= 'Add User';
							$scope.button 			= 'Add User';
							$scope.icon 			= 'plus-circle';
							$scope.hasError 		= Page.hasError;
							$scope.errors 			= [];
							$scope.user 			= { 'roles':[] , 'stores':[] , 'permissions':[] };
							$scope.sending 			= false;
							$scope.roles 			= {
								data: 					roles,
								settings: 				{
									displayProp: 				'name',
									smartButtonMaxItems: 		3,
			    					smartButtonTextConverter: 	function(itemText, originalItem){ return itemText; },
			    					closeOnSelect: 				true,
									selectionLimit: 			1
								},
								events: 				{
									onItemSelect: 				function(){ $scope.userForm.roles.$dirty = true; },
									onSelectAll: 				function(){ $scope.userForm.roles.$dirty = true; },
									onDeselectAll: 				function(){ $scope.userForm.roles.$dirty = false; }
								}
							};
							$scope.permissions 		= {
								data: 					permissions.data,
								settings: 				{
									displayProp: 				'name',
									smartButtonMaxItems: 		3,
			    					smartButtonTextConverter: 	function(itemText, originalItem){ return itemText; }
								},
								events: 				{
									onItemSelect: 				function(){ $scope.userForm.permissions.$dirty = $scope.user.permissions.length; },
									onItemDeselect: 			function(){ $scope.userForm.permissions.$dirty = $scope.user.permissions.length; },
									onSelectAll: 				function(){ $scope.userForm.permissions.$dirty = $scope.user.permissions.length; },
									onDeselectAll: 				function(){ $scope.userForm.permissions.$dirty = false; }
								}
							};
							$scope.stores 			= {
								data: 					stores,
								settings: 				{
									displayProp: 				'name',
									showCheckAll: 				false,
									showUncheckAll: 			false,
									smartButtonMaxItems: 		3,
			    					smartButtonTextConverter: 	function(itemText, originalItem){ return itemText; },
								},
								events: 				{
									onItemSelect: 				function(){ $scope.userForm.stores.$dirty = $scope.user.stores.length; },
									onItemDeselect: 			function(){ $scope.userForm.stores.$dirty = $scope.user.stores.length; },
									onSelectAll: 				function(){ $scope.userForm.stores.$dirty = $scope.user.stores.length; },
									onDeselectAll: 				function(){ $scope.userForm.stores.$dirty = false; }
								}
							};

							//On Form Submit
							$scope.submit 	= function( form ){
								if( form.$valid && !$scope.sending ){

									$scope.sending = true;

									var data = angular.extend({}, $scope.user, {
										'roles': 		( $scope.user.roles.id || '' ), 
										'permissions': 	jQuery.map( $scope.user.permissions, function(value,index){
											return value.id;
										}), 
										'stores': 		jQuery.map( $scope.user.stores, function(value,index){
											return value.id;
										})
									});

									$http.put( ':api/user' , data ).then(function( response ){
										if( !response.data.result ){

											$scope.sending 	= false;
											$scope.success 	= '';
											$scope.errors 	= response.data.errors;

											$window.scrollTo(0,0);

										}else{

											form.$setPristine();

											$scope.sending 	= false;
											$scope.errors 	= [];
											$scope.success 	= ( $scope.user.username || $scope.user.email ) + ' has been added to the user list.';
											$scope.user 	= { roles:[] , stores: [] , 'permissions':[] };

											$window.scrollTo(0,0);

										}
									});

								}
							};

						});

					});

				});










			}else
			if( Page.is( /^\/admin\/users(\/[0-9]+)?$/ ) && User.hasPermission([ 'users' ]) ){




				/**
				*
				*	ROUTE: /admin/users/([0-9]+)?
				*		- The User List
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

						User.all(function( users ){

							if( users.data.length > 0 || page == 1 ){

								if( $scope.page.current != page ){

									$location.path( '/admin/users' + ( page > 1 ? '/' + page : '' ) );

								}

								angular.extend( $scope.page , { current: page }, users );

							}else{

								Page.error(404);

							}

							Page.loading.end();

						}, page, $scope.page.showing);
					
					};

					//On Click "Delete"
					$scope.delete 		= function( user ){
						if( User.hasPermission([ 'users.delete' ]) ){

							jQuery.confirm({
							    title: 				'Are you sure?',
							    content: 			'Are you sure you want to delete the user: ' + ( user.username || user.email ),
							    confirmButton: 		'Delete User',
								confirmButtonClass: 'btn-danger',
							    cancelButton: 		'Cancel',
								cancelButtonClass: 	'btn bgaaa coloraaa hoverbg555 hovercolorfff',
							    confirm: 			function(){

							    	$http.delete( ':api/user/' + user.id ).then(function( response ){
							    		if( !response.data.result ){

							    			$scope.success 	= '';
							    			$scope.errors 	= response.data.errors;

							    			$window.scrollTo(0,0);

							    		}else{

							    			$scope.errors 	= [];
							    			$scope.success 	= ( user.username || user.email ) + ' has been deleted.';

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