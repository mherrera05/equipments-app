$(document).ready(function() {
			$('#equipments').dataTable({
				"dom" : 'T<"clear">lfrtip',
				"order": [[ 0, "desc" ]],
				"lenght": false,
				"info":     false,
				"tableTools" : {
					"sSwfPath" : "../web/swf/copy_csv_xls_pdf.swf"
				}
			});
			$('button.new-button').click(function(){
				window.location = $(this).attr('route');
			});
			$("#delete").click(function(){
				deleteEquipment($(this).attr('pk'));
			});
		});
		$(document).on('click', '.delete', function(){
			$('.modal .title').html('Do you really want to delete this equipment?');
			$('#delete').attr('pk', $(this).attr('pk'));
			$(".content-modal").show();
		});
		function deleteEquipment(id){
			var data = 'id='+id;
			$.post(addressDeleteEquipment, data, function(send){
				if(send.state){
					$(".content-modal").hide();
					$("#row-"+id).fadeOut('fast');
					showMessage(send.type);
				}
			},'JSON');
		}