(function(){
	

	var app = angular.module('System.Views');

	app.config([ '$stateProvider' , '$urlRouterProvider' , function( $stateProvider , $urlRouterProvider ){
		
		$stateProvider.state({

			name: 			'users-add',

			url: 			'^/admin/users/add',

			title: 			'Administration :: Users :: Add User',
			
			templateUrl: 	':api/layout/components/manage-user',
			
			controller: 	'UserManagementController',

			resolve: 		{ user: function( $stateParams, User ){ return User.hasPermission('user') && User.hasPermission('user.create') } },

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'users' , url: '/admin/users' , name: 'Administration' } , { icon:'user' , url: '/admin/users' , name: 'Users' } , { icon:'plus-circle' , url: '/admin/users/add' , name: 'Add User' } ]
		
		}).state({

			name: 			'users-edit',

			url: 			'^/admin/users/edit/{userid:int}',

			title: 			'Administration :: Users :: Edit User',

			params: 		{ page: { value:null, squash:true } },
			
			templateUrl: 	':api/layout/components/manage-user',
			
			controller: 	'UserManagementController',

			resolve: 		{ user: function( $stateParams, User ){ return User.hasPermission('user') && User.hasPermission( 'user.edit' ) } },

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'users' , url: '/admin/users' , name: 'Administration' } , { icon:'user' , url: '/admin/users' , name: 'Users' } , { icon:'pencil-square' , url: '/admin/users/edit/:userid' , name: 'Edit User' } ]
		
		}).state({

			name: 			'users-list',

			url: 			'^/admin/users/{page:int}',

			title: 			'Administration :: Users',

			params: 		{ page: { value:null, squash:true } },
			
			templateUrl: 	':api/layout/controllers/user',
			
			controller: 	'UserManagementController',

			resolve: 		{ user: function( $stateParams, User ){ return User.hasPermission( 'user' ) } },

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'users' , url: '/admin/users' , name: 'Administration' } , { icon:'user' , url: '/admin/users' , name: 'Users' } ]
		
		});

	}]);


})();