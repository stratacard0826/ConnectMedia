(function(){
	
	var app = angular.module('System.Services');
	
	app.factory('Store' , [ 'Loader' , function( Loader ){


		//Save the Object
		var obj   = this;





	    /**
	    *
	    *   all
	    *       - Loads the Roles
	    *
	    *   Params:
	    * 		- callback: 	(Function) The Callback Function
	    * 		- storeid: 		(INT) The Role ID to Lookup
	    *
	    *   Returns (JSON):
	    *       1. The Role Data
	    *
	    **/
		this.get = function( callback , storeid ){
			Loader.get( ':api/store/' + storeid , function( response ){
				if( response.data ){

					//Store the Data
					callback( response.data );

				}
			});
		};







	    /**
	    *
	    *   all
	    *       - Loads all of the Stores
	    *
	    *   Params:
	    * 		- callback: 	(Function) The Callback Function
	    * 		- limit: 		(INT) The Amount of Rows to return (Default: 15, Max: 100)
	    * 		- page: 		(INT) The Page to Start From (Default: 1)
	    *
	    *   Returns (JSON):
	    *       1. All of the Stores Data Paginated (If Page || Limit are passed) 	* Note: Requires "Stores" Permission *
	    * 		2. All of the Stores with only ID & Name 							* Note: No Permission Required *
	    *
	    **/
		this.all = function( callback , page , limit ){
			Loader.get( ':api/stores' + ( limit ? '/' + limit : '' ) + ( page ? '/' + page : '' ) , function( response ){
				if( response.data ){

					//Store the Data
					callback( response.data );

				}
			});
		};







	    /**
	    *
	    *   all
	    *       - Loads all of the Users in a Store
	    *
	    *   Params:
	    * 		- callback: 	(Function) The Callback Function
	    * 		- storeid: 		(INT) The Store ID to Lookup
	    *
	    *   Returns (JSON):
	    *       1. A List of all the Users associated to that store
	    *
	    **/
		this.users = function( callback , storeid ){
			Loader.get( ':api/store/' + storeid + '/users', function( response ){

				//Store the Data
				callback( response.data );

			});
		};








		//Return the Object
		return this;

	}]);

})();