$(document).ready(function(){
		$("#form").submit(function(){
				$('#loader-login').show();
				$("button[type='submit']").attr("disabled","disabled");
				updateTypeLocation($(this).serialize());
		});
	});
	function updateTypeLocation(form){
		var data = form;
		$.post(addressUpdateTypeLocations, data, function(send){
			if(send.state){
				$("#loader-login").fadeOut('fast');
				showMessage(send.type);
				$("button[type='submit']").removeAttr("disabled");
			}
		},'JSON');
	}