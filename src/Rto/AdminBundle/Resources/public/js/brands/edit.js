$(document).ready(function(){
		$("#form").submit(function(){
				$('#loader-login').show();
				$("button[type='submit']").attr("disabled","disabled");
				updateBrand($(this).serialize());
		});
	});
	function updateBrand(form){
		var data = form;
		$.post(addressUpdateBrand, data, function(send){
			if(send.state){
				$("#loader-login").fadeOut('fast');
				showMessage(send.type);
				$("button[type='submit']").removeAttr("disabled");
			}
		},'JSON');
	}