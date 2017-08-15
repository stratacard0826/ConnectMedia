(function(){
	

	var app = angular.module('System.Views');

	app.config([ '$stateProvider' , '$urlRouterProvider' , function( $stateProvider , $urlRouterProvider ){

		$stateProvider.state({

			name: 			'sick-add',

			url: 			'/admin/sickday/add',

			title: 			'Sick Days :: Add Sick Day',
			
			templateUrl: 	':api/layout/components/manage-sickday',
			
			controller: 	'SickController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'users' , url: '/admin/users' , name: 'Administration' } , { icon:'ambulance' , url: '/admin/sick' , name: 'Sick Days' } , { icon:'plus-circle' , url: '/admin/sickday/add' , name: 'Add Sick Day' } ]
		
		}).state({

			name: 			'sick-edit',

			url: 			'/admin/sickday/edit/{sickid:int}',

			title: 			'Sick Days :: Edit Sick Day',

			params: 		{ sickid: { value:null , squash:true } },
			
			templateUrl: 	':api/layout/components/manage-sickday',
			
			controller: 	'SickController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'users' , url: '/admin/users' , name: 'Administration' } , { icon:'ambulance' , url: '/admin/sick' , name: 'Sick Days' } , { icon:'pencil-square' , url: '/admin/sickday/edit/:sickid' , name: 'Edit Sick Day' } ]
		
		}).state({

			name: 			'sick-list',

			url: 			'/admin/sickday/{page:int}',

			title: 			'Sick Days',
			
			params: 		{ page: { value:null , squash:true } },

			templateUrl: 	':api/layout/controllers/sickday',
			
			controller: 	'SickController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'users' , url: '/admin/users' , name: 'Administration' } , { icon:'ambulance' , url: '/admin/sick' , name: 'Sick Days' } ]
		
		}).state({

			name: 			'sick-view',

			url: 			'/admin/sickday/view/{sickid:int}',

			title: 			'Sick Days :: View Sick Day',
			
			params: 		{ sickid: { value:null , squash:true } },

			templateUrl: 	':api/layout/components/view-sickday',
			
			controller: 	'SickController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'users' , url: '/admin/users' , name: 'Administration' } , { icon:'ambulance' , url: '/admin/sick' , name: 'Sick Days' } , { icon:'eye' , url: '/admin/sickday/view/:sickid' , name: 'View Sick Day' }  ]
		
		});

	}]);

	


})();