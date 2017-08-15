(function(){
	

	var app = angular.module('System.Views');

	app.config([ '$stateProvider' , '$urlRouterProvider' , function( $stateProvider , $urlRouterProvider ){

		$stateProvider.state({

			name: 			'bar-add',

			url: 			'/bar/add',

			title: 			'Bar & Drinks :: Add Recipe',
			
			templateUrl: 	':api/layout/components/manage-bar',
			
			controller: 	'BarController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'martini' , url: '/bar' , name: 'Bar & Drinks' } , { icon:'plus-circle' , url: '/bar/add' , name: 'Add Recipe' } ]
		
		}).state({

			name: 			'bar-edit',

			url: 			'/bar/edit/{recipeid:int}',

			title: 			'Bar & Drinks :: Edit Recipe',

			params: 		{ recipeid: { value:null , squash:true } },
			
			templateUrl: 	':api/layout/components/manage-bar',
			
			controller: 	'BarController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'martini' , url: '/bar' , name: 'Bar & Drinks' } , { icon:'pencil-square' , url: '/bar/edit/:recipeid' , name: 'Edit Recipe' } ]
		
		}).state({

			name: 			'bar-list',

			url: 			'/bar/{page:int}',

			title: 			'Bar & Drinks',
			
			params: 		{ page: { value:null , squash:true } },

			templateUrl: 	':api/layout/controllers/bar',
			
			controller: 	'BarController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'martini' , url: '/bar' , name: 'Bar & Drinks' } ]
		
		}).state({

			name: 			'bar-view',

			url: 			'/bar/view/{recipeid:int}',

			title: 			'Bar & Drinks :: View Recipe',
			
			params: 		{ recipeid: { value:null , squash:true } },

			templateUrl: 	':api/layout/components/view-bar',
			
			controller: 	'BarController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'martini' , url: '/bar' , name: 'Bar & Drinks' } , { icon:'eye' , url: '/bar/view/:recipeid' , name: 'View Recipe' }  ]
		
		});

	}]);

	


})();