(function(){
	

	var app = angular.module('System.Views');

	app.config([ '$stateProvider' , '$urlRouterProvider' , function( $stateProvider , $urlRouterProvider ){

    	$urlRouterProvider.when('', '/');
		
		$stateProvider.state({

			name: 			'dashboard',

			url: 			'/',

			title: 			'Dashboard',
			
			params: 		{ squash:true },

			templateUrl: 	':api/layout/controllers/dashboard',
			
			controller: 	'DashboardController',

			breadcrumbs: 	[ { icon: 'home' , url:'/', name: 'Dashboard' } ]
		
		});

	}]);

	


})();