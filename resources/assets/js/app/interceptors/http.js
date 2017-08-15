(function(){
	
	var app 		= angular.module('System.Interceptors');
	





	/**
	*
	*	ApiInterceptor
	*		- Checks each outbound router for :api and replaces it with the correct API path
	*
	*
	**/
	app.factory('ApiInterceptor', [ '$location' , 'Config' , 'Authorization' , function( $location , Config , Authorization ){
		return {

			'request': function( request ){
				
				//Only run on LOCAL Api Requests - Otherwise this will break local cache
				if( request.url.indexOf( ':api') > -1 ){

					//Redirect all URL's to the correct API
					request.url = request.url.replace( ':api' , Config.api ) + ( request.url.indexOf( '?' ) > -1 ? '&' : '?' ) + 'time=' + new Date().getTime();

				}

				return request;

			},






			'responseError': function( request ){

				//Only run on a Logged in User
				if( Authorization.authorized && request.status == 401 ){

		    		window.location.href = '/auth/login';

				}

			}

		}
	}]);







	/**
	*
	*	$httpProvider.interceptors.push
	*		- Initializes the previous ApiInterceptor Factor
	*
	*
	**/
	app.config(['$httpProvider', function($httpProvider) {  

	    $httpProvider.interceptors.push('ApiInterceptor');

	}]);
	




})();