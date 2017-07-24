$(document).ready(function(){
		$("#form").submit(function(){
				$('#loader-login').show();
				$("button[type='submit']").attr("disabled","disabled");
				updateModel($(this).serialize());
		});
	});
	function updateModel(form){
		var data = form;
		$.post(addressUpdateModel, data, function(send){
			if(send.state){
				$("#loader-login").fadeOut('fast');
				showMessage(send.type);
				$("button[type='submit']").removeAttr("disabled");
			}
		},'JSON');
	}