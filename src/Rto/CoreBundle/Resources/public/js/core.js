angular.module('buttonApp', ['ngMaterial', 'ngMessages']).controller('button', function() {
		  this.topDirections = ['left', 'up'];
	      this.bottomDirections = ['down', 'right'];
	      this.isOpen = false;
	      this.availableModes = ['md-fling', 'md-scale'];
	      this.selectedMode = 'md-scale';
	      this.availableDirections = ['up', 'down', 'left', 'right'];
	      this.selectedDirection = 'down';
		  	});
$(document).ready(
	function(){
		
		$(".menu").click(function()
		{
			if($(this).hasClass("active")){
				$('.menu').before().removeClass('active');
				$(".menu").next().find("ul").slideUp('fast');
			}
			else{
				$('.menu').before().removeClass('active');
				$(".menu").next().find("ul").slideUp('fast');
				$(this).before().addClass('active');
				$(this).next().find("ul").slideDown('fast');
			}
		});
		$(".content-modal .modal .close").click(function(){
			$(".content-modal").hide();
		});
		$("#close").click(function(){
			$(".content-modal").hide();
			return false;
		});
		$(".side-bar > div:nth(1)").click(function(){
			$(".side-bar").toggleClass('show');
		});
		$("#center-button").draggable().click(function(){
		 		$(this).animate({height:'274px'}, 200);
		 	}).mouseleave(function(){
		 		$(this).next().trigger("click");
		 		$(this).animate({height:'52px'}, 300);
		 });
});
function showMessage(type){
	var array = new Array();
	//Operators
	array[1] = 'The new operator could not be created..';
	array[2] = 'The new operator has been created..';
	array[3] = 'Your profile has not been updated..';
	array[4] = 'Your profile has been updated..';
	array[5] = 'The operator has not been updated..';
	array[6] = 'The operator has been updated..';
	array[7] = 'The operator has not been deleted..';
	array[8] = 'The operator has been deleted..';
	//Type Equipments
	array[9] = 'The new type of equipment could not be created..';
	array[10] = 'The new type of equipment has been created..';
	array[11] = 'The type of equipment could not be updated..';
	array[12] = 'The type of equipment has been update..';
	array[13] = 'The type of equipment could not be deleted..';
	array[14] = 'The type of equipment has been deleted..';
	//Brands
	array[15] = 'The new brand could not be created..';
	array[16] = 'The new brand has been created..';
	array[17] = 'The brand could not be updated..';
	array[18] = 'The brand has been updated..';
	array[19] = 'The brand could not be deleted..';
	array[20] = 'The brand has been deleted..';
	//Models
	array[21] = 'The model could not be created..';
	array[22] = 'The model has been created..';
	array[23] = 'The model could not be updated..';
	array[24] = 'The model has been updated..';
	array[25] = 'The model could not be deleted..';
	array[26] = 'The model has been deleted..';
	//Projects
	array[27] = 'The new project could not be created..';
	array[28] = 'The new project has been created..';
	array[29] = 'The project could not be updated..';
	array[30] = 'The project has been updated..';
	array[31] = 'The project could not be deleted..';
	array[32] = 'The project has been deleted..';
	//Type Locations
	array[33] = 'The new type of location could not be created..';
	array[34] = 'The new type of location has been created..';
	array[35] = 'The type of location could not be updated..';
	array[36] = 'The type of location has been updated..';
	array[37] = 'The type of location could not be deleted..';
	array[38] = 'The type of location has been deleted..';
	//Locations
	array[39] = 'The new location could not be created..';
	array[40] = 'The new location has been created..';
	array[41] = 'The location could not be updated..';
	array[42] = 'The location has been updated..';
	array[43] = 'The location could not be deleted..';
	array[44] = 'The location has been deleted..';
	//Models 
	array[45] = 'There are no models..';
	array[46] = '';
	//Equipments 
	array[47] = 'The new equipment could not be created..';
	array[48] = 'The new equipment have been created..';
	array[49] = 'The equipment could not be updated..';
	array[50] = 'The equipment have been updated..';
	array[51] = 'The equipment could not be deleted..';
	array[52] = 'The equipment have been deleted..';
	array[53] = 'There ara not locations in this project..';
	array[54] = '';
	array[55] = 'Serial does not match..';
	array[56] = 'Equipment added..';
	array[57] = 'The equipment is currently assigned..';
	array[58] = 'The equipment has been removed from the installation..';
	// Installation
	array[59] = 'The new installation could not be created..';
	array[60] = 'The new installation has been created..';
	// Location
	array[61] = 'Details of equipments could not be loaded..';
	array[62] = '';
	array[63] = 'There are not equipments in the selected location..';
	array[64] = '';
	// Uninstallation
	array[65] = 'The new uninstallation could not be created..';
	array[66] = 'The new uninstallation has been created..';
	// Installation Complete
	array[67] = 'The installation could not be completed..';
	array[68] = 'The installation has been completed..';
	// Uninstallation Complete
	array[69] = 'The uninstallation could not be completed..';
	array[70] = 'The uninstallation has been completed..';
	// Assigning  Complete
	array[71] = 'The operator could not be reassigned..';
	array[72] = 'The operator has been reassigned..';
	// Delete Installation
	array[73] = 'The installation could not be deleted..';
	array[74] = 'The installation has been deleted..';
	// Delete Installation
	array[75] = 'The uninstallation could not be deleted..';
	array[76] = 'The uninstallation has been deleted..';
	// Bad Equipment
	array[77] = 'The equipment is not operative..';
	// Serial
	array[79] = 'The current serial exists..';
	// MAC
	array[81] = 'The current MAC exists..';
	array[82] = 'Locations with type of equipments found..';
	array[83] = 'There are not locations with this type of equipments..';
	array[84] = 'Projects loaded..';
	array[85] = 'There are not projects to this coordinator..';
	
	if (type % 2 == 0) {
		$(".alert").html(array[type]);
		$(".alert").removeClass("alert-danger");
		$(".alert").addClass("alert-success");
	} else {
		$(".alert").html(type + ' - ' + array[type]);
		$(".alert").removeClass("alert-success");
		$(".alert").addClass("alert-danger");
	}
	$(".alert").show();
}
function loadElement(element, data) {
	var html = '';
	if (data != '') {
		html = html + '<option value="">Choose..</option>';
		for (var i = 0; i < data.length; i++) {
			html = html + '<option value="' + data[i].id + '">' + data[i].name + '</option>';
		}
		element.html(html).removeAttr("disabled").removeAttr("readonly");
		$(".alert").fadeOut('fast');
	}
}
function loadEquipment(element, data){
	$("#equipments").removeClass("error");
	element.append('<ul><li>'+data.serial+'</li><li>'+data.type+'</li><li>'+data.model+'</li><li><span id="remove" pk="'+data.id+'">X</span></li></ul>');
	elements[i] = data.id;
	i++;
}
function participationsChart(){
		 plot5 = $.jqplot('bar1', [data], {
			animate: !$.jqplot.use_excanvas,
            seriesDefaults:{
                renderer:$.jqplot.BarRenderer,
                shadowAngle: 135,
                rendererOptions: {
                    barDirection: 'horizontal',
                    highlightMouseDown: true  ,
                    barWidth: 20 ,
                    barMargin: 5   
                },
                labelOptions: {show: true},
                pointLabels: {show: true, formatString: '%d'}
            },
            legend: {
                show: false,
                location: 'e',
                placement: 'inside'
            },
            axes: {
                yaxis: {
                    renderer: $.jqplot.CategoryAxisRenderer
                },
                xaxis: {
				    min: 0,
				    max: parseInt(data[0][0])+parseInt(10),
				    tickOptions: {formatString: "%4d"},
				    tickInterval: 10 
                }
            },
		    highlighter: {
		        show: false,
		        sizeAdjust: 7.5
		      },
		    cursor: {
		      show: true,
		      tooltipLocation:'n'
		    }
        });
	}