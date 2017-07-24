$(document).on( "click", "#remove", function() {
  		removeEquipment($(this));
	});
	/*$(document).on("click", ".ui-menu-item",function(){
      		searchEquipment($(this).text());
     });*/
	$(document).ready(function() {
		$('#refer').multiSelect({
			selectableHeader: "<div class='custom-header'>Staff</div>",
  			selectionHeader: "<div class='custom-header'>Copy to</div>"
		});
		$("#serial").autocomplete({
			source: serials,
			select: function( event, ui ) {
        				$("#loader-serial").show();
						searchEquipment(ui.item.value);
				    }
      	});
		$("#form").submit(function() {
			if (validation()) {
				$('#loader-login').show();
				$("button[type='submit']").attr("disabled", "disabled");
				createInstallEquipments($(this).serialize());
			}
		});
		$("#project").change(function() {
			if ($(this).val() != '') {
				$("#loader-model").show();
				searchLocation($(this).val());
			}
		});
		$("#location").change(function() {
			if ($(this).val() != '') {
				$("#loader-model").show();
				loadDetails($(this).val());
			}
		});
		$("#serial").change(function() {
			if (($(this).val() != '') && ($(this).val().length > 5)) {
				$("#loader-serial").show();
				searchEquipment($(this).val());
			}
		});
		$("#remove").click(function(){
			removeEquipment($(this));
		});
		$("#date").datepicker({
			dateFormat: 'yy-mm-dd'
		});
		$('#select-all').click(function(){
		  $('#refer').multiSelect('select_all');
		  return false;
		});
		$('#deselect-all').click(function(){
		  $('#refer').multiSelect('deselect_all');
		  return false;
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
	function searchLocation(id) {
		$("#location").html("<option value=''>Choose..</option>");
		var data = "project=" + id;
		$.post(addressSearchLocation, data, function(send) {
			if (send.state) {
				if (send.type != '') {
					showMessage(send.type);
					$("#loader-model").fadeOut('fast');
				} else {
					loadElement($("#location"), send.locations);
					$("#loader-model").fadeOut('fast');
				}
			}
		}, 'JSON');
	}
	function searchEquipment(id) {
		var data = "serial=" + id;
		$.post(addressSearchEquipment, data, function(send) {
			if (send.state) {
				$("#loader-serial").fadeOut('fast');
				if(send.equipment != ''){
					if(elements.indexOf(send.equipment.id) == -1){
						loadEquipment($("#equipments"), send.equipment);
						showMessage(send.type);
						$('#serial').val('').focus();
					}
				}else{
					$("#serial").val('').focus();
				}
				showMessage(send.type);
			}
		}, 'JSON');
	}
	function removeEquipment(element){
		position = elements.indexOf(element.attr('pk'));
		elements.splice(position, 1);
		i--;
		element.parent().parent().remove();
		showMessage(58);
		$('#serial').focus();
	}
	function createInstallEquipments(form){
		var data = form +'&equipments='+elements;
		$.post(addressCreateInstallEquipments, data, function(send){
			if(send.state){
				showMessage(send.type);
				$("#loader-login").fadeOut('fast');
				$("#form")[0].reset();
				$("#equipments").find("ul").next().remove();
				$("button[type='submit']").removeAttr("disabled");
				elements = new Array();
				$('#refer').multiSelect('refresh');
				$("section.content > section > section:nth-child(2) > ul").html('');
			}
		}, 'JSON');
	}
	function validation() {
		if (elements.length == 0) {
			$("#equipments").addClass('error');
			return false;
		}
		return true;
	}
	function loadDetails(id){
		var data = 'id='+id;
		$.post(addressLoadDetails, data, function(send){
			if(send.state){
				$("section.content > section > section:nth-child(2) > ul").html('');
				$("section.content > section > section:nth-child(2) > ul").html('<li class="title">Point selected</div>');
				for(var i = 0; i < send.equipments.length; i++){
					$("section.content > section > section:nth-child(2) > ul").append('<li class="content-list">'+send.equipments[i].serial+' - '+send.equipments[i].brand+' '+send.equipments[i].model+' </li>');
				}
				if(send.type != ''){
					showMessage(send.type);
				}else{
					$(".alert").hide();
				}
				$("#loader-model").hide();
			}
		}, 'JSON');
	}