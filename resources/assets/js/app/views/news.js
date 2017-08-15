(function(){
	

	var app = angular.module('System.Views');

	app.config([ '$stateProvider' , '$urlRouterProvider' , function( $stateProvider , $urlRouterProvider ){

		$stateProvider.state({

			name: 			'news-add',

			url: 			'/news/add',

			title: 			'Administration :: News :: Add Article',
			
			templateUrl: 	':api/layout/components/manage-news',
			
			controller: 	'NewsController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'bell-o' , url: '/news' , name: 'Company News' } , { icon:'plus-circle' , url: '/news/add' , name: 'Add Article' } ]
		
		}).state({

			name: 			'news-edit',

			url: 			'/news/edit/{newsid:int}',

			title: 			'Administration :: News :: Edit Article',

			params: 		{ newsid: { value:null , squash:true } },
			
			templateUrl: 	':api/layout/components/manage-news',
			
			controller: 	'NewsController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'bell-o' , url: '/news' , name: 'Company News' } , { icon:'pencil-square' , url: '/news/edit/:newsid' , name: 'Edit Article' } ]
		
		}).state({

			name: 			'news-list',

			url: 			'/news/{page:int}',

			title: 			'Administration :: News',
			
			params: 		{ page: { value:null , squash:true } },

			templateUrl: 	':api/layout/controllers/news',
			
			controller: 	'NewsController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'users' , url: '/news' , name: 'Company News' } ]
		
		}).state({

			name: 			'news-view',

			url: 			'/news/view/{newsid:int}',

			title: 			'Administration :: News :: View Article',
			
			params: 		{ newsid: { value:null , squash:true } },

			templateUrl: 	':api/layout/components/view-news',
			
			controller: 	'NewsController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'users' , url: '/news' , name: 'Company News' } , { icon:'eye' , url: '/news/view/:newsid' , name: 'View Article' } ]
		
		});

	}]);

	


})();