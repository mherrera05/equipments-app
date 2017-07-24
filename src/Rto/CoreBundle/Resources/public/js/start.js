$(document).ready(function(){
	$.jqplot.config.enablePlugins = true;
	participationsChart();
});
function participationsChart(){
	var ticks = [data[0][1],data[1][1],data[2][1]];
	var values = [data[0][0],data[1][0],data[2][0]];
	plot1 = $.jqplot('bar1', [values], {
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
				ticks : ticks,
				fontSize: '6pt'
			},
			yaxis : {
				min : 0,
				max: parseInt(data[0][0])+parseInt(30),
			    tickOptions: {formatString: "%4d"},
			    tickInterval: 30 
			}
		},
		series : [],
		legend : {},
		highlighter : {
			show : false
		}
	}); }