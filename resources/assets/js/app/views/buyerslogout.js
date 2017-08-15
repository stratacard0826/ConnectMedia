(function(){
	

	var app = angular.module('System.Views');

	app.config([ '$stateProvider' , '$urlRouterProvider' , function( $stateProvider , $urlRouterProvider ){

		$stateProvider.state({

			name: 			'buyers-add',

			url: 			'/buyers/add',

			title: 			'Buyers Logout :: Add Logout',
			
			templateUrl: 	':api/layout/components/manage-buyerslogout',
			
			controller: 	'BuyerController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'credit-card' , url: '/logouts' , name: 'Buyers Logout' } , { icon:'plus-circle' , url: '/buyers/add' , name: 'Add Logout' } ]
		
		}).state({

			name: 			'buyers-edit',

			url: 			'/buyers/edit/{logoutid:int}',

			title: 			'Buyers Logout :: Edit Logout',

			params: 		{ logoutid: { value:null , squash:true } },
			
			templateUrl: 	':api/layout/components/manage-buyerslogout',
			
			controller: 	'BuyerController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'credit-card' , url: '/logouts' , name: 'Buyers Logout' } , { icon:'pencil-square' , url: '/buyers/edit/:logoutid' , name: 'Edit Logout' } ]
		
		}).state({

			name: 			'buyers-list',

			url: 			'/buyers/{page:int}',

			title: 			'Buyers Logout',
			
			params: 		{ page: { value:null , squash:true } },

			templateUrl: 	':api/layout/controllers/buyerslogout',
			
			controller: 	'BuyerController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'credit-card' , url: '/logouts' , name: 'Buyers Logout' } ]
		
		}).state({

			name: 			'buyers-view',

			url: 			'/buyers/view/{logoutid:int}',

			title: 			'Buyers Logout :: View Logout',
			
			params: 		{ logoutid: { value:null , squash:true } },

			templateUrl: 	':api/layout/components/view-buyerslogout',
			
			controller: 	'BuyerController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'credit-card' , url: '/logouts' , name: 'Buyers Logout' } , { icon:'eye' , url: '/buyers/view/:logoutid' , name: 'View Logout' }  ]
		
		}).state({

			name: 			'buyers-report',

			url: 			'/buyers/report',

			title: 			'Buyers Logout :: View Report',

			templateUrl: 	':api/layout/components/view-buyerslogout-report',
			
			controller: 	'BuyerController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'credit-card' , url: '/logouts' , name: 'Buyers Logout' } , { icon:'eye' , url: '/buyers/report' , name: 'View Report' }  ]
		
		});

	}]);

	


})();