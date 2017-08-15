(function(){

	'use strict';

	var app = angular.module('System.Controllers');

	//The App Controller
	app.controller('StoreDashboardController', [ '$scope' , '$http' , 'User' , 'Store' , function( $scope , $http , User , Store ){
		User.ready(function(){

			$scope.loading = true;

			Store.all(function(stores){
				if( stores.length > 0 ){

					//Prepare the List
					var list 	= [],
						bounds 	= new google.maps.LatLngBounds(),
						windows = [];

					//Get the Stores
					for( var i=0; i < stores.length; i++ ){

						//Add the Point
						list.push(
							new google.maps.LatLng( parseFloat( stores[i].latitude ), parseFloat( stores[i].longitude ) )
						)

						//Extend the Bounds
						bounds.extend( list[ list.length - 1 ] );

						//Add the Info Window
				    	windows.push(
				    		new google.maps.InfoWindow({
					    		content: 
					    			'<h4 class="color000 font2">' + 
					    			
					    				stores[i].name + 
					    			
					    			'</h4>' +
					    			'<hr />' +
					    			'<address>' + [

							    			stores[i].address + ', ',
							    		
							    			stores[i].city + ', ' + stores[i].province ,

							    			stores[i].postalcode

							    		].join('<br />') +
					    			'</address>' +
					    			'<hr />' +
					    			'<div class="phone">' +

					    				'<i class="fa fa-phone-square"></i> ' + 
					    				stores[i].phone +

					    			'</div>'
					    	})
					    );

					}

					//Prepare the Map Data
				    var map = {

				    	//The Map Object
				    	obj: 		null,
				    	
				    	//The Map Points
				    	points: 	list,

				    	//Esthetics for the Map
			    		design: 	[
						    { 	'stylers': [
						    		{ 'visibility': 'off' 	}, 
						    		{ 'saturation': -100 	}
						    	] 
						    }, 
						    { 
						    	'featureType': 	'road',
						        'stylers': 		[ 
						        	{ 'visibility': 'on' 		},
						        	{ 'color': 		'#ffffff' 	}
						        ]
						    }, 
						    {
						        'featureType': 	'road.arterial',
					            'stylers': 	[
						            { 'visibility': 'on' 		}, 
						            { 'color': 		'#fee379' 	},
						            { 'saturation':	-100		}
					         	]
						    }, 
						    {
						        'featureType': 'road.highway',
					            'stylers': [
						            { 'visibility': 'on' 		}, 
						            { 'color': 		'#fee379' 	},
						            { 'saturation':	-100 		}
					           	]
						    }, 
						    {
						        'featureType': 'landscape',
					            'stylers': [
						            { 'visibility': 'on' 		}, 
						            { 'color': 		'#f3f4f4' 	},
						            { 'saturation':	-100 		}
					            ]
						    }, 
						    {
						        'featureType': 'water',
					            'stylers': [
						            { 'visibility': 'on' 		}, 
						            { 'color': 		'#7fc8ed' 	},
						            { 'saturation': -100 		}
					            ]
						    }, 
						    {}, 
						    {
						        'featureType': 	'road',
						        'elementType': 	'labels',
						        'stylers': 		[
						        	{ 'visibility': 'off'	},
						        	{ 'saturation': -100 	}
						        ]
						    }, 
						    {
						        'featureType': 	'poi.park',
					            'elementType': 	'geometry.fill',
					            'stylers': 		[
						            { 'visibility': 'on' 	}, 
						            { 'color': '#83cead' 	},
						            { 'saturation': -100 	}
					            ]
						    }, 
						    {
						        'elementType': 'labels',
						        'stylers': [
						        	{ 'visibility': 'off' },
						        	{ 'saturation':-100}
						        ]
						    }, 
						    {
						        'featureType': 	'landscape.man_made',
					            'elementType': 	'geometry',
					            'stylers': 		[
					            	{ 'weight': 	0.9 	}, 
					            	{ 'visibility': 'off' 	},
					            	{ 'saturation':	-100 	}
					            ]
						    }
						],

						//The Map Settings
						settings: 	{
					        zoom: 				13,
					        center: 			bounds.getCenter(),
					        mapTypeId: 			google.maps.MapTypeId.ROADMAP,
					        disableDefaultUI: 	true
					    }
				    };

					$scope.loading = false;

				    window.setTimeout(function(){

					    //Setup the Map
					    map.obj = new google.maps.Map( jQuery('map')[0], map.settings);
					    map.obj.setOptions({ styles: map.design });
					    map.obj.fitBounds( bounds );

					    google.maps.event.addDomListener(window, 'resize', function() {
						    map.obj.setCenter( bounds.getCenter() );
						});

					    //Add the Markers
					    for( var i=0; i < map.points.length; i++ ){
					    	(function( index ){

						    	//Add the Marker
							    var marker = new google.maps.Marker({
							        position: 	map.points[ index ],
							        map: 		map.obj,
							        icon: 		{
								        url: 		'/public/assets/images/googlemap/marker.png',
								        origin: 	new google.maps.Point(0, 0),
								        anchor: 	new google.maps.Point(12, 59)
								    },
							        shadow: 	{
								        url: 		'/public/assets/images/googlemap/shadow.png',
								        origin: 	new google.maps.Point(0, 0),
								        anchor: 	new google.maps.Point(-2, 36)
								    }
							    });

							    //The Window Popup
							    marker.addListener('click', function(){

							    	windows[ index ].open( map.obj , marker );

							    });

							})( i );
						}

					},500);



				}

				$scope.list = stores;



			});

		});
	}]);


})();