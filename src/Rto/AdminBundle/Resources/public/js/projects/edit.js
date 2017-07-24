$(document).ready(function(){
		$("#form").submit(function(){
				$('#loader-login').show();
				$("button[type='submit']").attr("disabled","disabled");
				updateProject($(this).serialize());
		});
	});
	function updateProject(form){
		var data = form;
		$.post(addressUpdateProject, data, function(send){
			if(send.state){
				$("#loader-login").fadeOut('fast');
				showMessage(send.type);
				$("button[type='submit']").removeAttr("disabled");
			}
		},'JSON');
	}