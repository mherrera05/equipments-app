$(document).ready(function(){
		$("#form").submit(function(){
			if(validation()){
				$('#loader-login').show();
				$("button[type='submit']").attr("disabled","disabled");
				createUser($(this).serialize());
			}
		});
		$("#email").change(function(){
			searchEmail($(this).val());
		});
		$("#user-id").change(function(){
			searchUserId($(this).val());
		});
	});
	function createUser(form){
		var data = form;
		$.post(addressCreateUser, data, function(send){
			if(send.state){
				$("#loader-login").fadeOut('fast');
				showMessage(send.type);
				$("#form")[0].reset();
				$("button[type='submit']").removeAttr("disabled");
			}
		},'JSON');
	}
	function searchEmail(email){
		var data = 'email='+email+'&id='+null;
		$.post(addressSearchEmail, data, function(send){
			if(send.state){
				$("#email").removeClass("error");
			}else{
				$("#email").addClass("error");
			}
		}, 'JSON');
	}
	function searchUserId(userId){
		var data = 'userid='+userId+'&id='+null;
		$.post(addressSearchUserId, data, function(send){
			if(send.state){
				$("#user-id").removeClass("error");
			}else{
				$("#user-id").addClass("error");
			}
		}, 'JSON');
	}
	function validation(){
		if($("#email").hasClass('error')||($('#user-id').hasClass('error'))){
			return false;
		}
		return true;
	}