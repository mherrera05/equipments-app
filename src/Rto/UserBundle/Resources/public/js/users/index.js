$(document).ready(function() {
			$('#users').dataTable({
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
				deleteUser($(this).attr('pk'));
			});
		});
		$(document).on('click', '.delete', function(){
			$('.modal .title').html('Do you really want to delete the operator?');
			$('#delete').attr('pk', $(this).attr('pk'));
			$(".content-modal").show();
		});
		function deleteUser(id){
			var data = 'id='+id;
			$.post(addressDeleteUser, data, function(send){
				if(send.state){
					$(".content-modal").hide();
					$("#row-"+id).fadeOut('fast');
					showMessage(send.type);
				}
			},'JSON');
		}