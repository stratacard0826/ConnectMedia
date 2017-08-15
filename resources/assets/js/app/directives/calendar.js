(function(){
	
	var app 		= angular.module('System.Directives');
	



	
	



	



	/**
	*
	*	DIRECTIVE: 	dateMin
	*		- Compares two dates based on the [date-compare-type]
	*
	*
	* 	USAGE:
	* 		[min-date='(Expression)']
	*
	**/
	app.directive('calendar', [ 'Config' , 'User' , 'Page' , function( Config , User , Page ){
		 return {

    		restrict: 'A',

	        require: 'ngModel',

	        
	        link: function($scope, $element, $attributes, $ctrl) {

		        	var Calendar = jQuery('[calendar]').fullCalendar({
						header: {

							left: 			'prev,next today',
							
							center: 		'title',
							
							right: 			'month,basicWeek,basicDay'
						
						},
						
						defaultDate: 		new Date(),
						
						editable: 			true,
						
						eventLimit: 		true,
					    eventLimitClick: 	'week',

		        		events: 			Config.api + '/user/events',

		        		//When an Event is Clicked
		        		eventClick: 		function( calEvent , jsEvent, view ){
		        			if( User.hasPermission([ 'events.view' ]) ){

		        				Page.open( '/admin/events/view/' + calEvent.id );

		        			}
		        		},

		        	});

	        }
	    };
	}]);







})();