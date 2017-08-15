(function(){
	
	var app = angular.module('System.Services');
	
	app.factory('Authorization' , [function(){

		this.authorized = false;

		return this;

	}]);

})();