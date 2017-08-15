(function(){
	

	var app = angular.module('System.Views');

	app.config([ '$stateProvider' , '$urlRouterProvider' , function( $stateProvider , $urlRouterProvider ){

		$stateProvider.state({

			name: 			'doctor-add',

			url: 			'/medical/doctors/add',

			title: 			'Doctors :: Add Doctor',
			
			templateUrl: 	':api/layout/components/manage-doctor',
			
			controller: 	'DoctorController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'heartbeat' , url: '/medical' , name: 'Medical Referrals' } , { icon:'user-md' , url: '/medical/doctors' , name: 'Doctors' } , { icon:'plus-circle' , url: '/medical/doctors/add' , name: 'Add Doctor' } ]
		
		}).state({

			name: 			'doctor-edit',

			url: 			'/medical/doctors/edit/{doctorid:int}',

			title: 			'Doctors :: Edit Doctor',

			params: 		{ doctorid: { value:null , squash:true } },
			
			templateUrl: 	':api/layout/components/manage-doctor',
			
			controller: 	'DoctorController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'heartbeat' , url: '/medical' , name: 'Medical Referrals' } , { icon:'user-md' , url: '/medical/doctors' , name: 'Doctors' } , { icon:'pencil-square' , url: '/medical/doctors/edit/:doctorid' , name: 'Edit Doctor' } ]
		
		}).state({

			name: 			'doctor-list',

			url: 			'/medical/doctors/{page:int}',

			title: 			'Doctors',
			
			params: 		{ page: { value:null , squash:true } },

			templateUrl: 	':api/layout/controllers/doctor',
			
			controller: 	'DoctorController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'heartbeat' , url: '/medical' , name: 'Medical Referrals' } , { icon:'user-md' , url: '/medical/doctors' , name: 'Doctors' } ]
		
		}).state({

			name: 			'doctor-view',

			url: 			'/medical/doctors/view/{doctorid:int}',

			title: 			'Doctors :: View Doctor',
			
			params: 		{ doctorid: { value:null , squash:true } },

			templateUrl: 	':api/layout/components/view-doctor',
			
			controller: 	'DoctorController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'heartbeat' , url: '/medical' , name: 'Medical Referrals' } , { icon:'user-md' , url: '/medical/doctors' , name: 'Doctors' } , { icon:'eye' , url: '/medical/doctors/view/:doctorid' , name: 'View Doctor' }  ]
		
		});

	}]);

	


})();