(function(){
	

	var app = angular.module('System.Views');

	app.config([ '$stateProvider' , '$urlRouterProvider' , function( $stateProvider , $urlRouterProvider ){
		
		$stateProvider.state({

			name: 			'profile-edit',

			url: 			'^/profile',

			title: 			'Edit Profile',
			
			templateUrl: 	':api/layout/components/manage-profile',
			
			controller: 	'ProfileManagementController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'user' , url: '/profile' , name: 'Edit Profile' } ]
		
		});

	}]);


})();