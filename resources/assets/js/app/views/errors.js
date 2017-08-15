(function(){
	

	var app = angular.module('System.Views');

	app.config([ '$stateProvider' , '$urlRouterProvider' , function( $stateProvider , $urlRouterProvider ){

		$stateProvider.state({

			name: 			'404',

			title: 			'',

			templateUrl: 	':api/layout/errors/404',

			breadcrumbs: 	[]

		});


		$urlRouterProvider.otherwise(function($injector){

			$state = $injector.get('$state');

			$state.go( '404' , null , {
				
				location: false

			});

		});

	}]);


})();