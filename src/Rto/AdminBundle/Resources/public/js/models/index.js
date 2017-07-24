$(document).ready(function() {
			$('#models').dataTable({
				"dom" : 'T<"clear">lfrtip',
				"order": [[ 0, "desc" ]],
				"lenght": false,
				"info":     false
			});
			$('button.new-button').click(function(){
				window.location = $(this).attr('route');
			});
			$("#delete").click(function(){
				deleteModel($(this).attr('pk'));
			});
		});
		$(document).on('click', '.delete', function(){
				$('.modal .title').html('Do you really want to delete the model?');
				$('#delete').attr('pk', $(this).attr('pk'));
				$(".content-modal").show();
		});
		function deleteModel(id){
			var data = 'id='+id;
			$.post(addressDeleteModel, data, function(send){
				if(send.state){
					$(".content-modal").hide();
					$("#row-"+id).fadeOut('fast');
					showMessage(send.type);
				}
			},'JSON');
		}