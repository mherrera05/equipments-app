$(document).ready(function(){
		$('#equipments').multiSelect({
			selectableHeader: "<div class='custom-header'>Equipments installed</div>",
  			selectionHeader: "<div class='custom-header'>Equipments to uninstall</div>"
		});
		$('#refer').multiSelect({
			selectableHeader: "<div class='custom-header'>Staff</div>",
  			selectionHeader: "<div class='custom-header'>Copy to</div>"
		});
		$('#select-all').click(function(){
		  $('#equipments').multiSelect('select_all');
		  return false;
		});
		$('#deselect-all').click(function(){
		  $('#equipments').multiSelect('deselect_all');
		  return false;
		});
		$('#select-all-notify').click(function(){
		  $('#refer').multiSelect('select_all');
		  return false;
		});
		$('#deselect-all-notify').click(function(){
		  $('#refer').multiSelect('deselect_all');
		  return false;
		});
		$("#project").change(function() {
			if ($(this).val() != '') {
				$("#loader-project").show();
				searchLocation($(this).val());
			}
		});
		$("#projects-move").change(function() {
			if ($(this).val() != '') {
				$("#loader-center").show();
				searchCenter($(this).val());
			}
		});
		$("#location").change(function(){
			$("#loader-location").show();
			loadEquipments($(this).val());
		});
		$("#form").submit(function(){
			if(validate()){
				$('#loader-login').show();
				$("button[type='submit']").attr("disabled", "disabled");
				createUninstallation($(this).serialize());
			}
		});
		$("#uninstallation").click(function(){
			uninstallation();
		});
		$("#move").click(function(){
			move();
		});
		$("#date").datepicker({
			dateFormat: 'yy-mm-dd'
		});
		$('#state').change(function(){
		  if($(this).val() == 1){
		  	$("#notify").show();
		  	$("#notify").next().show();
		  }else{
		  	$("#notify").next().hide();
		  	$("#notify").hide();
		  }		 
		});
	});
	function move(){
		$("#location-center").html("<option value=''>Choose..</option>");
		$("#move-equip").find("select").removeAttr("disabled","disabled");
		$("#uninstall-equip").hide();
		$("#move-equip").show();
		$("#uninstall-equip").find("select").attr("disabled","disabled");
		return 0;
	}
	function uninstallation(){
		$("#uninstall-equip").find("select").removeAttr("disabled","disabled");
		$("#move-equip").hide();
		$("#uninstall-equip").show();
		$("#move-equip").find("select").attr("disabled","disabled");
		return 0;
	}
	function searchLocation(id) {
		$("#location").html("<option value=''>Choose..</option>");
		var data = "project=" + id;
		$.post(addressSearchLocation, data, function(send) {
			if (send.state) {
				if (send.type != '') {
					showMessage(send.type);
					$("#loader-project").fadeOut('fast');
				} else {
					loadElement($("#location"), send.locations);
					$("#loader-project").fadeOut('fast');
				}
			}
		}, 'JSON');
	}
	function searchCenter(id) {
		$("#location-center").html("<option value=''>Choose..</option>");
		var data = "project=" + id;
		$.post(addressSearchLocation, data, function(send) {
			if (send.state) {
				if (send.type != '') {
					showMessage(send.type);
					$("#loader-center").fadeOut('fast');
				} else {
					loadElement($("#location-center"), send.locations);
					$("#loader-center").fadeOut('fast');
				}
			}
		}, 'JSON');
	}
	function loadEquipments(id){
		var data = 'id='+id;
		$.post(addressLoadDetails, data, function(send){
			if(send.state){
				$('#equipments').html('');
				if(send.equipments != ''){
					$(".alert").hide();
					var html = '';
					for(var i = 0; i < send.equipments.length; i++){
						if(send.equipments[i].related == 1){
							html += '<option value="'+send.equipments[i].id+'">'+send.equipments[i].brand+' '+send.equipments[i].model+'</option>';
						}
					}
					$('#equipments').html(html);
					$('#equipments').multiSelect('refresh');
				}else{
					var html = '';
					$('#equipments').html(html);
					$('#equipments').multiSelect('refresh');
					showMessage(send.type);
				}
				$('#loader-location').hide();
			}
		}, 'JSON');
		return false;
	}
	function createUninstallation(form){
		var data = form + '&equipments='+$("#equipments").val();
		$.post(addressCreateUninstallation, data, function(send){
			if(send.state){
				$('#loader-login').hide();
				showMessage(send.type);
				$("#form")[0].reset();
				$('#equipments').html('');
				$('#equipments').multiSelect('refresh');
				$('#refer').multiSelect('refresh');
				uninstallation();
				$("button[type='submit']").removeAttr("disabled");
			}
		}, 'JSON');
		return false; 
	}
	function validate(){
		if($("#equipments").val() == null){
			$(".ms-list").addClass('error');
			return false;
		}else{
			return true;
		}
	}