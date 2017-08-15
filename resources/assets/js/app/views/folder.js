(function(){
	

	var app = angular.module('System.Views');

	app.config([ '$stateProvider' , '$urlRouterProvider' , function( $stateProvider , $urlRouterProvider ){

		$stateProvider.state({

			name: 			'folders-add',

			url: 			'/documents/folders/add',

			title: 			'Documents :: Folders :: Add Folder',
			
			templateUrl: 	':api/layout/components/manage-folder',
			
			controller: 	'FolderManagementController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'folder-open' , url:'/documents' , name: 'Documents' } , { icon:'folder' , url: '/documents/folders' , name: 'Folders' } , { icon:'plus-circle' , url: '/documents/folders/add' , name: 'Add Folder' } ]
		
		}).state({

			name: 			'folders-edit',

			url: 			'/documents/folders/edit/{folderid:int}',

			title: 			'Documents :: Folders :: Edit Folder',

			params: 		{ folderid: { value:null , squash:true } },
			
			templateUrl: 	':api/layout/components/manage-folder',
			
			controller: 	'FolderManagementController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'folder-open' , url:'/documents' , name: 'Documents' } , { icon:'folder' , url: '/documents/folders' , name: 'Folders' } , { icon:'pencil-square' , url: '/documents/folders/edit/:folderid' , name: 'Edit Folder' } ]
		
		}).state({

			name: 			'folders-list',

			url: 			'/documents/folders/{page:int}',

			title: 			'Documents :: Folders',
			
			params: 		{ page: { value:null , squash:true } },

			templateUrl: 	':api/layout/controllers/folder',
			
			controller: 	'FolderManagementController',

			breadcrumbs: 	[ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'folder-open' , url:'/documents' , name: 'Documents' } , { icon:'folder' , url: '/documents/folders' , name: 'Folders' } ]
		
		});

	}]);

	


})();