$(document).ready(function(){
		$("#form").submit(function(){
				$('#loader-login').show();
				$("button[type='submit']").attr("disabled","disabled");
				createTypeEquipments($(this).serialize());
		});
	});
	function createTypeEquipments(form){
		var data = form;
		$.post(addressCreateTypeEquipments, data, function(send){
			if(send.state){
				$("#loader-login").fadeOut('fast');
				showMessage(send.type);
				$("#form")[0].reset();
				$("button[type='submit']").removeAttr("disabled");
			}
		},'JSON');
	}