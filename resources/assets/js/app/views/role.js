(function(){
	

	var app = angular.module('System.Views');

	app.config([ '$stateProvider' , '$urlRouterProvider' , function( $stateProvider , $urlRouterProvider ){

		$stateProvider.state({

			name: 			'roles-add',

			url: 			'/admin/roles/add',

			title: 			'Administration :: Roles :: Add Role',
			
			templateUrl: 	':api/layout/components/manage-role',
			
			controller: 	'RoleManagementController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'users' , url: '/admin/users' , name: 'Administration' } , { icon:'lock' , url: '/admin/roles' , name: 'Roles' } , { icon:'plus-circle' , url: '/admin/roles/add' , name: 'Add Role' } ]
		
		}).state({

			name: 			'roles-edit',

			url: 			'/admin/roles/edit/{roleid:int}',

			title: 			'Administration :: Roles :: Edit Role',

			params: 		{ roleid: { value:null , squash:true } },
			
			templateUrl: 	':api/layout/components/manage-role',
			
			controller: 	'RoleManagementController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'users' , url: '/admin/users' , name: 'Administration' } , { icon:'lock' , url: '/admin/roles' , name: 'Roles' } , { icon:'pencil-square' , url: '/admin/roles/edit/:roleid' , name: 'Edit Role' } ]
		
		}).state({

			name: 			'roles-list',

			url: 			'/admin/roles/{page:int}',

			title: 			'Administration :: Roles',
			
			params: 		{ page: { value:null , squash:true } },

			templateUrl: 	':api/layout/controllers/role',
			
			controller: 	'RoleManagementController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'users' , url: '/admin/users' , name: 'Administration' } , { icon:'lock' , url: '/admin/roles' , name: 'Roles' } ]
		
		});

	}]);

	


})();