(function(){
	

	var app = angular.module('System.Views');

	app.config([ '$stateProvider' , '$urlRouterProvider' , function( $stateProvider , $urlRouterProvider ){

		$stateProvider.state({

			name: 			'promotions-add',

			url: 			'/admin/promotions/add',

			title: 			'Administration :: Promotions :: Add promotion',
			
			templateUrl: 	':api/layout/components/manage-promotion',
			
			controller: 	'PromotionManagementController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'users' , url: '/admin/users' , name: 'Administration' } , { icon:'lock' , url: '/admin/promotions' , name: 'Promotions' } , { icon:'plus-circle' , url: '/admin/promotions/add' , name: 'Add Promotion' } ]
		
		}).state({

			name: 			'promotions-edit',

			url: 			'/admin/promotions/edit/{promotionid:int}',

			title: 			'Administration :: Promotions :: Edit Promotion',

			params: 		{ promotionid: { value:null , squash:true } },
			
			templateUrl: 	':api/layout/components/manage-promotion',
			
			controller: 	'PromotionManagementController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'users' , url: '/admin/users' , name: 'Administration' } , { icon:'lock' , url: '/admin/promotions' , name: 'Promotions' } , { icon:'pencil-square' , url: '/admin/promotions/edit/:promotionid' , name: 'Edit Promotion' } ]
		
		}).state({

			name: 			'promotions-list',

			url: 			'/admin/promotions/{page:int}',

			title: 			'Administration :: Promotions',
			
			params: 		{ page: { value:null , squash:true } },

			templateUrl: 	':api/layout/controllers/promotion',
			
			controller: 	'PromotionManagementController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'users' , url: '/admin/users' , name: 'Administration' } , { icon:'lock' , url: '/admin/promotions' , name: 'Promotions' } ]
		
		});

	}]);

	


})();