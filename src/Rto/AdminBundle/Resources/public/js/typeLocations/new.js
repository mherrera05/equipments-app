$(document).ready(function(){
		$("#form").submit(function(){
				$('#loader-login').show();
				$("button[type='submit']").attr("disabled","disabled");
				createTypeLocation($(this).serialize());
		});
	});
	function createTypeLocation(form){
		var data = form;
		$.post(addressCreateTypeLocation, data, function(send){
			if(send.state){
				$("#loader-login").fadeOut('fast');
				showMessage(send.type);
				$("#form")[0].reset();
				$("button[type='submit']").removeAttr("disabled");
			}
		},'JSON');
	}