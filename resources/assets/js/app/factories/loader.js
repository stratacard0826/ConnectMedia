(function(){
	
	var app = angular.module('System.Services');
	
	app.factory('Loader' , [ '$rootScope' , '$http' , '$q' , function( $rootScope , $http , $q ){


		//The Request Queue
		var queue = [];









		/**
	    *
	    *   this.remove
	    *       -  Removes the AJAX request to the Queue
	    *
	    *   Params:
	    * 		url: 		(String) The URL to Request
	    *
	    *	Returns:
	    * 		n/a
	    *
	    **/
		this.remove 	= function(url){
			for( var i=0; i < queue.length; i++ ){
				if( queue[i].url == url || queue[i].url.match( url ) ){

					queue[i].canceller.resolve();
					
					queue.splice( i , 1 );

					break;

				}
			}

		}









		/**
	    *
	    *   this.clear
	    *       -  Removes all outstanding AJAX requests from the Queue
	    *
	    *   Params:
	    * 		n/a
	    *
	    *	Returns:
	    * 		n/a
	    *
	    **/
		this.clear 	= function(){
			angular.forEach(queue, function(item){

				item.canceller.resolve();

			});
			queue.length = 0;
		}







		/**
	    *
	    *   this.start
	    *       -  Show the Loading Message
	    *
	    *   Params:
	    * 		n/a
	    *
	    *	Returns:
	    * 		n/a
	    *
	    **/
		this.start = function(){
			
	        $rootScope.loading = true;

		};










		/**
	    *
	    *   this.end
	    *       -  Remove the Loading Message
	    *
	    *   Params:
	    * 		n/a
	    *
	    *	Returns:
	    * 		n/a
	    *
	    **/
		this.end = function(){

	        $rootScope.loading = false;

		};











		/**
	    *
	    *   this.load
	    *       -  Sends the (GET) AJAX Request
	    *
	    *   Params:
	    * 		url: 		(String) The URL to Request
	    * 		callback: 	(Function) The Callback Function
	    *
	    *	Returns (Object):
	    * 		The AJAX Response
	    *
	    **/
	    this.get 	= function(url , callback){

	    	var canceller = $q.defer();

	    	queue.push({
	    		'url': 			url,
	    		'canceller': 	canceller
	    	});

			var request = $http.get( url , { timeout: canceller.promise } );

			if( callback ){

				request.then( callback );

			}

			request.finally(function( response ){

				//Store the Data
				callback( response.data );

			});

	    };







		//Return the Object
		return this;

	}]);

})();