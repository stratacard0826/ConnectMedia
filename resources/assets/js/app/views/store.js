(function(){
	

	var app = angular.module('System.Views');

	app.config([ '$stateProvider' , '$urlRouterProvider' , function( $stateProvider , $urlRouterProvider ){

		$stateProvider.state({

			name: 			'stores-add',

			url: 			'/admin/stores/add',

			title: 			'Administration :: Store :: Add Store',
			
			templateUrl: 	':api/layout/components/manage-store',
			
			controller: 	'StoreManagementController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'users' , url: '/admin/users' , name: 'Administration' } , { icon:'map-marker' , url: '/admin/stores' , name: 'Stores' } , { icon:'plus-circle' , url: '/admin/stores/add' , name: 'Add Stores' } ]
		
		}).state({

			name: 			'stores-edit',

			url: 			'/admin/stores/edit/{storeid:int}',

			title: 			'Administration :: Store :: Edit Store',

			params: 		{ storeid: { value:null , squash:true } },
			
			templateUrl: 	':api/layout/components/manage-store',
			
			controller: 	'StoreManagementController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'users' , url: '/admin/users' , name: 'Administration' } , { icon:'map-marker' , url: '/admin/stores' , name: 'Stores' } , { icon:'pencil-square' , url: '/admin/stores/edit/:roleid' , name: 'Edit Stores' } ]
		
		}).state({

			name: 			'stores-list',

			url: 			'/admin/stores/{page:int}',

			title: 			'Administration :: Store',
			
			params: 		{ page: { value:null , squash:true } },

			templateUrl: 	':api/layout/controllers/store',
			
			controller: 	'StoreManagementController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'users' , url: '/admin/users' , name: 'Administration' } , { icon:'map-marker' , url: '/admin/stores' , name: 'Stores' } ]
		
		});

	}]);

	


})();