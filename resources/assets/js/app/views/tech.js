(function(){
	

	var app = angular.module('System.Views');

	app.config([ '$stateProvider' , '$urlRouterProvider' , function( $stateProvider , $urlRouterProvider ){

		$stateProvider.state({

			name: 			'tech-add',

			url: 			'/tech/add',

			title: 			'Tech Talk :: Add Product',
			
			templateUrl: 	':api/layout/components/manage-tech',
			
			controller: 	'TechController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'cogs' , url: '/tech' , name: 'Tech Talk' } , { icon:'plus-circle' , url: '/tech/add' , name: 'Add Product' } ]
		
		}).state({

			name: 			'tech-edit',

			url: 			'/tech/edit/{techid:int}',

			title: 			'Tech Talk :: Edit Product',

			params: 		{ techid: { value:null , squash:true } },
			
			templateUrl: 	':api/layout/components/manage-tech',
			
			controller: 	'TechController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'cogs' , url: '/tech' , name: 'Tech Talk' } , { icon:'pencil-square' , url: '/tech/edit/:techid' , name: 'Edit Product' } ]
		
		}).state({

			name: 			'tech-list',

			url: 			'/tech/{page:int}',

			title: 			'Tech Talk',
			
			params: 		{ page: { value:null , squash:true } },

			templateUrl: 	':api/layout/controllers/tech',
			
			controller: 	'TechController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'cogs' , url: '/tech' , name: 'Tech Talk' } ]
		
		});

	}]);

	


})();