(function(){
	
	var app 		= angular.module('System.Interceptors');



	/**
	*
	*	$stateChange(Start|Finish)
	*		- On a State Change it shows the Loading Symbol
	*
	*
	**/
	app.run(['$rootScope', 'Page' , function($rootScope , Page) {  

		Page.loading.start();

		//Set Loading to True
		$rootScope.$on('$stateChangeStart', function(event, toState, toParams, fromState, fromParams){
		    if( toState.resolve ){

		    	Page.loading.start();
		    
		    }
		});

		//Set Loading to False
		$rootScope.$on('$stateChangeSuccess', function(event, toState, toParams, fromState, fromParams) {
		    if( !toState.resolve ){

		    	//Page.loading.end();
		    
		    }
		});

	}]);
	




})();