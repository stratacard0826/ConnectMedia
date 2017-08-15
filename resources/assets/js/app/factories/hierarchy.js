(function(){
	
	var app = angular.module('System.Services');
	
	app.factory('Hierarchy' , [function(){


		//Save the Object
		var obj 	  = this,

		arr 	 	  = [];



	    /**
	    *
	    *   create
	    *       - Creates the Hierarchy
	    *
	    *   Params:
	    * 		- data: 			(Object) The Function Data 		
	    * 			- arr: 			(Array) The Array to build the Hierarchy List off of
	    * 			- position: 	(Array) The Current Position of the Array
	    *       	- depth:       	(INT) How deep the array hierarchy has gone (Default: 0)
	    * 			- parents: 		(Array) The List of Parents above the Current Item
	    * 			- each: 		(Callback) The Callback Function that runs for each item
	    *
	    *   Returns (Object):
	    *       1. The Hierarchy Array
	    *
	    **/
		this.create 	= function( data ){

			//Reset the List
			if(!data.depth){

				data.response = [];

				data.parents  = [];

			}

			data.depth = data.depth || 0;

			data.position = data.position || [1];

			angular.forEach(data.data,function( value , key ){
				if( typeof data.each === 'undefined' || data.each(value, data.parents) ){

					for( var log='', i=0; i<data.depth; i++) log = log + '-' ;

					data.response.push({
						id: 			value.id,
						parent_id: 		value.parent_id,
						name: 			value.name,
						description: 	value.description,
						depth: 			data.depth,
						position: 		data.position.join('.'),
						tree: 			log + ' ' + value.name
					});

					if(typeof value.children !== 'undefined' ){

						data.parents.push( value.id );

						data.position.push( 1 );

						obj.create(angular.extend({} , data , { 'depth': data.depth+1 , 'data': value.children }));

					}

					data.position[ data.position.length - 1 ]++;

				}
			});

			data.position.pop();

			data.parents.pop();

			return data.response;
		
		};




		//Return the Object
		return this;

	}]);

})();