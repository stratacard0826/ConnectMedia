(function(){
	

	var app = angular.module('System.Views');

	app.config([ '$stateProvider' , '$urlRouterProvider' , function( $stateProvider , $urlRouterProvider ){

		$stateProvider.state({

			name: 			'promos-add',

			url: 			'/promos/add',

			title: 			'Marketing & Promos :: Add Promotion',
			
			templateUrl: 	':api/layout/components/manage-promo',
			
			controller: 	'PromoController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'bar-chart' , url: '/promos' , name: 'Marketing & Promos' } , { icon:'plus-circle' , url: '/promos/add' , name: 'Add Promotion' } ]
		
		}).state({

			name: 			'promos-edit',

			url: 			'/promos/edit/{promotionid:int}',

			title: 			'Marketing & Promos :: Edit Promotion',

			params: 		{ promotionid: { value:null , squash:true } },
			
			templateUrl: 	':api/layout/components/manage-promo',
			
			controller: 	'PromoController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'bar-chart' , url: '/promos' , name: 'Marketing & Promos' } , { icon:'pencil-square' , url: '/promos/edit/:promotionid' , name: 'Edit Promotion' } ]
		
		}).state({

			name: 			'promos-list',

			url: 			'/promos/{page:int}',

			title: 			'Marketing & Promos',
			
			params: 		{ page: { value:null , squash:true } },

			templateUrl: 	':api/layout/controllers/promo',
			
			controller: 	'PromoController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'bar-chart' , url: '/promos' , name: 'Marketing & Promos' } ]
		
		}).state({

			name: 			'promos-view',

			url: 			'/promos/view/{promotionid:int}',

			title: 			'Marketing & Promos :: View Promotion',
			
			params: 		{ promotionid: { value:null , squash:true } },

			templateUrl: 	':api/layout/components/view-promo',
			
			controller: 	'PromoController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'bar-chart' , url: '/promos' , name: 'Marketing & Promos' } , { icon:'eye' , url: '/promos/view/:promotionid' , name: 'View Promotion' }  ]
		
		});

	}]);

	


})();