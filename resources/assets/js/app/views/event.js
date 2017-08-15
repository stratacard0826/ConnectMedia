(function(){
	

	var app = angular.module('System.Views');

	app.config([ '$stateProvider' , '$urlRouterProvider' , function( $stateProvider , $urlRouterProvider ){

		$stateProvider.state({

			name: 			'events-add',

			url: 			'/admin/events/add',

			title: 			'Administration :: Events :: Add Event',
			
			templateUrl: 	':api/layout/components/manage-event',
			
			controller: 	'EventController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'users' , url: '/admin/users' , name: 'Administration' } , { icon:'calendar' , url: '/admin/events' , name: 'Events' } , { icon:'plus-circle' , url: '/admin/events/add' , name: 'Add Event' } ]
		
		}).state({

			name: 			'events-edit',

			url: 			'/admin/events/edit/{eventid:int}',

			title: 			'Administration :: Events :: Edit Event',

			params: 		{ eventid: { value:null , squash:true } },
			
			templateUrl: 	':api/layout/components/manage-event',
			
			controller: 	'EventController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'users' , url: '/admin/users' , name: 'Administration' } , { icon:'calendar' , url: '/admin/events' , name: 'Events' } , { icon:'pencil-square' , url: '/admin/events/edit/:eventid' , name: 'Edit Event' } ]
		
		}).state({

			name: 			'events-list',

			url: 			'/admin/events/{page:int}',

			title: 			'Administration :: Events',
			
			params: 		{ page: { value:null , squash:true } },

			templateUrl: 	':api/layout/controllers/event',
			
			controller: 	'EventController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'users' , url: '/admin/users' , name: 'Administration' } , { icon:'calendar' , url: '/admin/events' , name: 'Events' } ]
		
		}).state({

			name: 			'events-view',

			url: 			'/admin/events/view/{eventid:int}',

			title: 			'Administration :: Events :: View Event',
			
			params: 		{ eventid: { value:null , squash:true } },

			templateUrl: 	':api/layout/components/view-event',
			
			controller: 	'EventController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'users' , url: '/admin/users' , name: 'Administration' } , { icon:'calendar' , url: '/admin/events' , name: 'Events' } , { icon:'eye' , url: '/admin/events/view/:eventid' , name: 'View Event' } ]
		
		});

	}]);

	


})();