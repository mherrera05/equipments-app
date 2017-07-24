$(document).ready(function() {
		$("#form").submit(function() {
			if(validation()){
				$('#loader-login').show();
				$("button[type='submit']").attr("disabled","disabled");
				createEquipment($(this).serialize());
			}
		});
		$("#type").change(function() {
			if ($("#brand").val() != "") {
				$("#loader-model").show();
				searchModels($(this).val(), $("#brand").val());
			}
		});
		$("#brand").change(function() {
			if ($("#type").val() != "") {
				$("#loader-model").show();
				searchModels($("#type").val(), $(this).val());
			}
		});
		$("#serial").change(function() {
			searchSerial($(this).val());
		});
		$("#mac").change(function() {
			macAddress($(this).val(), $(this));
		}).keyup(function(key){
			if($(this).val().length <= 17){
				var character = $(this).val().slice(-1);
				if(!character.match(/[0-9A-Fa-f]/)){
					var string = $(this).val();
					$(this).val(string.substring(0, string.length - 1));
				}
				macAddress($(this).val(), $(this));
			}else{
				var string = $(this).val();
				$(this).val(string.substring(0, string.length - 1));
			}
		});
	});
	function searchModels(type, brand) {
		$("#model").html("<option value=''>Choose..</option>");
		var data = "type=" + type + "&brand=" + brand;
		$.post(addressSearchModels, data, function(send) {
			if (send.state) {
				if (send.type != '') {
					showMessage(send.type);
					$("#loader-model").fadeOut('fast');
				} else {
					loadElement($("#model"), send.models);
					$("#loader-model").fadeOut('fast');
				}
			}
		}, 'JSON');
	}
	function searchSerial(serial) {
		var data = "serial=" + serial + "&id=" + null;
		$.post(addressSearchSerial, data, function(send) {
			if (send.state) {
				$("#serial").removeClass("error");
			} else {
				$("#serial").addClass("error");
				showMessage(79);
			}
		}, 'JSON');
	}
	function searchMac(mac) {
		var data = "mac=" + mac + "&id=" + null;
		$.post(addressSearchMac, data, function(send) {
			if (send.state) {
				$("#mac").removeClass("error");
			} else {
				$("#mac").addClass("error");
				showMessage(81);
			}
		}, 'JSON');
	}
	function createEquipment(form) {
		var data = form;
		$.post(addressCreateEquipment, data, function(send) {
			if (send.state) {
				$("#loader-login").fadeOut('fast');
				showMessage(send.type);
				$("#form")[0].reset();
				$("button[type='submit']").removeAttr('disabled');
			}
		}, 'JSON');
	}
	function validation(){
		if($("#serial").hasClass('error')||($('#mac').hasClass('error'))){
			return false;
		}
		return true;
	}
	function macAddress(string, input){
		var newString = '';
		console.log(string.length);
		for(var i=0; i < string.length; i++){
			if(i > 16 || newString.length > 16 ){
				break;
			}
			if(newString.length == 2 || newString.length == 5 || newString.length == 8 || newString.length == 11 || newString.length == 14 ){
				if(string[i] != "-"){
					newString = newString + '-';
				}
			}
			newString = newString + string[i];
		}
		input.val(newString.toUpperCase());
		searchMac(newString.toUpperCase());
	}