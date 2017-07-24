$(document).ready(function(){
		$("#form").submit(function(){
				$('#loader-login').show();
				$("button[type='submit']").attr("disabled","disabled");
				createLocation($(this).serialize());
		});
		google.maps.event.addDomListener(window, 'load', initialize);
	});
	function createLocation(form){
		var data = form;
		$.post(addressCreateLocation, data, function(send){
			if(send.state){
				$("#loader-login").fadeOut('fast');
				showMessage(send.type);
				$("#form")[0].reset();
				$("button[type='submit']").removeAttr("disabled");
			}
		},'JSON');
	}
	
	function initialize() {
	  mapOptions = {zoom: 6};
	  map 	     = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);
						
	  // Try HTML5 geolocation
	  if(navigator.geolocation) {
	    	navigator.geolocation.getCurrentPosition(function(position) {
	    		$("#latitude").val(position.coords.latitude);
	    		$("#length").val(position.coords.longitude);
	      		var pos 		= new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
	      		addMarker(pos,'Current Position',map);
	      		
	      				map.setCenter(pos);
	    			}, function() {
	      				handleNoGeolocation(true);
	    				});
	  	} else {
	    	// Browser doesn't support Geolocation
	   		 handleNoGeolocation(false);
	  	}
	}
	
	function handleNoGeolocation(errorFlag) {
	  if (errorFlag) {
	    var content = 'Error: The Geolocation service failed.';
	  } else {
	    var content = 'Error: Your browser doesn\'t support geolocation.';
	  }
	
	  var options = {
	  	map: map,
	  	position: new google.maps.LatLng(60, 105),
	  	content: content
	  	};
	  	
	  addMarker(new google.maps.LatLng(60, 105),'Current Position',map);
	}
	function addMarker(position,title,map){
		var marker     = new google.maps.Marker({
				position:position,
				draggable:true, 
				map: map, 
				title: title
			});
			
		google.maps.event.addListener(marker,'dragend',function(event) {
        $("#latitude").val(event.latLng.lat());
        $("#length").val(event.latLng.lng());
    });
	}