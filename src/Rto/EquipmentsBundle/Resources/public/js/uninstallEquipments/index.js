$(document).ready(function() {
			$('#uninstallations').dataTable({
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
				deleteUninstallation($(this).attr('pk'));
			});
		});
		
		$(document).on("mouseover", "table.dataTable tbody td > a.point-tooltip", function(){
				$(this).next().show();
			}).on("mouseout", "table.dataTable tbody td > a.point-tooltip", function(){
				$(this).next().hide();
		});
		
		$(document).on("mouseover","table.dataTable tbody td > a.complete-uninstallation", function(){
				$(this).html('Done');
			}).on("mouseout", "table.dataTable tbody td > a.complete-uninstallation", function(){
				$(this).html('To do');
		});
		
		$(document).on("click", "table.dataTable tbody td > a.complete-uninstallation", function(){
				$(this).next().show();
				completeUninstallation($(this).attr("pk"), $(this).next());
		});
		
		$(document).on('click', '.delete', function(){
			$('.modal .title').html('Do you really want to delete this uninstallation?');
			$('#delete').attr('pk', $(this).attr('pk'));
			$(".content-modal").show();
		});
			
		function completeUninstallation(id, loader){
			var data = 'id='+id;
			$.post(addressCompleteUninstallation, data, function(send){
				if(send.state){
					loader.hide();
					showMessage(send.type);
					loader.parent().parent().addClass('done');
					loader.parent().html('Done');
					$("#delete-"+id).remove();
				}
			},'JSON');
		}
		
		function deleteUninstallation(id){
			var data = "id="+id;
			$.post(addressDeleteUninstallation, data, function(send){
				if(send.state){
					$(".content-modal").hide();
					$("#row-"+id).fadeOut('fast');
					showMessage(send.type);
				}
			}, 'JSON');
		}