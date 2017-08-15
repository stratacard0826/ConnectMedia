(function(){
	
	var app 		= angular.module('System.Directives'),
		intervals 	= {
			email: 		null,
			username: 	null
		};
	

	app.directive('required', [ function() {
	    return {

	        restrict: 'A',

	        require: 'ngModel',
    		
    		link: function($scope, $element, $attributes, $ctrl) { 

		      	$scope.$watch( $attributes.ngModel, function(value){

		      		var $el = jQuery( $element ).nextAll( '.required' );

		      		if( ( !value || value == '' ) && !$el.length ){

		            	$element.parent().append('<span class="required"></span>');
			
					}else
					if( value != '' && $el.length ){

						$el.remove();

					}

				});
	        
	        }
	    };
	}]);


	/**
	*
	*	DIRECTIVE: 	isEmpty
	*		- Checks to see if the field is empty
	*
	* 	USAGE:
	* 		[empty]
	*
	**/
	app.directive('isEmpty', [ '$http' , function($http){
  		return {

    		restrict: 'A',
    		
    		require: 'ngModel',
    		
    		link: function($scope, $element, $attributes, $ctrl) { 

		      	$scope.$watch( $attributes.ngModel, function(value) {

		      		$ctrl.$empty = ( $element[0].value == '' );

	    		});

	    	}

	  	}

	}]);




	



	/**
	*
	*	DIRECTIVE: 	uniqueEmail
	*		- Checks to see if the email already exists in the database
	*
	* 	USAGE:
	* 		[unique-email]
	*
	**/
	app.directive('uniqueEmail', [ '$http' , function($http){
  		return {

    		restrict: 'A',
    		
    		require: 'ngModel',

    		link: function($scope, $element, $attributes, $ctrl) { 

    			var userid = null;

    			$attributes.$observe( 'uniqueEmail' , function(value){

    				$scope.$watch( value , function(id){

    					userid = id;

	    			});

    			});

		      	$scope.$watch( $attributes.ngModel, function(value) {
		        	if( value ){

			        	if( intervals.email ){

			        		clearTimeout( intervals.email );

			        	}

				        intervals.email = setTimeout(function(){

				          	$http.get( ':api/user/exists?email=' + value + '&userid=' + userid ).success(function(data) {

				              	//set the validity of the field
				              	$ctrl.$setValidity('uniqueEmail', !data );
		          			
		          			});

		        		}, 200);

				    }
	    		});

	    	}

	  	}

	}]);
	



	



	/**
	*
	*	DIRECTIVE: 	uniqueUsername
	*		- Checks to see if the username already exists in the database
	*
	* 	USAGE:
	* 		[unique-username]
	*
	**/
	app.directive('uniqueUsername', [ '$http' , function($http){
  		return {

    		restrict: 'A',
    		
    		require: 'ngModel',

    		link: function($scope, $element, $attributes, $ctrl) {

    			var userid = null;

    			$attributes.$observe( 'uniqueUsername' , function(value){

    				$scope.$watch( value , function(id){

    					userid = id;

	    			});

    			});

		      	$scope.$watch( $attributes.ngModel , function(value){
		      		if( value ){
		        	
			        	if( intervals.username ){

			        		clearTimeout( intervals.username );

			        	}

				        intervals.username = setTimeout(function(){
				          	$http.get( ':api/user/exists?username=' + value + '&userid=' + userid ).success(function(data){

				              	//set the validity of the field
				              	$ctrl.$setValidity( 'uniqueUsername', !data );
		          			
		          			});

		        		}, 200);

				    }
	    		});

	    	}

	  	}

	}]);
	



	



	/**
	*
	*	DIRECTIVE: 	uniqueUsername
	*		- Checks to see if the username already exists in the database
	*
	* 	USAGE:
	* 		[unique-username]
	*
	**/
	app.directive('uniqueRole', [ '$http' , function($http){
  		return {

    		restrict: 'A',
    		
    		require: 'ngModel',

    		link: function($scope, $element, $attributes, $ctrl) {

    			var roleid = null;

    			$attributes.$observe( 'uniqueRole' , function(value){

    				$scope.$watch( value , function(id){

    					roleid = id;

	    			});

    			});

		      	$scope.$watch( $attributes.ngModel , function(value){
		      		if( value ){
		        	
			        	if( intervals.role ){

			        		clearTimeout( intervals.role );

			        	}

				        intervals.role = setTimeout(function(){
				          	$http.get( ':api/role/exists?name=' + value + '&roleid=' + roleid ).success(function(data){

				              	//set the validity of the field
				              	$ctrl.$setValidity( 'uniqueRole', !data );
		          			
		          			});

		        		}, 200);

				    }
	    		});

	    	}

	  	}

	}]);
	



	



	/**
	*
	*	DIRECTIVE: 	uniquePromotion
	*		- Checks to see if the promotion already exists in the database
	*
	* 	USAGE:
	* 		[unique-username]
	*
	**/
	app.directive('uniquePromotion', [ '$http' , function($http){
  		return {

    		restrict: 'A',
    		
    		require: 'ngModel',

    		link: function($scope, $element, $attributes, $ctrl) {

    			var promotionid = null;

    			$attributes.$observe( 'uniquePromotion' , function(value){

    				$scope.$watch( value , function(id){

    					promotionid = id;

	    			});

    			});

		      	$scope.$watch( $attributes.ngModel , function(value){
		      		if( value ){
		        	
			        	if( intervals.promotion ){

			        		clearTimeout( intervals.promotion );

			        	}

				        intervals.promotion = setTimeout(function(){
				          	$http.get( ':api/promotion/exists?name=' + value + '&promotionid=' + promotionid ).success(function(data){

				              	//set the validity of the field
				              	$ctrl.$setValidity( 'uniquePromotion', !data );
		          			
		          			});

		        		}, 200);

				    }
	    		});

	    	}

	  	}

	}]);
	



	



	/**
	*
	*	DIRECTIVE: 	uniquePosition
	*		- Checks to see if the position already exists in the database
	*
	* 	USAGE:
	* 		[unique-username]
	*
	**/
	app.directive('uniquePosition', [ '$http' , function($http){
  		return {

    		restrict: 'A',
    		
    		require: 'ngModel',

    		link: function($scope, $element, $attributes, $ctrl) {

    			var positionid = null;

    			$attributes.$observe( 'uniquePosition' , function(value){

    				$scope.$watch( value , function(id){

    					positionid = id;

	    			});

    			});

		      	$scope.$watch( $attributes.ngModel , function(value){
		      		if( value ){
		        	
			        	if( intervals.position ){

			        		clearTimeout( intervals.position );

			        	}

				        intervals.position = setTimeout(function(){
				          	$http.get( ':api/position/exists?name=' + value + '&positionid=' + positionid ).success(function(data){

				              	//set the validity of the field
				              	$ctrl.$setValidity( 'uniquePosition', !data );
		          			
		          			});

		        		}, 200);

				    }
	    		});

	    	}

	  	}

	}]);
	



	



	/**
	*
	*	DIRECTIVE: 	uniqueStoreName
	*		- Checks to see if the store name already exists in the database
	*
	* 	USAGE:
	* 		[unique-store-name]
	*
	**/
	app.directive('uniqueStoreName', [ '$http' , function($http){
  		return {

    		restrict: 'A',
    		
    		require: 'ngModel',

    		link: function($scope, $element, $attributes, $ctrl) {

    			var storeid = null;

    			$attributes.$observe( 'uniqueStoreName' , function(value){

    				$scope.$watch( value , function(id){

    					storeid = id;

	    			});

    			});

		      	$scope.$watch( $attributes.ngModel , function(value){
		      		if( value ){
		        	
			        	if( intervals.name ){

			        		clearTimeout( intervals.name );

			        	}

				        intervals.name = setTimeout(function(){
				          	$http.get( ':api/store/exists?name=' + value + '&storeid=' + storeid ).success(function(data){

				              	//set the validity of the field
				              	$ctrl.$setValidity( 'uniqueStoreName', !data );
		          			
		          			});

		        		}, 200);

				    }
	    		});

	    	}

	  	}

	}]);
	



	



	/**
	*
	*	DIRECTIVE: 	uniqueDoctor
	*		- Checks to see if the doctor name already exists in the database
	*
	* 	USAGE:
	* 		[unique-doctor-name]
	*
	**/
	app.directive('uniqueDoctorEmail', [ '$http' , function($http){
  		return {

    		restrict: 'A',
    		
    		require: 'ngModel',

    		link: function($scope, $element, $attributes, $ctrl) {

    			var doctorid = null;

    			$attributes.$observe( 'uniqueDoctorEmail' , function(value){

    				$scope.$watch( value , function(id){

    					doctorid = id;

	    			});

    			});

		      	$scope.$watch( $attributes.ngModel , function(value){
		      		if( value ){
		        	
			        	if( intervals.doctor ){

			        		clearTimeout( intervals.doctor );

			        	}

				        intervals.doctor = setTimeout(function(){
				          	$http.get( ':api/doctor/exists?email=' + value + '&doctorid=' + doctorid ).success(function(data){

				          		console.log( data , '??' );

				              	//set the validity of the field
				              	$ctrl.$setValidity( 'uniqueDoctorEmail', !data );
		          			
		          			});

		        		}, 200);

				    }
	    		});

	    	}

	  	}

	}]);
	



	



	/**
	*
	*	DIRECTIVE: 	phonenumber
	*		- Validates a Phone Number field
	*
	* 	USAGE:
	* 		[phonenumber]
	*
	**/
	app.directive('phonenumber', [ '$http' , function($http){
  		return {

    		restrict: 'A',
    		
    		require: 'ngModel',

    		link: function($scope, $element, $attributes, $ctrl) { 

		      	$scope.$watch( $attributes.ngModel , function(value) {
		      		if( value ){

						$ctrl.$setValidity( 'phonenumber' , value.replace(/[^0-9]+/g,'').length === 10 );
						$ctrl.$setViewValue( formatLocal( 'US' , value ) );
						$ctrl.$render();

			      	}
	    		});

	    	}

	  	}

	}]);
	



	



	/**
	*
	*	DIRECTIVE: 	percentage
	*		- Format a Field into a Percentage
	*
	* 	USAGE:
	* 		[percentage]
	*
	**/
	app.directive('percentage', [  function($http){
  		return {

    		restrict: 'A',
    		
    		require: 'ngModel',

    		link: function($scope, $element, $attributes, $ctrl) { 

    			var value = null;

    			$element.on( 'focus' , function(){

    				var value = $ctrl.$$rawModelValue;

    				if( value ){

    					value = value.replace( '%' , '' );

	    				$ctrl.$setViewValue(
	    					value && !isNaN(value) ? value : ''
	    				);

	    				$ctrl.$render();

	    			}

    			});


    			$element.on( 'blur' , function(){

    				var value = $ctrl.$$rawModelValue;

    				if( value ){

    					var percent = value.match( /([0-9]{1,3}(\.[0-9]{1,2})?)/ );

	    				if( percent ){

	    					if( percent[0] > 100 ){

	    						value = '100';

	    					}else
	    					if( percent[0] <= 0 ){

	    						value = '0';

	    					}else{

	    						value = percent[0];

	    					}
	    					
		    				$ctrl.$setViewValue( value + '%' );

		    				$ctrl.$render();

		    			}

		    		}
    			});

	    	}

	  	}

	}]);
	



	



	/**
	*
	*	DIRECTIVE: 	percentage
	*		- Format a Field into a Percentage
	*
	* 	USAGE:
	* 		[percentage]
	*
	**/
	app.directive('currency', [  function($http){
  		return {

    		restrict: 'A',
    		
    		require: 'ngModel',

    		link: function($scope, $element, $attributes, $ctrl) { 

    			var value = null;

    			$element.on( 'focus' , function(){

    				var value = $ctrl.$$rawModelValue;

    				if( value ){

    					value = value.replace( /[^0-9.]+/g , '' );

	    				$ctrl.$setViewValue(
	    					value && !isNaN(value) ? value : ''
	    				);

	    				$ctrl.$render();

	    			}

    			});


    			$element.on( 'blur' , function(){

    				var value 	= $ctrl.$$rawModelValue;

    				if( value ){

    					value = value.replace( /[^0-9.]+/g , '' );

    					if( parseFloat( value ) < 0 ){

    						value = 0;

    					}
    					
	    				$ctrl.$setViewValue( '$' + parseFloat( value ).toFixed(2).replace( /(\d)(?=(\d{3})+(?!\d))/g , "$1,") );

	    				$ctrl.$render();

	    			}
    			});

	    	}

	  	}

	}]);
	



	



	/**
	*
	*	DIRECTIVE: 	number
	*		- Format a Field into a Number
	*
	* 	USAGE:
	* 		[percentage]
	*
	**/
	app.directive('number', [  function($http){
  		return {

    		restrict: 'A',
    		
    		require: 'ngModel',

    		link: function($scope, $element, $attributes, $ctrl) { 

    			var value = null;

    			$element.on( 'blur' , function(){

    				var value = $ctrl.$$rawModelValue;

    				if( value ){
	    			    var newVal  = value.replace( /[^0-9.]+/ , '' );
                        
                        newVal      = isNaN(parseFloat(newVal)) ? "" : parseFloat(newVal).toFixed( 2 );
                        
	    				$ctrl.$setViewValue(newVal );

	    				$ctrl.$render();

		    		}
    			});

	    	}

	  	}

	}]);
	



	



	/**
	*
	*	DIRECTIVE: 	compareTo
	*		- Compares two fields for similar values
	*
	*	Reference:
	* 		- http://odetocode.com/blogs/scott/archive/2014/10/13/confirm-password-validation-in-angularjs.aspx
	*
	* 	USAGE:
	* 		[compareTo='(Element)']
	*
	**/
	app.directive('compareTo', [function(){
		 return {

    		restrict: 'A',

	        require: 'ngModel',
	        
	        scope: {
	            comparison: '=compareTo'
	        },
	        
	        link: function($scope, $element, $attributes, $ctrl) {
	             
	            $ctrl.$validators.compareTo = function(modelValue) {
	                return modelValue == $scope.comparison;
	            };
	 
	            $scope.$watch('comparison', function() {
	                $ctrl.$validate();
	            });

	        }
	    };
	}]);
	



	



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
	app.directive('dateCompare', [function(){
		 return {

    		restrict: 'A',

	        require: 'ngModel',

	        scope: 	{

	        	'dateMin': 	'=?dateMin',

	        	'dateMax': 	'=?dateMax'

	        },
	        
	        link: function($scope, $element, $attributes, $ctrl) {



	        	//Validate the Minimum Date
	            $ctrl.$validators['date-min'] = function(modelValue) {

	            	if( $scope.dateMin ){

	            		var dates = [ moment(modelValue) , moment($scope.dateMin) ];

	            		if( dates[0].isValid() && dates[1].isValid() ){

			            	return ( dates[0].isValid() && dates[1].isValid() ) && dates[0].diff( dates[1] ) >= 0;

			            }

					}

					return true;

	            };


	            //Validate the Maximum Date
	            $ctrl.$validators['date-max'] = function(modelValue) {

	            	if( $scope.dateMax ){

	            		var dates = [ moment(modelValue) , moment($scope.dateMax) ];

	            		if( dates[0].isValid() && dates[1].isValid() ){

			            	return ( dates[0].isValid() && dates[1].isValid() ) && dates[0].diff( dates[1] ) <= 0;
	
						}

					}

					return true;

	            };


	            //Validate a Date
	            $ctrl.$validators['date-invalid'] = function(modelValue) {
	            	
	            	return moment(modelValue).isValid();

	            }
	 
	 			//Minimum Date Watcher
	            $scope.$watch('dateMin', function() {
	                $ctrl.$validate();
	            });

	            //Maximum Date Watcher
	            $scope.$watch('dateMax', function() {
	            	$ctrl.$validate();
	            });

	        }
	    };
	}]);
	



	



	/**
	*
	*	DIRECTIVE: 	selectWrapper
	*		- Creates a Reference between the Select Wrapper and the Select Element
	*
	*
	* 	USAGE:
	* 		[select-wrapper='(Expression)']
	*
	**/
	app.directive('selectWrapper', [function(){
		return {

    		restrict: 'A',

	        scope: 	{

	        	'selectWrapper': 	'=',

	        },
	        
	        link: function($scope, $element, $attributes, $ctrl) {

	        	//Get the Curent Class Name
	        	var current = $element[0].className;

	        	$scope.$watch(function(){

	        		//Watch the Child Class Name
	        		return $element[0].firstElementChild.className ;

	        	}, function( scopeClass ){

	        		//Update the Current Class Name
	        		$element[0].className = ( current.length > 0 ? current + ' ' : '' ) + scopeClass ;

	        	});

	        }

	    }
	}]);



})();