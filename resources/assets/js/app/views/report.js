(function(){
	

	var app = angular.module('System.Views');

	app.config([ '$stateProvider' , '$urlRouterProvider' , function( $stateProvider , $urlRouterProvider ){

		$stateProvider.state({

			name: 			'report-add',

			url: 			'/reports/add',

			title: 			'Weekly Reports :: Add Report',
			
			templateUrl: 	':api/layout/components/manage-report',
			
			controller: 	'ReportController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'area-chart' , url: '/reports' , name: 'Weekly Reports' } , { icon:'plus-circle' , url: '/report/add' , name: 'Add Report' } ]
		
		}).state({

			name: 			'report-edit',

			url: 			'/reports/edit/{reportid:int}',

			title: 			'Weekly Reports :: Edit Report',

			params: 		{ reportid: { value:null , squash:true } },
			
			templateUrl: 	':api/layout/components/manage-report',
			
			controller: 	'ReportController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'area-chart' , url: '/reports' , name: 'Weekly Reports' } , { icon:'pencil-square' , url: '/report/edit/:reportid' , name: 'Edit Report' } ]
		
		}).state({

			name: 			'report-list',

			url: 			'/reports/{page:int}',

			title: 			'Weekly Reports',
			
			params: 		{ page: { value:null , squash:true } },

			templateUrl: 	':api/layout/controllers/report',
			
			controller: 	'ReportController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'area-chart' , url: '/reports' , name: 'Weekly Reports' } ]
		
		}).state({

			name: 			'report-view',

			url: 			'/reports/view/{reportid:int}',

			title: 			'Weekly Reports :: View Report',
			
			params: 		{ reportid: { value:null , squash:true } },

			templateUrl: 	':api/layout/components/view-report',
			
			controller: 	'ReportController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'area-chart' , url: '/reports' , name: 'Weekly Reports' } , { icon:'eye' , url: '/report/view/:reportid' , name: 'View Report' }  ]
		
		});

	}]);

	


})();