class Class_total_top10By_EMP
{
	constructor(){
		this.param = [];
	}

	graphShowPie = (sel,data) => {
		let d_pie = data;
		for (var i=0;i<d_pie.length;i++){
		    d_pie[i].label+=' ('+d_pie[i].data+')'
		}
		// graph
		$.plot(sel, d_pie, $.extend(true, {}, Plugins.getFlotDefaults(), {
			series: {
				pie: {
					show: true,
					radius: 1,
					label: {
						show: false
					}
				},
		
			},
			grid: {
				hoverable: true
			},
			tooltip: true,
			tooltipOpts: {
				content: '%p.0%, %s', // show percentages, rounding to 2 decimal places
				shifts: {
					x: 20,
					y: 0
				}
			}
		}));
	}

	chart = async() => {
		let url  = base_url_js+'it/summary_knowledgebase/pie_chart_total_top10By_EMP'
		const processData =  await AjaxSubmitFormPromises(url,'');
		const sel = $('#total_top10By_EMP');
		this.graphShowPie(sel,processData);
	}
}

const total_top10By_EMP = new Class_total_top10By_EMP();

$(document).ready(function(e){
	total_top10By_EMP.chart();
})