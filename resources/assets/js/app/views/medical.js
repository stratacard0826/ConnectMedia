(function(){
	

	var app = angular.module('System.Views');

	app.config([ '$stateProvider' , '$urlRouterProvider' , function( $stateProvider , $urlRouterProvider ){

		$stateProvider.state({

			name: 			'medical-add',

			url: 			'/medical/add',

			title: 			'Medical Referrals :: Add Referral',
			
			templateUrl: 	':api/layout/components/manage-medical',
			
			controller: 	'MedicalController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'heartbeat' , url: '/medical' , name: 'Medical Referrals' } , { icon:'plus-circle' , url: '/medical/add' , name: 'Add Referral' } ]
		
		}).state({

			name: 			'medical-edit',

			url: 			'/medical/edit/{medicalid:int}',

			title: 			'Medical Referrals :: Edit Referral',

			params: 		{ medicalid: { value:null , squash:true } },
			
			templateUrl: 	':api/layout/components/manage-medical',
			
			controller: 	'MedicalController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'heartbeat' , url: '/medical' , name: 'Medical Referrals' } , { icon:'pencil-square' , url: '/medical/edit/:medicalid' , name: 'Edit Referral' } ]
		
		}).state({

			name: 			'medical-list',

			url: 			'/medical/{page:int}',

			title: 			'Medical Referrals',
			
			params: 		{ page: { value:null , squash:true } },

			templateUrl: 	':api/layout/controllers/medical',
			
			controller: 	'MedicalController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'heartbeat' , url: '/medical' , name: 'Medical Referrals' } ]
		
		}).state({

			name: 			'medical-view',

			url: 			'/medical/view/{medicalid:int}',

			title: 			'Medical Referrals :: View Referral',
			
			params: 		{ medicalid: { value:null , squash:true } },

			templateUrl: 	':api/layout/components/view-medical',
			
			controller: 	'MedicalController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'heartbeat' , url: '/medical' , name: 'Medical Referrals' } , { icon:'eye' , url: '/medical/view/:medicalid' , name: 'View Referral' }  ]
		
		});

	}]);

	


})();