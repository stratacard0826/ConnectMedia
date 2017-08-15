(function(){

	'use strict';

	var app = angular.module('System.Controllers');

	//The App Controller
	app.controller('PromotionManagementController', [ '$window' , '$stateParams' , '$scope' , '$http' , '$location' , 'Loader' , 'User' , 'Role' , 'Page' , function( $window , $stateParams , $scope , $http , $location , Loader , User , Promotion , Page ){
		User.ready(function(){

			if( Page.is( /^\/admin\/promotions\/edit\/([0-9]+)$/ ) && User.hasPermission([ 'promotions' , 'promotions.edit' ]) ){




				/**
				*
				*	ROUTE: /admin/promotions/edit/([0-9]+)
				*		- Edit the promotions	
				* 	
				* 	Params (URL):
				* 		- Promotionid: 		(INT) The Promotion ID to Edit
				*
				**/

				Page.loading.start();


				Loader.get( ':api/promotion/' + $stateParams.promotionid , function( promotion ){
					if( promotion.data.result && promotion.data.data.id ){

						Page.loading.end();

						$scope.sending 			= false;
						$scope.promotion 		= promotion.data.data;
						$scope.running 			= 'Updating';
						$scope.action 			= 'edit';
						$scope.title 			= 'Edit Promotion: ' + promotion.data.data.name ;
						$scope.button 			= 'Edit Promotion';
						$scope.icon 			= 'pencil-square';
						$scope.hasError 		= Page.hasError;
						$scope.errors 			= [];

						//On Form Submit
						$scope.submit 	= function( form ){
							if( form.$valid && !$scope.sending ){

								$scope.sending = true;

								$http.post( ':api/promotion/' + promotion.data.data.id , $scope.promotion ).then(function( response ){
									if( !response.data.result ){

										$scope.sending 	= false;
										$scope.success 	= '';
										$scope.errors 	= response.data.errors;

										$window.scrollTo(0,0);

									}else{

										form.$setPristine();

										$scope.sending 	= false;
										$scope.errors 	= [];
										$scope.success 	= ( $scope.promotion.name + ' has been updated.' );

										$window.scrollTo(0,0);

									}
								});

							}
						};

					}else{

						Page.error(404);

					}
				}, $stateParams.Promotionid );






			}else
			if( Page.is( /^\/admin\/promotions\/add$/ ) &&  User.hasPermission([ 'promotions' , 'promotions.create' ]) ){




				/**
				*
				*	ROUTE: /admin/promotions/add
				*		- Add a New Promotion
				* 	
				* 	Params (URL):
				* 		n/a
				*
				**/


				Page.loading.end();

				$scope.sending 			= false;
				$scope.action 			= 'insert';
				$scope.running 			= 'Creating';
				$scope.title 			= 'Add Promotion';
				$scope.button 			= 'Add Promotion';
				$scope.icon 			= 'plus-circle';
				$scope.hasError 		= Page.hasError;
				$scope.errors 			= [];

				//On Form Submit
				$scope.submit 	= function( form ){
					if( form.$valid && !$scope.sending ){

						$scope.sending = true;

						$http.put( ':api/promotion' , $scope.promotion ).then(function( response ){
							if( !response.data.result ){

								$scope.sending 	= false;
								$scope.success 	= '';
								$scope.errors 	= response.data.errors;

								$window.scrollTo(0,0);

							}else{

								form.$setPristine();

								$scope.sending 		= false;
								$scope.errors 		= [];
								$scope.success 		= ( $scope.promotion.name + ' has been added to the Promotion list.' );
								$scope.promotion 	= {};

								$window.scrollTo(0,0);

							}
						});

					}
				};










			}else
			if( Page.is( /^\/admin\/promotions(\/[0-9]+)?$/ ) && User.hasPermission([ 'promotions' ]) ){




				/**
				*
				*	ROUTE: /admin/promotions/([0-9]+)?
				*		- The Promotion List
				* 	
				* 	Params (URL):
				* 		- page: 		(INT) promotions Listing Pagination
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

						Loader.get( ':api/promotions' + ( limit ? '/' + limit : '' ) + ( page ? '/' + page : '' ) + ( queries.length > 0 ? '?' + queries.join('&') : '' ) , function( promotions ){
							if( promotions ){

								if( promotions.data.data.length > 0 || page == 1 ){

									if( $scope.page.current != page ){

										$location.path( '/admin/promotions' + ( page > 1 ? '/' + page : '' ) );

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
					$scope.delete 		= function( promotions ){
						if( User.hasPermission([ 'promotions.delete' ]) ){

							jQuery.confirm({
							    title: 				'Are you sure?',
							    content: 			'Are you sure you want to delete the promotion: ' + promotions.name,
							    confirmButton: 		'Delete Promotion',
								confirmButtonClass: 'btn-danger',
							    cancelButton: 		'Cancel',
								cancelButtonClass: 	'btn bgaaa coloraaa hoverbg555 hovercolorfff',
							    confirm: 			function(){

							    	$http.delete( ':api/promotion/' + promotions.id ).then(function( response ){
							    		if( !response.data.result ){

							    			$scope.success 	= '';
							    			$scope.errors 	= response.data.errors;

							    			$window.scrollTo(0,0);

							    		}else{

							    			$scope.errors 	= [];
							    			$scope.success 	= promotions.name + ' has been deleted.';

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

						Loader.remove( /:api\/admin\/promotions*/ );

						$scope.load( 1 , true );

					}

					//Load the First Page
					$scope.load( ( $stateParams.page || 1 ) , false );

				})();




			}

		});
	}]);


})();