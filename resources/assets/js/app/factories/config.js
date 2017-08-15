(function(){
	
	var app = angular.module('System.Services');
	
	app.factory('Config' , [function(){

		return {

			api: 	'/api/v1'

		};

	}]);

})();