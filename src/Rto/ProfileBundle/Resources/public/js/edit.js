$(document).ready(function(){
		$('#re-password').keyup(function(){
			if($("#password").val()!=$("#re-password").val()){
				$("#password").addClass("error");
				$("#re-password").addClass("error");
				condition = true;
			}else{
				condition = false;
				$("#password").removeClass("error");
				$("#re-password").removeClass("error");
			}
		});
		$("#form").submit(function(){
			if(!condition){
				loadForm();
				$("#loader-login").show();
				$("button[type='submit']").attr("disabled","disabled");
				updateProfile();
			}
		});
		$("div.picture > div > div").click(function(){
			if($(this).next().attr("showed") == 0){
				$(this).next().slideDown('fast');
				$(this).next().attr("showed",1);
			}else{
				$(this).next().slideUp('fast');
				$(this).next().attr("showed",0);
			}
		});
		$("#upload-picture").click(function(){
			$( "#picture" ).trigger( "click" );
			$("div.picture > div > ul").slideUp('fast').attr("showed",0);
			$("#action").val("uploaded");
		});
		$("#delete-picture").click(function(){
			$("div.picture > div > ul").slideUp('fast').attr("showed",0);
			$("#photo").attr("src", imageDefault);
			$("#action").val("deleted");
		});
		$("#take-picture").click(function(){
			$("div.picture > div > ul").slideUp('fast').attr("showed",0);
			$(".content-modal").show();
			$(".modal").hide();
			$(".modal-camera").show();
			$("#loader-upload-camera").show();
			$("#action").val("taked");
			initCamera();
		});
		$(".modal-camera .close").click(function(){
			$(".content-modal").hide();
			$(".modal").show();
			$(".modal-camera").hide();
		});
		$("#camera-cancel").click(function(){
			cancelCamera();
		});
		$("#camera-take").click(function(){
			if(shootEnabled){
				takeCamera();
			}
		});
		$("#camera-upload").click(function(){
			uploadCamera();
		});
		$("#camera-config").click(function(){
			if(shootEnabled){
				configCamera();
			}
		});
		$("#picture").change(function(){
			var filereader   = new FileReader();
			var image  	     = new Image();
			var file 		 = this.files[0];
			
			if(!!file.type.match(/image.*/)){
				filereader.readAsDataURL(file);
				if(window.FileReader){
		            filereader.onloadend = function(event){
		                image.src    = filereader.result;
		                image.onload = function()
		                {
		                 	if(this.width > 100 && this.height > 120){
		                		$('#photo').attr('src',event.target.result);
		                		data.append('file', file);
		                   	}else{
		                   		/*$('p#mjebug .texto').text('La imagen seleccionada es muy perqueña...');
	                 	    	$('p#mjebug').slideDown('medium'); */
	                 	   }
		                };
		            };
		        }
			}
		});
	});
	function updateProfile(){
		$.ajax({
		    url: addressUpdateProfile,
		    type: 'POST',
		    data: data,
		    cache: false,
		    contentType:false,
		    dataType: 'json',
		    processData: false,
		    success: function (send) {
		    	if(send.state){
		    		$("#loader-login").fadeOut('fast');
					showMessage(send.type);
					$("button[type='submit']").removeAttr("disabled");
		    	}
		    }
		});
	}
	function loadForm(){
		$.each($("#form").find('input[type!="submit"]'),function(){
			data.append($(this).attr('name'),$(this).val());
		});
	}
	function initCamera(){
		 var camera = $('#camera'),
		     photos = $('#picture'),
		     screen = $('#source');
		webcam.set_swf_url(webSource+'webcam.swf');
		webcam.set_api_url(addressUploadCamera);	
		webcam.set_quality(100);				
		webcam.set_shutter_sound(true, webSource+'shutter.mp3');
		screen.html(
				webcam.get_html(screen.width(), screen.height())
			);
		webcam.set_hook('onLoad',function(){
			shootEnabled = true;
		});
		webcam.set_hook('onComplete', function(msg){
			msg = $.parseJSON(msg);
			if(msg.error){
				alert(msg.message);
			}
			else {
				
				$("#loader-upload-camera").hide();
				$("#photo").attr("src", imageDefault);
				$("#photo").attr("src", msg.src+"?"+new Date().getTime());
				$(".content-modal").hide();
				$(".modal").show();
				$(".modal-camera").hide();
				
			}
		});
		webcam.set_hook('onError',function(e){
			screen.html(e);
		});
		$("#loader-upload-camera").hide();
		manageButtons(1);
	}
	function manageButtons(number){
		switch(number){
			case 1:{
				$("#camera-upload").hide();
				$("#camera-take").show();
				$("#camera-cancel").hide();
				$("#camera-config").show();
				break;
			}
			case 2:{
				$("#camera-upload").show();
				$("#camera-take").hide();
				$("#camera-cancel").show();
				$("#camera-config").hide();
				break;
			}
		}
	}
	function takeCamera(){
		manageButtons(2);
		webcam.freeze();
		return false;
	}
	function cancelCamera(){
		webcam.reset();
		manageButtons(1);
		return false;
	}
	function uploadCamera(){
		$("#loader-upload-camera").show();
		webcam.upload();
		manageButtons(1);
		return false;
	}
	function configCamera(){
		webcam.configure('camera');
	}