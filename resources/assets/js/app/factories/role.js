(function(){
	
	var app = angular.module('System.Services');
	
	app.factory('Role' , [ 'Loader' , function( Loader ){


		//Save the Object
		var obj   = this;



	    /**
	    *
	    *   all
	    *       - Loads the Role
	    *
	    *   Params:
	    * 		- callback: 	(Function) The Callback Function
	    * 		- roleid: 		(INT) The Role ID to Lookup
	    *
	    *   Returns (JSON):
	    *       1. The Role Data
	    *
	    **/
		this.get = function( callback , roleid ){
			Loader.get( ':api/role/' + roleid , function( response ){
				if( response.data ){

					//Store the Data
					callback( response.data );

				}
			});
		};



	    /**
	    *
	    *   all
	    *       - Loads the Role
	    *
	    *   Params:
	    * 		- callback: 	(Function) The Callback Function
	    * 		- limit: 		(INT) The Amount of Rows to return (Default: 15, Max: 100)
	    * 		- page: 		(INT) The Page to Start From (Default: 1)
	    *
	    *   Returns (JSON):
	    *       1. All of the Roles Data Paginated (If Page || Limit are passed) 	* Note: Requires "Roles" Permission *
	    * 		2. All of the Roles with only ID & Name 							* Note: No Permission Required *
	    *
	    **/
		this.all = function( callback , page , limit ){
			Loader.get( ':api/roles' + ( limit ? '/' + limit : '' ) + ( page ? '/' + page : '' ) , function( response ){
				if( response.data ){

					//Store the Data
					callback( response.data );

				}
			});
		};










		this.permissions = {




		    /**
		    *
		    *   get
		    *       - Loads the permissions for a single role
		    *
		    *   Params:
		    * 		- callback: 	(Function) The Callback Function
	    	* 		- roleid: 		(INT) The Role ID to Lookup
		    *
		    *   Returns (JSON):
		    *       1. The Role
		    *
		    **/
			get: function( callback , roleid ){
				Loader.get( ':api/role/' + roleid + '/permissions' , function( response ){
					if( response.data ){

						//Store the Data
						callback( response.data );

					}
				});
			},





		    /**
		    *
		    *   all
		    *       - Loads all of the Permissions
		    *
		    *   Params:
		    * 		- callback: 	(Function) The Callback Function
		    *
		    *   Returns (JSON):
		    *       1. The Role
		    *
		    **/
			all: function( callback ){
				Loader.get( ':api/role/permissions' , function( response ){
					if( response.data ){

						//Store the Data
						callback( response.data );

					}
				});
			},



		}





		//Return the Object
		return this;

	}]);

})();