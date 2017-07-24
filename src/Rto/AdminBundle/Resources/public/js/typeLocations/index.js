$(document).ready(function() {
			$('#typeLocations').dataTable({
				"dom" : 'T<"clear">lfrtip',
				"order": [[ 0, "desc" ]],
				"lenght": false,
				"info":     false
			});
			$('button.new-button').click(function(){
				window.location = $(this).attr('route');
			});
			$("#delete").click(function(){
				deleteTypeLocations($(this).attr('pk'));
			});
		});
		$(document).on('click', '.delete', function(){
			$('.modal .title').html('Do you really want to delete this type of locations?');
			$('#delete').attr('pk', $(this).attr('pk'));
			$(".content-modal").show();
		});
		function deleteTypeLocations(id){
			var data = 'id='+id;
			$.post(addressDeleteTypeLocations, data, function(send){
				if(send.state){
					$(".content-modal").hide();
					$("#row-"+id).fadeOut('fast');
					showMessage(send.type);
				}
			},'JSON');
		}