$(document).ready(function() {
		$('#typeEquipments').dataTable({
			"dom" : 'T<"clear">lfrtip',
			"order" : [[0, "desc"]],
			"lenght" : false,
			"info" : false
		});
		$('button.new-button').click(function() {
			window.location = $(this).attr('route');
		});
		$("#delete").click(function() {
			deleteTypeEquipments($(this).attr('pk'));
		});
	});
	$(document).on('click', '.delete', function(){
			$('.modal .title').html('Do you really want to delete the type of equipment?');
			$('#delete').attr('pk', $(this).attr('pk'));
			$(".content-modal").show();
	});
	function deleteTypeEquipments(id) {
		var data = 'id=' + id;
		$.post(addressDeleteTypeEquipments, data, function(send) {
			if (send.state) {
				$(".content-modal").hide();
				$("#row-" + id).fadeOut('fast');
				showMessage(send.type);
			}
		}, 'JSON');
	}