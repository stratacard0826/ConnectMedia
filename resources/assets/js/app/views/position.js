(function(){
	

	var app = angular.module('System.Views');

	app.config([ '$stateProvider' , '$urlRouterProvider' , function( $stateProvider , $urlRouterProvider ){

		$stateProvider.state({

			name: 			'positions-add',

			url: 			'/admin/positions/add',

			title: 			'Administration :: Positions :: Add Position',
			
			templateUrl: 	':api/layout/components/manage-position',
			
			controller: 	'PositionManagementController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'users' , url: '/admin/users' , name: 'Administration' } , { icon:'wrench' , url: '/admin/positions' , name: 'Positions' } , { icon:'plus-circle' , url: '/admin/positions/add' , name: 'Add Position' } ]
		
		}).state({

			name: 			'positions-edit',

			url: 			'/admin/positions/edit/{positionid:int}',

			title: 			'Administration :: Positions :: Edit Position',

			params: 		{ positionid: { value:null , squash:true } },
			
			templateUrl: 	':api/layout/components/manage-position',
			
			controller: 	'PositionManagementController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'users' , url: '/admin/users' , name: 'Administration' } , { icon:'wrench' , url: '/admin/positions' , name: 'Positions' } , { icon:'pencil-square' , url: '/admin/positions/edit/:positionid' , name: 'Edit Position' } ]
		
		}).state({

			name: 			'positions-list',

			url: 			'/admin/positions/{page:int}',

			title: 			'Administration :: Positions',
			
			params: 		{ page: { value:null , squash:true } },

			templateUrl: 	':api/layout/controllers/position',
			
			controller: 	'PositionManagementController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'users' , url: '/admin/users' , name: 'Administration' } , { icon:'wrench' , url: '/admin/positions' , name: 'Positions' } ]
		
		});

	}]);

	


})();