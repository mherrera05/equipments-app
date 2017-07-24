function handleNoGeolocation(errorFlag) {
		if (errorFlag) {
			var content = 'Error: The Geolocation service failed.';
		} else {
			var content = 'Error: Your browser doesn\'t support geolocation.';
		}

		var options = {
			map : map,
			position : new google.maps.LatLng(60, 105),
			content : content
		};
		addMarker(new google.maps.LatLng(60, 105), 'Current Position', map, '');
	}

	function addMarker(position, title, map, count, id) {
			var marker = new google.maps.Marker({
			position : position,
			draggable : false,
			map : map,
			title : title,
			id: id
			});
		markers.push(marker);
		google.maps.event.addListener(marker, 'click', function() {
			$("#loader-information").show();
			loadDetails(marker.id);
			 var content = '<div>'+title+'</div>';
			 if(count != ''){
			 	if(count > 1){
			 		content += '<div>'+count+' equipments</div>';
			 	}else{
			 		content += '<div>'+count+' equipment</div>';
			 	}
			 }	
			 var infowindow = new google.maps.InfoWindow({
							      content: content
							  });
			infowindow.open(map,marker);
		});
	}
	function initialize() {
		var position = new google.maps.LatLng(10.525043645645093, -66.88489590572499);
		mapOptions = {
			zoom : 6,
			center : position
		};
		map = new google.maps.Map(document.getElementById('map-canvas-locate'), mapOptions);

		// Try HTML5 geolocation
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function(position) {
				$("#latitude").val(position.coords.latitude);
				$("#length").val(position.coords.longitude);
				var pos = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
				addMarker(pos, 'Current Position', map,'');

				map.setCenter(pos);
			}, function() {
				handleNoGeolocation(true);
			});
		} else {
			// Browser doesn't support Geolocation
			handleNoGeolocation(false);
		}
		for(var i = 0; i < locations.length; i++ ){
			addMarker(new google.maps.LatLng(locations[i][1], locations[i][2]), locations[i][0], map, locations[i][3], locations[i][4]);
		}
	}
	$(document).ready(function() {
		//google.maps.event.addDomListener(window, 'load', initialize);
		$("#type").change(function(){
			deleteMarkers();
			$("#loader-select").show();
			if($(this).val() != 0){
				searchLocationTypeEquipments($(this).val());				
			}else{
				for(var i = 0; i < locations.length; i++ ){
					addMarker(new google.maps.LatLng(locations[i][1], locations[i][2]), locations[i][0], map, locations[i][3], locations[i][4]);
				}
				$("#loader-select").fadeOut('fast');
			}
		});
	}); 
	function loadDetails(id){
		var data = 'id='+id;
		$.post(addressLoadDetails, data, function(send){
			if(send.state){
				$("section.content > section > section:nth-child(2) > ul").html('');
				$("section.content > section > section:nth-child(2) > ul").html('<li class="title">Point selected</div>');
				for(var i = 0; i < send.equipments.length; i++){
					$("section.content > section > section:nth-child(2) > ul").append('<li class="content-list">'+send.equipments[i].serial+' - '+send.equipments[i].brand+' '+send.equipments[i].model+' </li>');
				}
				if(send.type != ''){
					showMessage(send.type);
				}else{
					$(".alert").hide();
				}
				$("#loader-information").hide();
			}
		}, 'JSON');
	}
	function setAllMap(map) {
	  for (var i = 0; i < markers.length; i++) {
	    markers[i].setMap(map);
	  }
	}
	function clearMarkers() {
	  setAllMap(null);
	}
	function deleteMarkers() {
	  clearMarkers();
	  markers = [];
	}
	function searchLocationTypeEquipments(type){
		var data = 'type='+type;
		$.post(addressSearchLocationTypeEquipments, data, function(send){
			if(send.state){
				var locations = send.data;
				for(var i = 0; i < locations.length; i++ ){
					addMarker(new google.maps.LatLng(locations[i].latitude, locations[i].length), locations[i].locationName+"-"+locations[i].projectName, map, locations[i].count, locations[i].locationId);
				}
				$("#loader-select").fadeOut('fast');
			}
			showMessage(send.type);
		}, 'JSON');
	}