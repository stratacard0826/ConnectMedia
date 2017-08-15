(function(){
	

	var app = angular.module('System.Views');

	app.config([ '$stateProvider' , '$urlRouterProvider' , function( $stateProvider , $urlRouterProvider ){

		$stateProvider.state({

			name: 			'menu-add',

			url: 			'/menu/add',

			title: 			'Food & Menus :: Add Recipe',
			
			templateUrl: 	':api/layout/components/manage-menu',
			
			controller: 	'MenuController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'dinner' , url: '/menu' , name: 'Food & Menus' } , { icon:'plus-circle' , url: '/menu/add' , name: 'Add Recipe' } ]
		
		}).state({

			name: 			'menu-edit',

			url: 			'/menu/edit/{recipeid:int}',

			title: 			'Food & Menus :: Edit Recipe',

			params: 		{ recipeid: { value:null , squash:true } },
			
			templateUrl: 	':api/layout/components/manage-menu',
			
			controller: 	'MenuController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'dinner' , url: '/menu' , name: 'Food & Menus' } , { icon:'pencil-square' , url: '/menu/edit/:recipeid' , name: 'Edit Recipe' } ]
		
		}).state({

			name: 			'menu-list',

			url: 			'/menu/{page:int}',

			title: 			'Food & Menus',
			
			params: 		{ page: { value:null , squash:true } },

			templateUrl: 	':api/layout/controllers/menu',
			
			controller: 	'MenuController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'dinner' , url: '/menu' , name: 'Food & Menus' } ]
		
		}).state({

			name: 			'menu-view',

			url: 			'/menu/view/{recipeid:int}',

			title: 			'Food & Menus :: View Recipe',
			
			params: 		{ recipeid: { value:null , squash:true } },

			templateUrl: 	':api/layout/components/view-menu',
			
			controller: 	'MenuController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'dinner' , url: '/menu' , name: 'Food & Menus' } , { icon:'eye' , url: '/menu/view/:recipeid' , name: 'View Recipe' }  ]
		
		});

	}]);

	


})();