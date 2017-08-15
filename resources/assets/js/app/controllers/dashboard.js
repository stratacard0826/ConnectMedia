(function(){

	'use strict';

	var app = angular.module('System.Controllers');

	//The App Controller
	app.controller('DashboardController', [ 'Page' ,  function( Page ){

		Page.loading.end();

	}]);

})();