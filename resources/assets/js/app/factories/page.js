(function(){
	
	var app = angular.module('System.Services');
	
	app.factory('Page' , [ '$rootScope' , '$location' , '$state' , 'Loader' , function( $rootScope , $location , $state , Loader ){


		//An Extension of the Loader class
		this.loading = {
			'start': 	Loader.start,
			'end': 		Loader.end
		}




		/**
	    *
	    *   this.is
	    *       -  Compares the current Path with the passed Regex
	    *
	    *   Params:
	    * 		n/a
	    *
	    *	Returns:
	    * 		(Bool) True or False
	    *
	    **/
		this.is = function( regex ){

			return $location.path().match( regex );

		}



		/**
	    *
	    *   $scope.open
	    *       - On Click, Run the Page Opening function
	    *
	    *	SOURCE:
	    * 		factories/page.js
	    *
	    *   Params:
	    * 		- destination: 			(String) The Route to Open
	    *
	    **/
		this.open = function( destination ){
			//Clear all Unnecessary AJAX Queues
			Loader.clear();

			//Go to Location
			$location.url( destination );

		}



		/**
	    *
	    *   $scope.back
	    *       - On Click, Open the Previous Page
	    *
	    *	SOURCE:
	    * 		factories/page.js
	    *
	    *   Params:
	    * 		- compare: 				(REGEX) The Regex to compare the previous destination to
	    * 		- fallback: 			(String) The fallback Destination
	    *
	    **/
		this.back = function( compare , fallback ){

			//Only delete it if we have more than 1 page
			if( $rootScope.history.length > 1 ){

				//Get the Previous Page
				$rootScope.history.pop();

				//Get the Last Page
				var $previous = $rootScope.history[ $rootScope.history.length - 1 ];

				//Build the Regex
				var regex 	  = new RegExp( compare );

				//Open the Previous Page or Fallback Page
				this.open( 
					( $previous.match( compare ) ? $previous : fallback )
				);

				return;

			}

			//Just open the Fallback
			this.open( fallback );

		}






		/**
	    *
	    *   this.hasError
	    *       -  Checks the Form for Errors Passed
	    *
	    * 	Requirements:
	    *		ng-messages MUST set form.error[key] = Bool 
	    * 		I.E. ng-show="form.error.email = ( form.email.$dirty && form.email.$invalid )"		
	    *
	    *   Params:
	    * 		form: 		(Object) The NG Form object to Compare
	    *
	    *	Returns:
	    * 		(Bool) True or False
	    *
	    **/
		this.hasError = function( form ){
			if( form && form.error ){

				for( var key in form.error ){

					if( form.error[ key ] ){

						return true; 
					}

				}

			}

			return false;
		};






		/**
	    *
	    *   this.error
	    *       -  Show an Error Page
	    *
	    *   Params:
	    * 		error: 		(INT) The type of Error to show
	    *
	    *	Returns:
	    * 		n/a
	    *
	    **/
		this.error = function( error ){

			//Show 404 Page
			$state.go('404',null, {location: false });

		};






		//Return the Object
		return this;

	}]);

})();