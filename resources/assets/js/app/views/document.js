(function(){
	

	var app = angular.module('System.Views');

	app.config([ '$stateProvider' , '$urlRouterProvider' , function( $stateProvider , $urlRouterProvider ){

		$stateProvider.state({

			name: 			'documents-manage',

			url: 			'/documents/manage',

			title: 			'Documents :: Manage Files',
			
			templateUrl: 	':api/layout/components/manage-document',
			
			controller: 	'DocumentController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'folder' , url: '/documents' , name: 'Documents' } , { icon:'pencil-square' , url: '/documents/manage' , name: 'Manage Files' } ]
		
		}).state({

			name: 			'documents-list',

			url: 			'/documents/{page:int}',

			title: 			'Documents',
			
			params: 		{ page: { value:null , squash:true } },

			templateUrl: 	':api/layout/controllers/document',
			
			controller: 	'DocumentController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'folder' , url: '/documents' , name: 'Documents' } ]
		
		});

	}]);

	


})();