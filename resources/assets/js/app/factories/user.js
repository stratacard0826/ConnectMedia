(function(){
	
	var app = angular.module('System.Services');
	
	app.factory('User' , [ '$http' , '$rootScope' , 'Loader' , 'Authorization' , function( $http , $rootScope , Loader , Authorization ){


		//Save the Object
		var obj   = this,


		//Is the User Factory Ready?
		ready 	  = false;



	    /**
	    *
	    *   get
	    *       - Loads the User
	    *
	    *   Params:
	    * 		- callback: 	(Function) The Callback Function
	    *       - userid:       (INT) The UserID to Lookup (Default: NULL)
	    *
	    *   Returns (JSON):
	    *       1. The User Looked up (If userid was passed)
	    *       2. The Current User Session Data (If userid was not passed)
	    *       3. Null
	    *
	    **/
		this.get = function( callback , userid ){
			Loader.get( ':api/user/' + ( parseInt(userid) || '' ) , function( response ){
				if( response.data ){

					//Store the Data
					callback( response.data );

				}
			});
		};




	    /**
	    *
	    *   all
	    *       - Load all of the Users
	    *
	    *   Params:
	    * 		- callback: 	(Function) The Callback Function
	    * 		- limit: 		(INT) The Amount of Rows to return (Default: 15, Max: 100)
	    * 		- page: 		(INT) The Page to Start From (Default: 1)
	    *
	    *   Returns (JSON):
	    *       1. A List of all of the Users
	    *
	    **/
		this.all = function( callback , page , limit ){
			Loader.get( ':api/users' + ( limit ? '/' + limit : '' ) + ( page ? '/' + page : '' ) , function( response ){
				if( response.data ){

					callback( response.data );

				}
			});
		};






	    /**
	    *
	    *   roles
	    *       - Loads the User Roles
	    *
	    *   Params:
	    * 		- callback: 	(Function) The Callback Function
	    *       - userid:       (INT) The UserID to Lookup (Default: Current User ID )
	    *
	    *   Returns (JSON):
	    *       1. The Current Users Roles
	    *
	    **/
		this.roles = function( callback , userid ){
			Loader.get( ':api/user/roles/' + ( parseInt(userid) || '' ) , function( response ){
				if( response.data ){

					//Store the Data
					callback( response.data );

				}
			});
		};






	    /**
	    *
	    *   customPermissions
	    *       - Loads the User Permissions NOT in the Role
	    *
	    *   Params:
	    * 		- callback: 	(Function) The Callback Function
	    *       - userid:       (INT) The UserID to Lookup (Default: Current User ID )
	    *
	    *   Returns (JSON):
	    *       1. The Current Users Permissions
	    *
	    **/
		this.customPermissions = function( callback , userid ){
			Loader.get( ':api/user/permissions/custom/' + ( parseInt(userid) || '' ) , function( response ){
				if( response.data ){

					//Store the Data
					callback( response.data );

				}
			});
		};






	    /**
	    *
	    *   roles
	    *       - Loads the User Stores
	    *
	    *   Params:
	    * 		- callback: 	(Function) The Callback Function
	    *       - userid:       (INT) The UserID to Lookup (Default: Current User ID )
	    *
	    *   Returns (JSON):
	    *       1. The Current Users Stores
	    *
	    **/
		this.stores = function( callback , userid ){
			Loader.get( ':api/user/stores/' + ( parseInt(userid) || '' ) , function( response ){
				if( response.data ){

					//Store the Data
					callback( response.data );

				}
			});
		};





	    /**
	    *
	    *   hasPermission
	    *       - Check if the current user has the correct permissions
	    *
	    *   Params:
	    * 		- permission: 		(String | Array) The Permission to Check - If Array is passed it does an OR evaluation (Default: NULL)
	    *
	    *   Returns (BOOL):
	    *       TRUE if user has permission
	    *
	    **/
		this.hasPermission = function( permissions ){

			if( obj.permissions &&  obj.permissions.list ){

				if( typeof permissions == 'object' ){

					for( var i=0; i < permissions.length; i++ ){

						if( !obj.hasPermission( permissions[i] ) ){

							return false;

						}

					}

					return true;
				
				}else{

					return $.inArray( String(permissions) , obj.permissions.list ) > -1 ;

				}

			}

			return false;
		
		};











	    /**
	    *
	    *   ready
	    *       - Checks if the Current User's Session information has loaded
	    *
	    *   Params:
	    * 		- callback: 	(Function) The Callback Function
	    *
	    *   Returns (JSON):
	    *       Returns the loaded User Data
	    *
	    **/
		this.ready = function( callback ){
			window.setTimeout(function(){
				if( ready ){

					//Return the User Data
					callback( obj );

				}else{

					//Rereun the Ready function
					obj.ready( callback );

				}
			},50);
		};











	    //Load the Permissions
		$http.get( ':api/user/permissions' ).then(function( response ){
			if( response ){

				for( var i=0, list=[]; i < response.data.length; i++ ){
					list.push( response.data[i].slug );
				}

				obj.permissions = {
					'data': 	response.data,
					'list':  	list
				};

				//Set the User as Authorized
				Authorization.authorized = true;

				//Set the User as Ready
				ready = true;

			}
		});




		//Return the Object
		return this;

	}]);

})();