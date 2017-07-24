$(document).ready(function(){
		
		$("#projects").change(function(){
			if($(this).val()!=''){
				$("#loader-projects-equipments").show();
				loadEquipmentsProjects($(this).val());
			}else{
				projectsChart();
			}
		});
		
		$.jqplot.config.enablePlugins = true;
		projectsChart();
		participationsChart();
		   
	});
	function loadEquipmentsProjects(project){
		var value = 'project='+project;
		$.post(addressReportsEquipmentsProject, value, function(send){
			if(send.state){
				loadChart(send.data, send.project);
				$("#loader-projects-equipments").fadeOut('fast');
			}
		}, 'JSON');
	}
	function loadChart(data, project){
		$.jqplot.config.enablePlugins = true;
					$("#pie3").html("");
					var s1 = [data[0][1]];
					var max = 100;//Math.max.apply(null, 100);
			        var s2 = [data[1][1]];
			         
			        plot1 = $.jqplot('pie3', [s1, s2] , {
			            // Only animate if we're not using excanvas (not in IE 7 or IE 8)..
			            title: project,//name
			            animate: !$.jqplot.use_excanvas,
			            seriesDefaults:{
			                renderer:$.jqplot.BarRenderer,
			                pointLabels: { show: true },
			                rendererOptions: {barWidth: 25, barMargin: 20}
			            },
			            axes: {
			                xaxis: {
			                    renderer: $.jqplot.CategoryAxisRenderer,
			                    ticks: ['']
			                },
				            yaxis: {
				                min: 0,
				                max: max
				            }
			            },
			            series:[
			            {label:data[0][0]},
			            {label:data[1][0]}],
			        legend:{
			            show: true,
			            placement: 'insideGrid'
			        },
			            highlighter: { show: false }
			        });
	}
	function projectsChart(){
					
	$("#pie3").html("");
	plot1 = $.jqplot('pie3', [serie1, serie2], {
		animate : !$.jqplot.use_excanvas,
		seriesDefaults : {
			renderer : $.jqplot.BarRenderer,
			rendererOptions : {
				barWidth : 30,
				barMargin : 10
			}, 
          pointLabels:{show:true, stackedValue: true}
		},
		axes : {
			xaxis : {
				renderer : $.jqplot.CategoryAxisRenderer,
				ticks : ticks
			},
			yaxis : {
				min : 0,
				max : 90
			}
		},
		series : [{
			label : 'Installed'
		}, {
			label : 'Unisntalled'
		}],
		legend : {
			show : true,
			placement : 'insideGrid'
		},
		highlighter : {
			show : false
		}
	}); 
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