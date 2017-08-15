(function(){
	

	var app = angular.module('System.Views');

	app.config([ '$stateProvider' , '$urlRouterProvider' , function( $stateProvider , $urlRouterProvider ){

		$stateProvider.state({

			name: 			'payrolls-add',

			url: 			'/payrolls/add',

			title: 			'Payrolls :: Add Payroll',
			
			templateUrl: 	':api/layout/components/manage-payroll',
			
			controller: 	'PayrollController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'money' , url: '/payrolls' , name: 'Payrolls' } , { icon:'plus-circle' , url: '/payrolls/add' , name: 'Add Payroll' } ]
		
		}).state({

			name: 			'payrolls-edit',

			url: 			'/payrolls/edit/{payrollid:int}',

			title: 			'Payrolls :: Edit Payroll',

			params: 		{ payrollid: { value:null , squash:true } },
			
			templateUrl: 	':api/layout/components/manage-payroll',
			
			controller: 	'PayrollController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'money' , url: '/payrolls' , name: 'Payrolls' } , { icon:'pencil-square' , url: '/payrolls/edit/:payrollid' , name: 'Edit Payroll' } ]
		
		}).state({

			name: 			'payrolls-list',

			url: 			'/payrolls/{page:int}',

			title: 			'Payrolls',
			
			params: 		{ page: { value:null , squash:true } },

			templateUrl: 	':api/layout/controllers/payroll',
			
			controller: 	'PayrollController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'money' , url: '/payrolls' , name: 'Payrolls' } ]
		
		}).state({

			name: 			'payrolls-view',

			url: 			'/payrolls/view/{payrollid:int}',

			title: 			'Payrolls :: View Payroll',
			
			params: 		{ payrollid: { value:null , squash:true } },

			templateUrl: 	':api/layout/components/view-payroll',
			
			controller: 	'PayrollController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'money' , url: '/payrolls' , name: 'Payrolls' } , { icon:'eye' , url: '/payrolls/view/:payrollid' , name: 'View Payroll' }  ]
		
		});

	}]);

	


})();