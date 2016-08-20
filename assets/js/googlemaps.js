
			var map; // Global declaration of the map
			var lat_longs_map = new Array();
			var markers_map = new Array();
            var iw_map;
			var directionsDisplay = new google.maps.DirectionsRenderer();
			var directionsService = new google.maps.DirectionsService();
			var placesService;
			var placesAutocomplete;
			
			iw_map = new google.maps.InfoWindow();
				
				 function initialize_map() {
				
				var myLatlng = new google.maps.LatLng(48.669026, 19.699024);
				var myOptions = {
			  		zoom: 8,
					center: myLatlng,
			  		mapTypeId: google.maps.MapTypeId.ROADMAP}
				map = new google.maps.Map(document.getElementById("map"), myOptions);
				directionsDisplay.setMap(map);
			directionsDisplay.setPanel(document.getElementById("directionsDiv"));
			var autocompleteOptions = {
					}
				var autocompleteInput = document.getElementById('myPlaceTextBox');
				var autocompleteInput2 = document.getElementById('myPlaceTextBox2');
				
				placesAutocomplete = new google.maps.places.Autocomplete(autocompleteInput, autocompleteOptions);
				placesAutocomplete2 = new google.maps.places.Autocomplete(autocompleteInput2, autocompleteOptions);
				placesAutocomplete.bindTo('bounds', map);
					
			var myLatlng = new google.maps.LatLng(48.736277, 19.1461917);	
			var markerOptions = {
				map: map,
				position: myLatlng,
				draggable: true,
				animation:  google.maps.Animation.DROP		
			};
			marker_0 = createMarker_map(markerOptions);
			
			var myLatlng = new google.maps.LatLng(48.736277, 19.1461917);	
			var markerOptions = {
				map: map,
				position: myLatlng,
				draggable: true,
				animation:  google.maps.Animation.DROP		
			};
			marker_1 = createMarker_map(markerOptions);
			
			var myLatlng = new google.maps.LatLng(48.8060729, 19.6438178);
				var marker_icon = {
					url: "assets/pickup_camper.png"};
				
			var markerOptions = {
				map: map,
				position: myLatlng,
				icon: marker_icon,
				animation:  google.maps.Animation.DROP		
			};
			marker_2 = createMarker_map(markerOptions);
			
			marker_2.set("content", "Meno: <br></br>Priezvisko:  <br></br> Pozicia: Brezno, Slovakia <br></br>Telfonne cislo: <br></br>Pocet miest: 0<br></br>SPZ: ");
			
			google.maps.event.addListener(marker_2, "click", function(event) {
				iw_map.setContent(this.get("content"));
				iw_map.open(map, this);
			
			});
			
				var circleCenter = new google.maps.LatLng(48.8060729, 19.6438178)
				lat_longs_map.push(new google.maps.LatLng(48.8060729, 19.6438178));
			
				var circleOptions = {
					strokeColor: "0.8",
					strokeOpacity: 0.8,
					strokeWeight: 2,
					fillColor: "#FF0000",
					fillOpacity: 0.15,
					map: map,
					center: circleCenter,
					radius: 50000
				};
				var circle_0 = new google.maps.Circle(circleOptions);
			
				calcRoute('ruzomberok, slovakia', 'bratislava, slovakia'); 
				
			
			}
		
		
		function createMarker_map(markerOptions) {
			var marker = new google.maps.Marker(markerOptions);
			markers_map.push(marker);
			lat_longs_map.push(marker.getPosition());
			return marker;
		}
		function calcRoute(start, end) {

			var request = {
			    	origin:start,
			    	destination:end,
			    	travelMode: google.maps.TravelMode.DRIVING
			    	, waypoints: [{ location: "martin", stopover: true}]
			};
			  	directionsService.route(request, function(response, status) {
			    	if (status == google.maps.DirectionsStatus.OK) {
			      		directionsDisplay.setDirections(response);
			    	}else{
			    		switch (status) { 	
			    			case "NOT_FOUND": { alert("Either the start location or destination were not recognised"); break }
			    			case "ZERO_RESULTS": { alert("No route could be found between the start location and destination"); break }
			    			case "MAX_WAYPOINTS_EXCEEDED": { alert("Maximum waypoints exceeded. Maximum of 8 allowed"); break }
			    			case "INVALID_REQUEST": { alert("Invalid request made for obtaining directions"); break }
			    			case "OVER_QUERY_LIMIT": { alert("This webpage has sent too many requests recently. Please try again later"); break }
			    			case "REQUEST_DENIED": { alert("This webpage is not allowed to request directions"); break }
			    			case "UNKNOWN_ERROR": { alert("Unknown error with the server. Please try again later"); break }
			    		}
			    	}
			  	});
			}
			function placesCallback(results, status) {
				if (status == google.maps.places.PlacesServiceStatus.OK) {
					for (var i = 0; i < results.length; i++) {
						
						var place = results[i];
					
						var placeLoc = place.geometry.location;
						var placePosition = new google.maps.LatLng(placeLoc.lat(), placeLoc.lng());
						var markerOptions = {
				 			map: map,
				        	position: placePosition
				      	};
				      	var marker = createMarker_map(markerOptions);
				      	marker.set("content", place.name);
				      	google.maps.event.addListener(marker, "click", function() {
				        	iw_map.setContent(this.get("content"));
				        	iw_map.open(map, this);
				      	});
				      	
				      	lat_longs_map.push(placePosition);
					
					}
					
				}
			}
			
			google.maps.event.addDomListener(window, "load", initialize_map);
			