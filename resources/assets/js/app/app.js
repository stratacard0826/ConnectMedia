(function(){

	'use strict';

	var app = angular.module('System',[
		'System.Controllers',
		'System.Modules',
		'System.Views',
		'System.Directives',
		'System.Filters',
		'System.Services',
		'System.Interceptors',
		'ngSanitize',
		'ngMessages',
		'ui.bootstrap',
		'ui.router',
		'angularUtils.directives.dirPagination',
		'angularjs-dropdown-multiselect',
		'textAngular',
		'ngFileUpload',
		'angular-inview',
		'ui.bootstrap.datetimepicker',
		'angularMoment'
	]);

	//Setup the Controllers
	angular.module('System.Controllers',['ui.router']);

	//Setup the Modules
	angular.module('System.Modules',[]);

	//Setup the Views
	angular.module('System.Views',['ui.router']);

	//Setup the Directives
	angular.module('System.Directives',[]);

	//Setup the Directives
	angular.module('System.Filters',[]);

	//Setup the Services
	angular.module('System.Services',[]);

	//Setup the Interceptors
	angular.module('System.Interceptors',[]);



	/**
	*
	* 	app.config
	*		- App Configuration
	*
	**/
	app.config(['$httpProvider', '$locationProvider', function($httpProvider, $locationProvider) {  

		//Pass the X-Requested-With for Laravel
	    $httpProvider.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

        $locationProvider.html5Mode(true);

	}]);

	
	app.config(function(paginationTemplateProvider) {

	    paginationTemplateProvider.setPath(':api/layout/_partials/pagination');

	});


	app.run(['$templateCache', function($templateCache) {
	  'use strict';

	  $templateCache.put('template/date-picker.html',
	    "<ul class=\"dropdown-menu dropdown-menu-left datetime-picker-dropdown\" ng-if=\"isOpen && showPicker == 'date'\" ng-style=dropdownStyle style=left:inherit ng-keydown=keydown($event) ng-click=$event.stopPropagation()><li style=\"padding:0 5px 5px 5px\" class=date-picker-menu><div ng-transclude></div></li><li style=padding:5px ng-if=buttonBar.show><span class=\"btn-group pull-left\" style=margin-right:10px ng-if=\"doShow('today') || doShow('clear')\"><button type=button class=\"btn btn-sm btn-info\" ng-if=\"doShow('today')\" ng-click=\"select('today')\" ng-disabled=\"isDisabled('today')\">{{ getText('today') }}</button> <button type=button class=\"btn btn-sm btn-danger\" ng-if=\"doShow('clear')\" ng-click=\"select('clear')\">{{ getText('clear') }}</button></span> <span class=\"btn-group pull-right\" ng-if=\"(doShow('time') && enableTime) || doShow('close')\"><button type=button class=\"btn btn-sm btn-default\" ng-if=\"doShow('time') && enableTime\" ng-click=\"changePicker($event, 'time')\">{{ getText('time')}}</button> <button type=button class=\"btn btn-sm btn-success\" ng-if=\"doShow('close')\" ng-click=close(true)>{{ getText('close') }}</button></span></li></ul>"
	  );


	  $templateCache.put('template/time-picker.html',
	    "<ul class=\"dropdown-menu dropdown-menu-left datetime-picker-dropdown\" ng-if=\"isOpen && showPicker == 'time'\" ng-style=dropdownStyle style=left:inherit ng-keydown=keydown($event) ng-click=$event.stopPropagation()><li style=\"padding:0 5px 5px 5px\" class=time-picker-menu><div ng-transclude></div></li><li style=padding:5px ng-if=buttonBar.show><span class=\"btn-group pull-left\" style=margin-right:10px ng-if=\"doShow('now') || doShow('clear')\"><button type=button class=\"btn btn-sm btn-info\" ng-if=\"doShow('now')\" ng-click=\"select('now')\" ng-disabled=\"isDisabled('now')\">{{ getText('now') }}</button> <button type=button class=\"btn btn-sm btn-danger\" ng-if=\"doShow('clear')\" ng-click=\"select('clear')\">{{ getText('clear') }}</button></span> <span class=\"btn-group pull-right\" ng-if=\"(doShow('date') && enableDate) || doShow('close')\"><button type=button class=\"btn btn-sm btn-default\" ng-if=\"doShow('date') && enableDate\" ng-click=\"changePicker($event, 'date')\">{{ getText('date')}}</button> <button type=button class=\"btn btn-sm btn-success\" ng-if=\"doShow('close')\" ng-click=close(true)>{{ getText('close') }}</button></span></li></ul>"
	  );

	}]);



})();