(function(){
	
	var app 		= angular.module('System.Directives');
	







	/**
	*
	*	DIRECTIVE: 	dropdown
	*		- Builds the Select Dropdown
	*
	*	REFERENCE:
	* 		- http://jsfiddle.net/cojahmetov/3DS49/
	*
	* 	USAGE:
	* 		<dropdown 
	* 			data-menu-type 				= Sets the Menu HTML: ( button | null )
	* 			select-val 					= Returns the (selectedVal)
	* 			preselected-item			= Sets the Pre-selected Status
	* 			data-dropdown-data			= The Array of Dropdown Items: [{ id:1 , name:'Option Name' }]
	* 			data-dropdown-class 		= Classes to Assigned the Dropdown
	* 		></dropdown>
	*
	**/
	app.directive('dropdown', [ '$compile' , function( $compile ){
	    return {
	        restrict: 'E',
	        
	        scope: {
	            items: '=dropdownData',
	            doSelect: '&selectVal',
	            selectedItem: '=preselectedItem'
	        },

	        link: function ($scope, $element, $attributes) {

	            switch ($attributes.menuType) {
	            
	                case 'button':
	                    var html = '<div class="btn-group"><button class="btn button-label' + ( $attributes.selectClass? ' ' + $attributes.selectClass : '' ) + '">Action</button><button class="btn dropdown-toggle' + ( $attributes.buttonClass? ' ' + $attributes.buttonClass : '' ) + '" data-toggle="dropdown"><span class="caret"></span></button>';
	                    break;
	            
	                default:
	                    var html = '<div class="dropdown"><a class="dropdown-toggle" role="button" data-toggle="dropdown"  href="javascript:;">Dropdown<b class="caret"></b></a>';

	            }
	            
	            html += '<ul class="dropdown-menu"><li ng-repeat="item in items"><a tabindex="-1" data-ng-click="selectVal(item)">{{item.name}}</a></li></ul></div>';
	            
	            $element.append($compile(html)($scope));

	            for (var i = 0; i < $scope.items.length; i++) {
	                if ($scope.items[i].id === $scope.selectedItem) {
	            
	                    $scope.bSelectedItem = $scope.items[i];
	                    break;
	            
	                }
	            }

	            $scope.selectVal = function (item) {
	            	if( item ){

		                switch ($attributes.menuType) {
		                   
		                    case 'button':
		                        jQuery('button.button-label', $element).html(item.name);
		                        break;
		                    
		                    default:
		                        jQuery('a.dropdown-toggle', $element).html('<b class="caret"></b> ' + item.name);
		                        break;
		                }

		                $scope.doSelect({
		                    selectedVal: item.id
		                });

		            }
	            };

	            $scope.selectVal($scope.bSelectedItem);
	        }
	    };
	}]);
	















	/**
	*
	*	DIRECTIVE: 	paginationLimit
	*		- Creates the Pagination Dropdown
	*=
	*
	* 	USAGE:
	* 		<pagination-limit></pagination-limit>
	*
	**/
	app.directive('paginationLimit', [ '$location' , function( $location ){
	    return {
	        restrict: 'E',
	        
	        template: '<dropdown ' +
	        	'class 					= "pagination-total" ' +
	        	'data-menu-type			= "button" '  +
	        	'select-val 			= "changePaginationLimit( selectedVal )" ' +
	        	'preselected-item		= "15" ' +
	        	'button-class 			= "bgbbb colorfff hovercolorfff hoverbgaaa" ' +
	        	'select-class 			= "bgbbb colorfff hovercolorfff" ' +
	        	'data-dropdown-data 	= "[ ' +
	        		"{ id:15, name:'Showing 15 Results' }," +
	        		"{ id:30, name:'Showing 30 Results' }," +
	        		"{ id:50, name:'Showing 50 Results'}," +
	        		"{ id:100, name:'Showing 100 Results'}" +
	        	']"></dropdown>',

	        link: function( $scope , $element , $attributes ){

	        	$scope.page.showing = ( $location.search().limit || 15 );

	        	$scope.changePaginationLimit = function( limit ){

	        		$scope.page.showing = limit;
	        		$scope.page.current = 1;

					$location.search({ 'limit' : limit });

	        	}

	        }

	    }
	}]);
	


















})();