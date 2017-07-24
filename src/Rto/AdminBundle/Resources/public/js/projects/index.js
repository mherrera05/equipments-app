$(document).ready(function() {
			$('#projects').dataTable({
				"dom" : 'T<"clear">lfrtip',
				"order": [[ 0, "desc" ]],
				"lenght": false,
				"info":     false
			});
			$('button.new-button').click(function(){
				window.location = $(this).attr('route');
			});
			$("#delete").click(function(){
				deleteProject($(this).attr('pk'));
			});
		});
		$(document).on('click', '.delete', function(){
				$('.modal .title').html('Do you really want to delete this project?');
				$('#delete').attr('pk', $(this).attr('pk'));
				$(".content-modal").show();
		});
		function deleteProject(id){
			var data = 'id='+id;
			$.post(addressDeleteProject, data, function(send){
				if(send.state){
					$(".content-modal").hide();
					$("#row-"+id).fadeOut('fast');
					showMessage(send.type);
				}
			},'JSON');
		}