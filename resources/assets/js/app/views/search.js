(function(){
	

	var app = angular.module('System.Views');

	app.config([ '$stateProvider' , '$urlRouterProvider' , function( $stateProvider , $urlRouterProvider ){
		
		$stateProvider.state({

			name: 			'search-list',

			url: 			'/search/{query}/{page:int}',

			title: 			'Search',
			
			params: 		{ query: { value:null , squash:true }, page: { value:null, squash:true} },

			templateUrl: 	':api/layout/controllers/search',
			
			controller: 	'SearchController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'search' , url: '/search' , name: 'Search' } ]
		
		});

	}]);


})();