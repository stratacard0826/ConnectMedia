(function(){
	

	var app = angular.module('System.Views');

	app.config([ '$stateProvider' , '$urlRouterProvider' , function( $stateProvider , $urlRouterProvider ){

		$stateProvider.state({

			name: 			'logouts-add',

			url: 			'/logouts/add',

			title: 			'Daily Logouts :: Add Logout',
			
			templateUrl: 	':api/layout/components/manage-logout',
			
			controller: 	'LogoutController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'sign-out' , url: '/logouts' , name: 'Daily Logouts' } , { icon:'plus-circle' , url: '/logouts/add' , name: 'Add Logout' } ]
		
		}).state({

			name: 			'logouts-edit',

			url: 			'/logouts/edit/{logoutid:int}',

			title: 			'Daily Logouts :: Edit Logout',

			params: 		{ logoutid: { value:null , squash:true } },
			
			templateUrl: 	':api/layout/components/manage-logout',
			
			controller: 	'LogoutController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'sign-out' , url: '/logouts' , name: 'Daily Logouts' } , { icon:'pencil-square' , url: '/logouts/edit/:logoutid' , name: 'Edit Logout' } ]
		
		}).state({

			name: 			'logouts-list',

			url: 			'/logouts/{page:int}',

			title: 			'Daily Logouts',
			
			params: 		{ page: { value:null , squash:true } },

			templateUrl: 	':api/layout/controllers/logout',
			
			controller: 	'LogoutController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'sign-out' , url: '/logouts' , name: 'Daily Logouts' } ]
		
		}).state({

			name: 			'logouts-view',

			url: 			'/logouts/view/{logoutid:int}',

			title: 			'Daily Logouts :: View Logout',
			
			params: 		{ logoutid: { value:null , squash:true } },

			templateUrl: 	':api/layout/components/view-logout',
			
			controller: 	'LogoutController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'sign-out' , url: '/logouts' , name: 'Daily Logouts' } , { icon:'eye' , url: '/logouts/view/:logoutid' , name: 'View Logout' }  ]
		
		}).state({

			name: 			'logouts-report',

			url: 			'/logouts/report',

			title: 			'Daily Logouts :: View Report',

			templateUrl: 	':api/layout/components/view-logout-report',
			
			controller: 	'LogoutController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'sign-out' , url: '/logouts' , name: 'Daily Logouts' } , { icon:'eye' , url: '/logouts/report' , name: 'View Report' }  ]
		
		});

	}]);

	


})();