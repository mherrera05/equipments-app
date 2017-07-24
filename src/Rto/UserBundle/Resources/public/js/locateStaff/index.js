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
		google.maps.event.addListener(marker, 'click', function() {
			$("#loader-information").show();
			loadDetails(marker.id);
			 var content = '<div>'+title+'</div>';
			 if(count != ''){
			 	if(count > 1){
			 		content += '<div>'+count+' operators</div>';
			 	}else{
			 		content += '<div>'+count+' operator</div>';
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
		google.maps.event.addDomListener(window, 'load', initialize);
	}); 
	function loadDetails(id){
		var data = 'id='+id;
		$.post(addressLoadDetails, data, function(send){
			if(send.state){
				$("section.content > section > section:nth-child(2) > ul").html('');
				$("section.content > section > section:nth-child(2) > ul").html('<li class="title">Point selected</div>');
				for(var i = 0; i < send.users.length; i++){
					$("section.content > section > section:nth-child(2) > ul").append('<li class="content-list"><img src="'+route+send.users[i].picture+'"><span>'+send.users[i].userId+' - '+send.users[i].name+' '+send.users[i].lastName+'</span> </li>');
				}
				if(send.type != ''){
					showMessage(send.type);
				}else{
					$(".alert").hide();
				}
				$("#loader-information").hide();
			}
		}, 'JSON');
	}$(document).ready(function(){$("#form").submit(function(){validation()&&($("#loader-login").show(),$("button[type='submit']").attr("disabled","disabled"),updateUser($(this).serialize()))});$("#email").change(function(){searchEmail($(this).val(),$("#id").val())});$("#user-id").change(function(){searchUserId($(this).val(),$("#id").val())})});
function updateUser(b){$.post(addressUpdateUser,b,function(a){a.state&&($("#loader-login").fadeOut("fast"),showMessage(a.type),$("button[type='submit']").removeAttr("disabled"))},"JSON")}function searchEmail(b,a){$.post(addressSearchEmail,"email="+b+"&id="+a,function(a){a.state?$("#email").removeClass("error"):$("#email").addClass("error")},"JSON")}
function searchUserId(b,a){$.post(addressSearchUserId,"userid="+b+"&id="+a,function(a){a.state?$("#user-id").removeClass("error"):$("#user-id").addClass("error")},"JSON")}function validation(){return $("#email").hasClass("error")&&$("#user-id").hasClass("error")?!1:!0};