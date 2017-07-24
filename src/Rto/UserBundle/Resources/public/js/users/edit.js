$(document).ready(function(){
		$("#form").submit(function(){
			if(validation()){
				$('#loader-login').show();
				$("button[type='submit']").attr("disabled","disabled");
				updateUser($(this).serialize());
			}
		});
		$("#email").change(function(){
			searchEmail($(this).val(), $("#id").val());
		});
		$("#user-id").change(function(){
			searchUserId($(this).val(), $("#id").val());
		});
	});
	function updateUser(form){
		var data = form;
		$.post(addressUpdateUser, data, function(send){
			if(send.state){
				$("#loader-login").fadeOut('fast');
				showMessage(send.type);
				$("button[type='submit']").removeAttr("disabled");
			}
		},'JSON');
	}
	function searchEmail(email,id){
		var data = 'email='+email+'&id='+id;
		$.post(addressSearchEmail, data, function(send){
			if(send.state){
				$("#email").removeClass("error");
			}else{
				$("#email").addClass("error");
			}
		}, 'JSON');
	}
	function searchUserId(userId, id){
		var data = 'userid='+userId+'&id='+id;
		$.post(addressSearchUserId, data, function(send){
			if(send.state){
				$("#user-id").removeClass("error");
			}else{
				$("#user-id").addClass("error");
			}
		}, 'JSON');
	}
	function validation(){
		if(($("#email").hasClass("error"))&&($("#user-id").hasClass("error"))){
			return false;
		}
		return true;
	}