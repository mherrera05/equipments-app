$(document).ready(function(){
		$("#form").submit(function(){
				$('#loader-login').show();
				$("button[type='submit']").attr("disabled","disabled");
				createModel($(this).serialize());
		});
	});
	function createModel(form){
		var data = form;
		$.post(addressCreateModel, data, function(send){
			if(send.state){
				$("#loader-login").fadeOut('fast');
				showMessage(send.type);
				$("#form")[0].reset();
				$("button[type='submit']").removeAttr("disabled");
			}
		},'JSON');
	}