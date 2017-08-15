!(function($){

  	'use strict';

	//Setup the Directives
	var app = angular.module('System.Directives');

  	app.directive('qtip', function() {
      	return {
        
        	restrict: 'A',
        
        	scope : {
            	qtipVisible : '='
        	},
        
        	link: function(scope, element, attrs) {
          		var my 			    = attrs.qtipMy || 'bottom center' ,
            		  at 			    = attrs.qtipAt || 'top center' ,
            		  qtipClass 	= attrs.qtipClass || 'qtip' ,
            		  content 	 = attrs.qtipContent || attrs.qtip;
        
          		if (attrs.qtipTitle) {
 
            		content = {'title': attrs.qtipTitle, 'text': attrs.qtip};
 
          		}else{

                content = { 'text': attrs.qtip };

              }


          		$(element).qtip({
            		content: content,
            		position: {
              			my: my,
              			at: at,
              			target: element
            		},
            		hide: {
              			fixed : true,
              			delay : 100
            		},
            		style: qtipClass
          		});

          		if(attrs.qtipVisible) {
              		scope.$watch('qtipVisible', function (newValue, oldValue) {
                 		 $(element).qtip('toggle', newValue);
              		});
          		}
    
        	}
    
      	}
    
    });


})(jQuery);