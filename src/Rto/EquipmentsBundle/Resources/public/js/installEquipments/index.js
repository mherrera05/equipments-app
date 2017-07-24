$(document).ready(function() {
			$('#installations').dataTable({
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
				deleteInstallation($(this).attr('pk'));
			});
		});
		$(document).on("mouseover", "table.dataTable tbody td > a.point-tooltip", function(){
			$(this).next().show();
		}).on("mouseout", "table.dataTable tbody td > a.point-tooltip", function(){
			$(this).next().hide();
		});
		
		$(document).on("mouseover", "table.dataTable tbody td > a.complete-installation",function(){
			$(this).html('Done');
		}).on("mouseout", "table.dataTable tbody td > a.complete-installation", function(){
			$(this).html('To do');
		}).on("click", "table.dataTable tbody td > a.complete-installation", function(){
			$(this).next().show();
			completeInstallation($(this).attr("pk"), $(this).next());
		});
		
		$(document).on('click', '.delete', function(){
			$('.modal .title').html('Do you really want to delete this installation?');
			$('#delete').attr('pk', $(this).attr('pk'));
			$(".content-modal").show();
		});
		
		function completeInstallation(id, loader){
			var data = 'id='+id;
			$.post(addressCompleteInstallation, data, function(send){
				if(send.state){
					loader.hide();
					showMessage(send.type);
					loader.parent().parent().addClass('done');
					loader.parent().html('Done');
					$("#delete-"+id).remove();
				}
			},'JSON');
		}
		function deleteInstallation(id){
			var data = "id="+id;
			$.post(addressDeleteInstallation, data, function(send){
				if(send.state){
					$(".content-modal").hide();
					$("#row-"+id).fadeOut('fast');
					showMessage(send.type);
				}
			}, 'JSON');
		}